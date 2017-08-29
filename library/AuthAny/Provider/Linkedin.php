<?php
/**
 * Social Share Locker
 * LinkedIn Authentication
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @license     Commercial
 * @link        http://suite.social
 * 
 * @link		https://developer.linkedin.com/documents/authentication
 * @link		https://developer.linkedin.com/documents/making-api-call-oauth-token
 *
 * @category    External Authenticaton
 * @package     Social Share Locker
*/

class AuthAny_Provider_Linkedin extends AuthAny
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
				'client_id'		=> $this->_envConfig->apiKey,
				'state'			=> UUID::mint( 4 )->__toString(),
				'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri,
				'scope'			=> $this->_apiConfig->scope,
				'response_type'	=> $this->_apiConfig->responseType
			);
			
			$apiEndpoint = $this->_apiConfig->authEndpoint.'/authorization?'.http_build_query( $params );
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
			$profile_picture = $this->getProfilePicture( $data['access_token'] );
                        $lipro_pic='';
                        if(isset($profile_picture['_total']) && $profile_picture['_total']>0){
                            $lipro_pic=$profile_picture['values'][0];
                        }

						
			if( isset( $user['firstName'] ) ) {
				// get e-mail address
				$email = trim( $this->getUserEmail( $data['access_token'] ) );
				//$profile_picture = trim( $this->getProfilePicture( $data['access_token'],$user['id'] ) );
                                $user['profile_picture']=$lipro_pic;

				if( strlen( $email ) ) {
					$user['email'] = $email;	
				} else {
					trigger_error( 'Unable to retrieve e-mail address for:  '.$this->_provider );	
				}
                                                $user['network']='linkedin';

				
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
				'client_id'		=> $this->_envConfig->apiKey,
				'client_secret'	=> $this->_envConfig->secretKey,
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
		$headers		= array('x-li-format: json');		
		//$apiEndpoint	= $this->_apiConfig->apiEndpoint.'?oauth2_access_token='.$accessToken;
                $apiEndpoint	= $this->_apiConfig->apiEndpoint.':(id,email-address,first-name,last-name,formatted-name,picture-url,location)?format=json&oauth2_access_token='.$accessToken;
                //$apiEndpoint	= $this->_apiConfig->apiEndpoint.'/picture-urls::(original)?oauth2_access_token='.$accessToken;
                 
		$apiResponse	= json_decode( $this->curl_get_url( $apiEndpoint, $headers ), true );		
		$apiResponse	= $this->objectToArray( $apiResponse );
		
		return $apiResponse;
	}
	/**
	 * Get User Details
	 * 
	 * @param	string	$accessToken
	 * @return	array
	*/	
	public function getProfilePicture( $accessToken )
	{
		$headers		= array('x-li-format: json');		
                $apiEndpoint	= $this->_apiConfig->apiEndpoint.'/picture-urls::(original)?oauth2_access_token='.$accessToken;
                 
		$apiResponse	= json_decode( $this->curl_get_url( $apiEndpoint, $headers ), true );		
		$apiResponse	= $this->objectToArray( $apiResponse );
		
		return $apiResponse;
	}
	
	/**
	 * Get User's e-mail Adddress
	 *
	 * @param	string	$accessToken
	 * @return	array
	 */
	public function getUserEmail( $accessToken )
	{
		$headers		= array('x-li-format: json');
		$apiEndpoint	= $this->_apiConfig->apiEndpoint.'/email-address?oauth2_access_token='.$accessToken;
		$apiResponse	= json_decode( $this->curl_get_url( $apiEndpoint, $headers ), true );
		$apiResponse	= $this->objectToArray( $apiResponse );
	
		return $apiResponse;
	}
}