<?php
/**
 * AuthAny
 * Windows Live Authentication
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * 
 * @link		http://msdn.microsoft.com/en-us/library/hh243649.aspx
 * @link		http://msdn.microsoft.com/en-us/library/hh243649.aspx#authorization_rest
 *
 * @license     Commercial
 * @since  	    Monday, June 23, 2014, 01:46 AM GMT+1
 * @modified    $Date: 2014-06-24 10:19:35 +0200 (Tue, 24 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Windowslive.php 13 2014-06-24 08:19:35Z dev@cloneui.com $
 *
 * @category    External Authenticaton
 * @package     AuthAny
*/

class AuthAny_Provider_Windowslive extends AuthAny
{
	private $_apiConfig;
	private $_envConfig;
	private $_provider;
	private $_providerConfig;
	
	/**
	 * Constructor
	 *
	 * @param   string	$env
	 * @return  void
	*/
	public function __construct( $env = 'live' )
	{
		parent::__construct( $env );
		
		$this->_provider		= $this->getRequestedProvider();
		$this->_providerConfig	= $this->getProviderConfig( $this->_provider );
		$this->_apiConfig		= $this->_providerConfig->get('api'); 
		$this->_envConfig		= $this->_providerConfig->get( $this->env );
		
		if( $this->_providerConfig->{$this->env}->enabled != 1 ) {
			// not enabled
			trigger_error( 'Provider:  '.$this->_provider.' not enabled', E_USER_ERROR );
		}
	}
		
	/**
	 * Get Authentication Code
	 *
	 * @return	array
	*/	
	public function handleAuth()
	{
		$params = array(
			'client_id'		=> $this->_envConfig->clientId,
			'scope'			=> $this->_apiConfig->scope,
			'response_type'	=> $this->_apiConfig->responseType,
			'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri
		);
		
		$apiEndpoint = $this->_apiConfig->authEndpoint.'?'.http_build_query( $params );
		header('Location: ' . $apiEndpoint );
	}	
	
	/**
	 * Handle Authentication Code
	 *
	 * @param	string	$code
	 * @return	array
	*/	
	public function handleCode( $code )
	{
		$code = trim( $code );
		if( strlen( $code ) ) {
			$response = $this->getAccessToken( $code );
	
			if( isset( $response['access_token'] ) AND isset( $response['user_id'] ) ) {
				return $this->handleAccessToken( $response );	
			} else {
				return $response;	
			}
		}
	}	
	
	/**
	 * Handle Access Token
	 *
	 * @param	array	$data
	 * @return	array
	*/	
	public function handleAccessToken( $data = array() ) 
	{
		if( isset( $data['access_token'] ) ) {
			$user = $this->getUserDetails( $data['access_token'] );
			
			if( isset( $user['emails'] ) ) {
				if( !isset( $user['username'] ) ) {
					$user['username'] = $user['name'];	
				}
				
				return $this->handleLocalLogin( $user );	
			} else {
				error_log( $this->_provider.' login error:  '.var_export( $user, true ) );
				$this->deleteAccessToken();
				
				header('Location: ' . AUTHANY_BASEURL );	
			}
		} else {
			error_log( $this->_provider.' login error, no access token:  '.var_export( $data, true ) );
			$this->deleteAccessToken();
				
			header('Location: ' . AUTHANY_BASEURL );	
		}
	}
	

	/**
	 * Get Access Token
	 *
	 * @param	string	$code
	 * @return	array
	 */
	public function getAccessToken( $code )
	{
		if( strlen( $code ) ) {
			$params = array(
				'client_id'		=> $this->_envConfig->clientId,
				'client_secret'	=> urlencode( $this->_envConfig->clientSecret ),
				'code'			=> $code,
				'redirect_uri'	=> urlencode( AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri ),
				'grant_type'	=> 'authorization_code'
			);
			
			$headers		= array('Content-Type: application/x-www-form-urlencoded');
			$apiEndpoint	= $this->_apiConfig->oauthEndpoint;
			$response		= $this->curl_post_url( $apiEndpoint, $params, $headers );
			$response		= json_decode( $response, true );
			$response		= $this->objectToArray( $response );
			
			if( isset( $response['access_token'] ) ) {
				$this->saveAccessToken( $response );	
			} else {
				error_log( 'No token:  '.var_export( $response, true ) );	
			}
	
			return $response;
		}
	}

	/**
	 * Delete Access Token
	 *
	 * @return	array
	*/
	public function deleteAccessToken()
	{
		$result		= array();
		$result[]	= setcookie( $this->_provider.'_authentication_token', '', time() - 3600, '/' );
		$result[]	= setcookie( $this->_provider.'_user_id', '', time() - 3600, '/' );
		$result[]	= setcookie( $this->_provider.'_access_token', '', time() - 3600, '/' );
	
		return $result;
	}	
	
	/**
	 * Save Access Token
	 *
	 * @param	array	$data
	 * @return	boolean
	*/	
	public function saveAccessToken( $data = array() ) 
	{
		if( !empty( $data ) ) {			
			$expires = ( isset( $data['expires_in'] ) ) ? time() + $data['expires_in'] : $this->cookieTimeout; 
			$_SESSION[$this->_provider] = $data;
			
			setrawcookie( $this->_provider.'_authentication_token', trim( $data['authentication_token'] ), $expires, '/' );
			setrawcookie( $this->_provider.'_user_id', trim( $data['user_id'] ), $expires, '/' );
			
			return setrawcookie( $this->_provider.'_access_token', trim( $data['access_token'] ), $expires, '/' );
		}	
	}
	 
	/**
	 * Get User Details
	 * 
	 * @param	string	$accessToken
	 * @return	array
	*/	
	public function getUserDetails( $accessToken )
	{
		$apiEndpoint = $this->_apiConfig->apiEndpoint;
		$apiEndpoint = $apiEndpoint.'/me?access_token='.$accessToken; 
		$apiResponse = $this->curl_get_url( $apiEndpoint );
		$apiResponse = json_decode( $apiResponse, true );
		$apiResponse = $this->objectToArray( $apiResponse );
		
		if( isset( $apiResponse['emails'] ) ) {
			$apiResponse['email'] = $apiResponse['emails']['preferred'];	
		} else {
			error_log( 'Failed to get user\'s email address' );	
		}
		
		return $apiResponse;
	}

	/**
	 * Call the Local Login Function or Class
	 * specified in $loginConfig
	 *
	 * @param	array	$data
	 * @return	mixed
	*/
	public function handleLocalLogin( $data = array() )
	{
		return $this->loginLocal( $data );
	}
	
	/**
	 * Login a User Locally
	 *
	 * @param	array	$data
	 * @return	void
	*/
	public function loginLocal( $data = array() )
	{
		if( !empty( $data ) ) {
			$provider								= $this->getRequestedProvider();
			$_SESSION[$provider]					= $data;
			$_SESSION['email']						= $_SESSION[$provider]['email'];
			$_SESSION['logged_in']					= 1;
			$_SESSION['network']					= array();
			$_SESSION['network']['name']			= $provider;
			// set network display name
			$_SESSION['network']['display_name']	= 'Windows Live';
			// set network's short ID
			$_SESSION['network']['short_id']		= 'windows';
				
			// redirect
			header( 'Location: '.AUTHANY_BASEURL );
		}
	}	
}