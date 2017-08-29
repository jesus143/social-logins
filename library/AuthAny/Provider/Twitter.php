<?php
/**
 * Social Share Locker
 * Twitter Authentication
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @license     Commercial
 * @link        http://suite.social
 *
 * @category    External Authenticaton
 * @package     Social Share Locker
*/

require_once( dirname( AUTHANY_ROOT ).'/Twitter.php' );
class AuthAny_Provider_Twitter extends AuthAny
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
		if( strlen( @$_COOKIE[$this->_provider.'_access_token'] ) AND strlen( @$_COOKIE[$this->_provider.'_access_secret'] ) ) {
			return $this->handleAccessToken( 
				array( 
					'access_token'	=> $_COOKIE[$this->_provider.'_access_token'],
					'access_secret' => $_COOKIE[$this->_provider.'_access_secret']
				) 
			);
		} else {
			if( empty( $_REQUEST ) ) {
				// Create a new instance of the TwitterOAuth library.  For this step, all we need to give the library is our
				// Consumer Key and Consumer Secret
				$Twitter = new Twitter( $this->_envConfig->key, $this->_envConfig->secret );
				 
				// Ask Twitter for a Request Token
				$request_token = $Twitter->getRequestToken( AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri );
				 
				// Store the request token and Request Token Secret
				$_SESSION[$this->_provider]							= array();
				$_SESSION[$this->_provider]['request_token']		= $request_token['oauth_token'];
				$_SESSION[$this->_provider]['request_token_secret']	= $request_token['oauth_token_secret'];
				$_SESSION[$this->_provider]['network']	= "twitter";
				 
				// Check the HTTP Code.  It should be a 200 (OK), if it's anything else then something didn't work.
				switch ( $Twitter->http_code ) {
					case 200:
						// Ask Twitter to give us a special address to their login page
						$url = $Twitter->getAuthorizeURL( $request_token['oauth_token'] );
							
						// Redirect the user to the login URL given to us by Twitter
						header('Location: ' . $url);
							
						// That's it for our side.  The user is sent to a Twitter Login page and
						// asked to authroize our app.  After that, Twitter sends the user back to
						// our Callback URL (callback.php) along with some information we need to get
						// an access token.
							
						break;
						
					default:
						// Give an error message
						trigger_error( 'Could not connect to Twitter. Refresh the page or try again later.', E_USER_ERROR );
				}
			} else {
			    if( !isset( $_SESSION[$this->_provider] ) ) {
			        // Create a new instance of the TwitterOAuth library.  For this step, all we need to give the library is our
			        // Consumer Key and Consumer Secret
			        $Twitter = new Twitter( $this->_envConfig->key, $this->_envConfig->secret );
			        	
			        // Ask Twitter for a Request Token
			        $request_token = $Twitter->getRequestToken( AUTHANY_BASEURL.'/'.$this->_envConfig->redirectUri );
			        	
			        // Store the request token and Request Token Secret
			        $_SESSION[$this->_provider]							= array();
			        $_SESSION[$this->_provider]['request_token']		= $request_token['oauth_token'];
			        $_SESSION[$this->_provider]['request_token_secret']	= $request_token['oauth_token_secret'];
			        
			        // Ask Twitter to give us a special address to their login page
			        $url = $Twitter->getAuthorizeURL( $request_token['oauth_token'] );
			        	
			        // Redirect the user to the login URL given to us by Twitter
			        header('Location: ' . $url);

			        exit;
			    }
			    
				// Once the user approves your app at Twitter, they are sent back to this script.
				// This script is passed two parameters in the URL, oauth_token (our Request Token)
				// and oauth_verifier (Key that we need to get Access Token).
				// We'll also need out Request Token Secret, which we stored in a session.
				
				// Create an instance of Twitter_OAuth.
				// It'll need our Consumer Key and Secret as well as our Request Token and Secret
				$Twitter = new Twitter( $this->_envConfig->key, $this->_envConfig->secret, $_SESSION[$this->_provider]['request_token'], $_SESSION[$this->_provider]['request_token_secret'] );
				
				// Ok, let's get an Access Token. We'll need to pass along our oauth_verifier which was given to us in the URL.
				$access_token = $Twitter->getAccessToken( $_REQUEST['oauth_verifier'] );
				
				// We're done with the Request Token and Secret so let's remove those.
				unset( $_SESSION[$this->_provider]['request_token'] );
				unset( $_SESSION[$this->_provider]['request_token_secret'] );
				
				// Make sure nothing went wrong.
				if ( 200 == $Twitter->http_code ) {
					$oauth_token		= $access_token['oauth_token'];
					$oauth_token_secret	= $access_token['oauth_token_secret'];
				
					/*
						What's next?  Now that we have an Access Token and Secret, we can make an API call.
						Any API call that requires OAuth authentiation will need the info we have now - (Consumer Key,
						Consumer Secret, Access Token, and Access Token secret).
				
						You should store the Access Token and Secret in a database, or if you must, a Cookie in the user's browser.
						Never expose your Consumer Secret.  
						It should stay on your server, avoid storing it in code viewable to the user.
				
						Make the /user/info API call to get some basic information about the user
					*/
					$_SESSION[$this->_provider]						= array();
					$_SESSION[$this->_provider]['access_token']		= $access_token['oauth_token'];
					$_SESSION[$this->_provider]['access_secret']	= $access_token['oauth_token_secret'];
					 
					setcookie( 'tumblr_access_token', $access_token['oauth_token'], 2147483647, '/' );
					setcookie( 'tumblr_access_secret', $access_token['oauth_token_secret'], 2147483647, '/' );

					$data = array(
						'access_token'	=> $access_token['oauth_token'],
						'access_secret'	=> $access_token['oauth_token_secret'] 	
					);
				
					return $this->handleAccessToken( $data );
				} else {
					header('Location: '.AUTHANY_BASEURL.'/login');
				}				
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
		if( isset( $data['access_token'] ) AND isset( $data['access_secret'] ) ) {
			$Twitter = new Twitter(
				$this->_envConfig->key,
				$this->_envConfig->secret,
				$data['access_token'],
				$data['access_secret']
			);
						
			$user = $this->getUserDetails( $Twitter );
			//$profile = $this->getUserProfile( $Twitter );
                        $user['network']='twitter';
						
			if( isset( $user['screen_name'] ) ) {
				if( !isset( $user['username'] ) ) {
					$user['username'] = $user['screen_name'];	
				}
				
				$this->saveAccessToken( $data );				
				return $this->handleLocalLogin( $user );
			} else {
				trigger_error( $this->_provider.' login error:  '.var_export( $user, true ) );
				$this->deleteAccessToken();
				
				return $user;	
			}
		} else {
			$this->deleteAccessToken();
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
	
			$response = $this->ObjectToArray( json_decode( $this->curl_post_url( $this->_apiConfig->oauthEndpoint, $params ), true ) );

			return $response;
		}
	}

	/**
	 * Save Access Token
	 *
	 * @param	array	$data
	 * @return	array
	*/
	public function saveAccessToken( $data = array() )
	{
		if( !empty( $data ) ) {
			$expires = ( isset( $data['expires_in'] ) ) ? ( time() + $data['expires_in'] ) : $this->cookieTimeout;
			$_SESSION[$this->_provider] = $data;
	
			$result		= array();
			$result[]	= setrawcookie( $this->_provider.'_access_token', trim( $data['access_token'] ), $expires, '/' );
			$result[]	= setrawcookie( $this->_provider.'_access_secret', trim( $data['access_secret'] ), $expires, '/' );
			
			return $result;
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
		$result[]	= setcookie( $this->_provider.'_access_secret', '', time() - 3600, '/' );
		$result[]	= setcookie( $this->_provider.'_access_token', '', time() - 3600, '/' );
		
		return $result;
	}	
	 
	/**
	 * Get User Details
	 * 
	 * @param	object	$config
	 * @return	array
	*/	
	public function getUserDetails( Twitter $config )
	{			
		// get user info
		$userInfo = $config->get('account/verify_credentials');
		
		if( strlen( $userInfo->screen_name ) ) {
			// login			
			return $this->objectToArray( $userInfo );
		} else {
			return array();
		}
	}
	public function getUserProfile( Twitter $config )
	{			
		// get user info
		$userInfo = $config->get('users/show');
		
		if( strlen( $userInfo->screen_name ) ) {
			// login			
			return $this->objectToArray( $userInfo );
		} else {
			return array();
		}
	}

	public function handleGet()
	{
		// START:	Error Handling
		$error = array();
		$possibleErrors = array(
			'error',
			'error_code',
			'error_description',
			'error_reason'
		);
		
		foreach( $possibleErrors AS $key => $value ) {
			if( isset( $_GET[$value] ) ) {
				$error[$value] = '<strong>'.$value.':</strong>  '.$_GET[$value];
			}
		}
		// END:		Error Handling
		
		if( empty( $error ) ) {
			return $this->handleAuth( $this->getProviderConfig() );			
		}
	}	
}