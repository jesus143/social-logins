<?php
/**
 * AuthAny
 * Facebook Authentication
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Saturday, June 21, 2014, 07:42 AM GMT+1
 * @modified    $Date: 2014-06-22 19:54:47 +0200 (Sun, 22 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Facebook.php 7 2014-06-22 17:54:47Z dev@cloneui.com $
 *
 * @category    External Authenticaton
 * @package     AuthAny
*/

class AuthAny_Provider_Facebook extends AuthAny
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
				'client_id'		=> $this->_envConfig->appId,
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
		//echo "<pre>";
		//echo "handleCode <br> <br>";
		$code = trim( $code );
		//echo "handleCode 11111111<br> <br>";
		if( strlen( $code ) ) {
			$token = $this->getAccessToken( $code, $this->_providerConfig );
			//print_r($token);
			//echo "handleCode 22222222222 <br> <br>";
			$response = json_decode($token,true);
			//parse_str( $token, $response );
			//print_r($response);
			//exit();
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
				$this->saveAccessToken( $data );
				
				// check for e-mail
				if( !strlen( @$user['email'] ) ) {
				    $_SESSION['badEmail'] = true;
				} else {
				    $_SESSION['badEmail'] = false;
				}
				
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
					'client_id'		=> $this->_envConfig->appId,
					'client_secret'	=> $this->_envConfig->appSecret,
					'code'			=> $code,
					'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri
			);
	
			$apiEndpoint	= $this->_apiConfig->oauthEndpoint.'?'.http_build_query( $params );
			$response = $this->ObjectToArray($this->curl_post_url( $this->_apiConfig->oauthEndpoint, $params ));
	
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
	 * @link	https://developers.facebook.com/docs/graph-api/reference/v2.0/user/picture
	 * @param	string	$accessToken
	 * @return	array
	*/	
	public function getUserDetails( $accessToken )
	{
		$apiEndpoint = $this->_apiConfig->apiEndpoint.'/me?access_token='.$accessToken;
		$apiResponse = json_decode( $this->curl_get_url( $apiEndpoint ), true );
		$apiResponse = $this->objectToArray( $apiResponse );
		
		if( isset( $apiResponse['id'] ) ) {
			$apiResponse['avatar'] = 'https://graph.facebook.com/'.$apiResponse['id'].'/picture';
		}	
		
		return $apiResponse;
	}		
}