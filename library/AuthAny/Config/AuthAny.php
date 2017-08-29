<?php
/**
 * Social Share Locker
 * Global Config
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @link        http://suite.social
 * @license     Commercial
 *
 * @category    Global Config
 * @package     Social Share Locker
*/

require_once('constants.php');
require_once('Zend/Loader/Autoloader.php');

$Zend_Loader_Autoloader = Zend_Loader_Autoloader::getInstance();
$Zend_Loader_Autoloader->setFallbackAutoloader( true );

require_once( AUTHANY_ROOT.'/Session.php' );
require_once( dirname( AUTHANY_ROOT ).'/AuthAny.php' );
require_once( dirname( AUTHANY_ROOT ).'/Zdm.php' );

Zdm::start();
$AuthAny = new AuthAny;
$AuthAny_Session = new AuthAny_Session();
$AuthAny_Session->setup();