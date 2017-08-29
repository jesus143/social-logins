<?php
/**
 * AuthAny
 * Yahoo! Authentication
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
 * @since  	    Monday, June 23, 2014, 03:27 AM GMT+1
 * @modified    $Date: 2014-06-24 10:19:35 +0200 (Tue, 24 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Yahoo.php 13 2014-06-24 08:19:35Z dev@cloneui.com $
 *
 * @category    External Authenticaton
 * @package     AuthAny
*/

require_once('OAuth.php');
require_once('Yahoo/YahooOAuthApplication.class.php');

class AuthAny_Provider_Yahoo extends AuthAny
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
	 * Handle All Authentication
	 * 
	 * @return	void
	*/	
	public function handleAuth()
	{						
		$app = new YahooOAuthApplication( 
			$this->_envConfig->consumerKey, 
			$this->_envConfig->consumerSecret, 
			$this->_envConfig->appId 
		);
		
		if( strlen( @$_COOKIE['yos-social-rt'] ) ) {
			// request token exists
			$request_token = $this->oauth_get_cookie('yos-social-rt');
			$app->token = $app->getAccessToken($request_token, $_GET['oauth_verifier']);
			$app->token->expires = 2147483647;
			$this->oauth_set_cookie('yos-social-at', $app->token, $app->token->expires_in);
		
			$token = $app->token;
			if( !empty( $token ) AND isset( $token->yahoo_guid ) ) {
				$profile = $this->objectToArray( $app->getProfile()->profile );
		
				if( !empty( $profile ) ) {
					if( !isset( $profile['username'] ) ) {
						$profile['username'] = $profile['nickname'];
					}

					if( isset( $profile['emails'] ) ) {					
						if( isset( $profile['emails'][1] ) ) {
							foreach( $profile['emails'] AS $key => $value ) {
								if( isset( $value['primary'] ) ) {
									$profile['email'] = $value['handle'];
								}
							}
						} else {
							$profile['email'] = $profile['emails']['handle'];
						}	
					}
	
					return $this->handleLocalLogin( $profile );
				} else {
					// error
					$this->oauth_unset_cookie( 'yos-social-at' );
					$this->oauth_unset_cookie( 'yos-social-rt' );
					
					header( 'Location: '.AUTHANY_BASEURL );
				}
			} else {
				// error
				$this->oauth_unset_cookie( 'yos-social-at' );
				$this->oauth_unset_cookie( 'yos-social-rt' );
				
				$callback_params = array();
				$callback = sprintf("%s://%s%s?%s", ($_SERVER["HTTPS"] == 'on') ? 'https' : 'http', $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"], http_build_query($callback_params));
				$request_token = $app->getRequestToken($callback);
				 
				$this->oauth_set_cookie('yos-social-rt', $request_token, $request_token->expires_in);
				 
				$auth_url = $app->getAuthorizationUrl( $request_token );
				header( 'Location: '.$auth_url );
			}
		} else {
			$token = $this->oauth_get_cookie('yos-social-at');
			if( !empty( $token ) AND isset( $token->yahoo_guid ) ) {
				// set the token in the SDK
				$app->token = $token;
				 
				$profile = $this->objectToArray( $app->getProfile()->profile );
		
				if( !empty( $profile ) ) {
					if( !isset( $profile['username'] ) ) {
						$profile['username'] = $profile['nickname'];
					}
					
					if( isset( $profile['emails'] ) ) {
						if( isset( $profile['emails'][1] ) ) {
							foreach( $profile['emails'] AS $key => $value ) {
								if( isset( $value['primary'] ) ) {
									$profile['email'] = $value['handle'];
								}
							}							
						} else {
							$profile['email'] = $profile['emails']['handle'];	
						}
					}					
										
					return $this->handleLocalLogin( $profile );
				} else {
					// error
					$this->oauth_unset_cookie( 'yos-social-at' );
					$this->oauth_unset_cookie( 'yos-social-rt' );
					
					header( 'Location: '.AUTHANY_BASEURL );
				}
			} else {
				$callback_params 	= array();
				$callback			= sprintf('%s://%s%s?%s', ( @$_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'], http_build_query( $callback_params ) );
				$request_token		= $app->getRequestToken( $callback );
				 
				$this->oauth_set_cookie('yos-social-rt', $request_token, $request_token->expires_in);
				 
				$auth_url = $app->getAuthorizationUrl( $request_token );
				header( 'Location: '.$auth_url );
			}
		}		
	}
	
	public function oauth_get_cookie( $name )
	{
		return unserialize( base64_decode( @$_COOKIE[$name] ) );
	}
	
	public function oauth_set_cookie( $name, $data, $expires = 20736000, $path = '/' )
	{
		return setcookie( $name, base64_encode( serialize( $data ) ), time() + $expires, $path );
	}
	
	public function oauth_unset_cookie( $name, $path = '/' )
	{
		return setcookie( $name, '', time() - 3600, $path );
	}	
}