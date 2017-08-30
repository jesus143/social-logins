<?php
/**
 * AuthAny
 * Global Config
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Thursday, June 19, 2014, 12:01 AM GMT+1
 * @modified    $Date: 2014-06-21 22:39:00 +0200 (Sat, 21 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: AuthAny.php 3 2014-06-21 20:39:00Z dev@cloneui.com $
 *
 * @category    Global Config
 * @package     AuthAny
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