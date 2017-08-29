<?php
/**
 * Social Share Locker
 * Instagram Authentication
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @license     Commercial
 * @link        http://suite.social
 * 
 * @link		http://instagram.com/developer/authentication
 * @link		http://instagram.com/developer/restrict-api-requests
 * @link		http://instagram.com/developer/endpoints
 *
 * @category    External Authenticaton
 * @package     Social Share Locker
*/

class AuthAny_Provider_Instagram extends AuthAny
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
				'scope'			=> $this->_apiConfig->scope
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
						
			if( isset( $user['username'] ) ) {
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
			
			if( $this->_envConfig->enforceSignedHeader == 1 ) {
				$signature = ( hash_hmac( 'sha256', $_SERVER['REMOTE_ADDR'], $this->_envConfig->clientSecret, false ) );
				$params['X-Insta-Forwarded-For'] = $_SERVER['REMOTE_ADDR'].'|'.$signature;	
			}
	
			$response = $this->ObjectToArray( json_decode( $this->curl_post_url( $this->_apiConfig->oauthEndpoint, $params ), true ) );

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
	 * @link	https://developers.facebook.com/docs/graph-api/reference/v2.0/user/picture
	 * @param	string	$accessToken
	 * @return	array
	*/	
	public function getUserDetails( $accessToken )
	{
		$apiEndpoint = $this->_apiConfig->apiEndpoint.'/users/self?access_token='.$accessToken;
		$apiResponse = json_decode( $this->curl_get_url( $apiEndpoint ), true );
		$apiResponse = $this->objectToArray( $apiResponse );
		$apiResponse = $apiResponse['data'];
		
		return $apiResponse;
	}
}