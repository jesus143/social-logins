<?php
/**
 * AuthAny
 * Google+ Authentication
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @link		https://developers.google.com/accounts/docs/OAuth2WebServer
 * @license     Commercial
 *
 * @since  	    Sunday, June 22, 2014, 02:22 AM GMT+1
 * @modified    $Date: 2014-06-24 10:19:35 +0200 (Tue, 24 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Googleplus.php 13 2014-06-24 08:19:35Z dev@cloneui.com $
 *
 * @category    External Authenticaton
 * @package     AuthAny
*/

class AuthAny_Provider_Googleplus extends AuthAny
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
		if( strlen( @$_COOKIE[$this->_provider.'_access_token'] ) ) {
			return $this->handleAccessToken( 
				array( 
					'access_token' => $_COOKIE[$this->_provider.'_access_token'] 
				) 
			);
		} else {
			$params = array(
				'client_id'		=> $this->_envConfig->clientId,
				'state'			=> UUID::mint( 4 )->__toString(),
				'response_type'	=> $this->_apiConfig->responseType,
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri,
				'scope'			=> $this->_apiConfig->scope,
				'access_type'	=> $this->_apiConfig->accessType
			);
			
			$apiEndpoint = $this->_apiConfig->authEndpoint.'?'.http_build_query( $params );
			header('Location: ' . $apiEndpoint );			
		}
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
			$response = $this->getAccessToken( $code, $this->_providerConfig );
	
			if( isset( $response['access_token'] ) ) {
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
						
			if( isset( $user['email'] ) ) {
				$this->saveAccessToken( $data );				
				return $this->handleLocalLogin( $user );	
			} else {
				trigger_error( $this->_provider.' login error:  '.var_export( $user, true ) );
				$this->deleteAccessToken();
				
				return $user;	
			}
		} else {
			trigger_error( $this->_provider.' login error, no access token:  '.var_export( $data, true ) );
			return $data;	
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
				'client_secret'	=> $this->_envConfig->clientSecret,
				'code'			=> $code,
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri,
				'grant_type'	=> 'authorization_code'
			);
	
			$response = $this->ObjectToArray( json_decode( $this->curl_post_url( $this->_apiConfig->oauthEndpoint, $params ) ) );

			return $response;
		}
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
			$expires = ( isset( $data['expires_in'] ) ) ? ( time() + $data['expires_in'] ) : $this->cookieTimeout; 			
			$_SESSION[$this->_provider] = $data;
			
			return setrawcookie( $this->_provider.'_access_token', trim( $data['access_token'] ), $expires, '/' );
		}	
	}
	
	/**
	 * Delete Access Token
	 *
	 * @return	boolean
	*/
	public function deleteAccessToken()
	{
		return setcookie( $this->_provider.'_access_token', '', time() - 3600, '/' );
	}	
	 
	/**
	 * Get User Details
	 * 
	 * @param	string	$accessToken
	 * @return	array
	*/	
	public function getUserDetails( $accessToken )
	{
		$apiEndpoint = $this->_apiConfig->apiEndpoint.'/me?access_token='.$accessToken;
		$apiResponse = json_decode( $this->curl_get_url( $apiEndpoint ) );
		$apiResponse = $this->objectToArray( $apiResponse );

		if( isset( $apiResponse['emails'] ) ) {
			$apiResponse['email'] = $apiResponse['emails'][0]['value'];			
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
	/*public function handleLocalLogin( $data = array() )
	{
		return $this->loginLocal( $data );
	}	*/

	/**
	 * Login a User Locally
	 *
	 * @param	array	$data
	 * @return	void
	*/
	/*public function loginLocal( $data = array() )
	{
		if( !empty( $data ) ) {
			$provider								= $this->getRequestedProvider();
			$_SESSION[$provider]					= $data;
			$_SESSION['email']						= $_SESSION[$provider]['email'];
			$_SESSION['logged_in']					= 1;
			$_SESSION['network']					= array();
			$_SESSION['network']['name']			= $provider;				
			// set network display name
			$_SESSION['network']['display_name']	= 'Google+';
			// set network's short ID
			$_SESSION['network']['short_id']		= 'google-plus';
			
			// redirect
			header( 'Location: '.AUTHANY_BASEURL );
		}
	}*/
}