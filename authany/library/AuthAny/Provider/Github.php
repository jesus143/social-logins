<?php
/**
 * AuthAny
 * GitHub Authentication
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Saturday, June 21, 2014, 09:15 PM GMT+1
 * @modified    $Date: 2014-06-22 19:54:47 +0200 (Sun, 22 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Github.php 7 2014-06-22 17:54:47Z dev@cloneui.com $
 *
 * @category    External Authenticaton
 * @package     AuthAny
*/

class AuthAny_Provider_Github extends AuthAny
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
			trigger_error('Provider:  '.$this->_provider.' not enabled', E_USER_ERROR);
		}
	}

	/**
	 * Request an Authorization Code
	 *
	 * @return  array
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
				'scope'			=> $this->_apiConfig->scope,
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri
			);
			
			$apiEndpoint = $this->_apiConfig->authEndpoint.'?'.http_build_query( $params );
			header('Location: ' . $apiEndpoint );			
		}
	}	
	
	/**
	 * Handle Authorization Code
	 * 
	 * @param	string	$code
	 * @return  array
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
	 * @return  array
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
				return $user;	
			}
		} else {
			trigger_error( $this->_provider.' login error, no access token:  '.var_export( $data, true ) );
			return $data;	
		}
	}
	

	/**
	 * Gt Access Token
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
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri
			);
	
			$headers		= array('Accept: application/json');
			$apiEndpoint	= $this->_apiConfig->oauthEndpoint;
			$apiResponse	= $this->curl_post_url( $apiEndpoint, $params, $headers );
			$apiResponse	= $this->objectToArray( json_decode( $apiResponse, true ) );
	
			return $apiResponse;
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
			$expires = ( isset( $data['expires_in'] ) ) ? time() + $data['expires_in'] : $this->cookieTimeout; 	
			$_SESSION[$this->_provider] = $data;
			
			return setrawcookie( $this->_provider.'_access_token', trim( $data['access_token'] ), $expires, '/' );
		}	
	}
	
	/**
	 * Get User Details via 
	 * the API
	 * 
	 * @param	string	$accessToken
	 * @return	array
	*/
	public function getUserDetails( $accessToken )
	{
		$headers		= array('Authorization: token '.$accessToken, 'Accept: application/json');
		$apiResponse 	= json_decode( $this->curl_get_url( $this->_apiConfig->apiEndpoint, $headers ) );
		$apiResponse 	= $this->objectToArray( $apiResponse );
		
		if( isset( $apiResponse['login'] ) ) {
			$response = $this->getEmailAddress( $accessToken );	
			if( isset( $response['email'] ) ) {
				$apiResponse['email'] = $response['email'];	
			}
			
			if( isset( $response['emails'] ) ) {
				$apiResponse['emails'] = $response['emails'];
			}			
		}	
		
		return $apiResponse;
	}

	/**
	 * Get User's e-mail Addresses via
	 * the API
	 *
	 * @param	string	$accessToken
	 * @return	array
	*/	
	public function getEmailAddress( $accessToken )
	{
		if( strlen( $accessToken ) ) {	
			$headers		= array('Authorization: token '.$accessToken, 'Accept: application/json');
			$apiResponse	= $this->curl_get_url( $this->_apiConfig->apiEndpoint.'/emails', $headers );
			$apiResponse 	= json_decode( $apiResponse );
			$apiResponse 	= $this->objectToArray( $apiResponse );
				
			if( !empty( $apiResponse ) ) {
				$emails = $apiResponse;
				
				foreach( $apiResponse AS $key => $value ) {
					if( $value['primary'] == 1 ) {
						$apiResponse['email'] = $value['email'];
					}
				}
				
				$apiResponse['emails'] = $emails;
								
				return $apiResponse;
			} else {
				trigger_error( 'Unable to get user\'s e-mail address from GitHub' );
			}
		}
	}
}