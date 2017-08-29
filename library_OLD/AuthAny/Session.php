<?php
/**
 * AuthAny
 * AuthAny\Session
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Thursday, June 19, 2014, 12:26 AM GMT+1
 * @modified    $Date: 2014-06-21 22:39:00 +0200 (Sat, 21 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: Session.php 3 2014-06-21 20:39:00Z dev@cloneui.com $
 *
 * @category    Classes
 * @package     AuthAny
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