<?php
/**
 * Social Share Locker
 * Facebook Authentication
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @link        http://suite.social
 * @license     Commercial
 *
 * @category    External Authenticaton
 * @package     Social Share Locker
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
	public function handleAuth($config = array())
	{
         if( strlen( @$_COOKIE[$this->_provider.'_access_token'] ) ) {
//         if(!empty($_SESSION['facebook'])) {
            return $this->handleAccessToken(
                array(
                    'access_token' => $_COOKIE[$this->_provider . '_access_token']
                )
            );
//         }
		} else {
			$params = array(
				'client_id'		=> $this->_envConfig->appId,
                'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri,
                'scope'			=> $this->_apiConfig->scope,
                'state'			=> UUID::mint( 4 )->__toString(),
				'response_type'	=> $this->_apiConfig->responseType,

			);

			$apiEndpoint = rawurldecode($this->_apiConfig->authEndpoint.'?'.http_build_query( $params));


//            exit;
//			print rawurldecode($apiEndpoint);

//			exit;
			header('Location: ' . $apiEndpoint  );
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
	public function getAccessToken( $code ,$config=array())
	{
		if( strlen( $code ) ) {
			$params = array(
					'client_id'		=> $this->_envConfig->appId,
					'client_secret'	=> $this->_envConfig->appSecret,
					'code'			=> $code,
					'redirect_uri'	=> AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri,

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
        // $scope = 'email,name,birthday,locale,cover,first_name,last_name,age_range,link,gender,picture,timezone,updated_time,verified';
		$apiEndpoint = $this->_apiConfig->apiEndpoint.'/me?fields='.$this->_apiConfig->scope_query.'&access_token='.$accessToken;
        //		print " endpoint " . $apiEndpoint;
		$apiResponse = json_decode( $this->curl_get_url( $apiEndpoint ), true );
		$apiResponse = $this->objectToArray( $apiResponse );


		if( isset( $apiResponse['id'] ) ) {
			$apiResponse['avatar'] = 'https://graph.facebook.com/'.$apiResponse['id'].'/picture';
		}	
                $apiResponse['network'] = 'facebook';
                // print " endpoint " . $apiEndpoint;
                // print_r($apiResponse);
                // exit;
		return $apiResponse;
	}		
}