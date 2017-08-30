<?php
/**
 * AuthAny
 * Vimeo Authentication
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Sunday, June 22, 2014, 10:03 PM GMT+1
 * @modified    $Date: 2014-06-24 10:19:35 +0200 (Tue, 24 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Vimeo.php 13 2014-06-24 08:19:35Z dev@cloneui.com $
 *
 * @category    External Authenticaton
 * @package     AuthAny
*/

class AuthAny_Provider_Vimeo extends AuthAny
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
				'scope'			=> $this->_apiConfig->scope,
				'response_type'	=> $this->_apiConfig->responseType,
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri
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
			if( isset( $user['name'] ) ) {
				if( !isset( $user['username'] ) ) {
					$user['username'] = $user['name'];	
				}
				
				$this->saveAccessToken( $data );
				return $this->handleLocalLogin( $user );	
			} else {
				trigger_error( $this->_provider.' login error:  '.var_export( $user, true ) );
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
				'grant_type'	=> 'authorization_code',
				'code'			=> $code,
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri
			);
	
			$apiEndpoint	= $this->_apiConfig->oauthEndpoint.'/access_token';
			$headers		= array('Authorization : basic '.base64_encode( $this->_envConfig->clientId.':'.$this->_envConfig->clientSecret ) );
			$response		= $this->curl_post_url( $apiEndpoint, $params, $headers );
			$response		= json_decode( $response, true );
			$response		= $this->objectToArray( $response );
	
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
			$expires = ( isset( $data['expires'] ) ) ? time() + $data['expires'] : $this->cookieTimeout; 
			$_SESSION[$this->_provider] = $data;

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
		$apiResponse = $this->curl_get_url( $this->_apiConfig->apiEndpoint.'/me', array( 'Authorization: bearer '.$accessToken ) );
		$apiResponse = json_decode( $apiResponse, true );
		$apiResponse = $this->objectToArray( $apiResponse );	
		
		return $apiResponse;
	}		
}