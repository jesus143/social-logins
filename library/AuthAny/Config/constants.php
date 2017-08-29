<?php
/**
 * Social Share Locker
 * Global Constants
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @link        http://suite.social
 * @license     Commercial
 *
 * @category    Global Config
 * @package     Social Share Locker
*/

date_default_timezone_set('UTC');
define( 'AUTHANY_DEBUG', false );
define( 'AUTHANY_BASEURL', 'http://suite.social/coder/share/connect' );
define( 'AUTHANY_ROOT', dirname( dirname( __FILE__ ) ) );
define( 'AUTHANY_LOGIN_ROOT', '/login/' );
define( 'AUTHANY_LIB_ROOT', dirname( AUTHANY_ROOT ) );
define( 'AUTHANY_PROVIDER_ROOT', AUTHANY_ROOT.'/Provider' );