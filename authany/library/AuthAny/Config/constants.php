<?php
/**
 * AuthAny
 * Global Constants
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Thursday, June 19, 2014, 12:04 AM GMT+1
 * @modified    $Date: 2014-06-24 10:19:35 +0200 (Tue, 24 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: constants.php 13 2014-06-24 08:19:35Z dev@cloneui.com $
 *
 * @category    Global Config
 * @package     AuthAny
*/

date_default_timezone_set('UTC');
define( 'AUTHANY_DEBUG', false );
define( 'AUTHANY_BASEURL', 'http://demo.authany.com' );
define( 'AUTHANY_ROOT', dirname( dirname( __FILE__ ) ) );
define( 'AUTHANY_LOGIN_ROOT', '/login/' );
define( 'AUTHANY_LIB_ROOT', dirname( AUTHANY_ROOT ) );
define( 'AUTHANY_PROVIDER_ROOT', AUTHANY_ROOT.'/Provider' );