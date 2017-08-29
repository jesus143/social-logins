<?php
/**
 * Social Share Locker
 * Session
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @link        http://suite.social
 * @license     Commercial
 * 
 * @link		https://developer.linkedin.com/documents/authentication
 * @link		https://developer.linkedin.com/documents/making-api-call-oauth-token
 *
 * @category    Classes
 * @package     Social Share Locker
*/

class AuthAny_Session
{
	public function setup()
	{
		// until the end of time...
		// @link	http://en.wikipedia.org/wiki/Year_2038_problem
		if( !defined( 'AUTHANY_COOKIE_TIMEOUT' ) ) {
			define( 'AUTHANY_COOKIE_TIMEOUT', 2147483647 );
		}
		
		if( !defined( 'AUTHANY_GARBAGE_TIMEOUT' ) ) {
			define( 'AUTHANY_GARBAGE_TIMEOUT', AUTHANY_COOKIE_TIMEOUT );
		}
				
		// START:	$_SESSION logic
		if( !isset( $_SESSION ) ) {		
			ini_set( 'session.gc_maxlifetime', AUTHANY_GARBAGE_TIMEOUT );
			session_set_cookie_params( AUTHANY_COOKIE_TIMEOUT, '/' );
		
			// setting session dir
			if( isset( $_SERVER['HTTP_HOST'] ) ) {
				$sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
			} else {
				$sessdir = '/tmp/AuthAny';
			}
		
			// if session dir not exists, create directory
			if ( !is_dir( $sessdir ) ) {
				@mkdir( $sessdir, 0777, true );
			}
		
			// if directory exists, then set session.savepath otherwise let it go as is
			if( is_dir( $sessdir ) ) {
				ini_set( 'session.save_path', $sessdir );
			}
			
			session_start();
		}
		// END:		$_SESSION logic		
	}	
}