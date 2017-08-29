<?php
ob_start();
session_start();

$client_id = 'id3';
$page_name = 'client.php';
//echo __DIR__ . '------------' . basename($_SERVER['PHP_SELF']);

$_SESSION['share_page'] = $page_name;
$_SESSION['client_id'] = $client_id;

$fileContent = '<?php 
session_start();
if (isset($_GET["id"]) && $_GET["id"] > 0) {
        require_once "sharelock.class.php";
        $sharelock = new sharelock();
        $share_id = $_GET["id"];
        $ip = 1;
        $client_id = $_GET["cid"];
        $total_visits = $sharelock->visitor($share_id, $ip, $client_id);
        header("Refresh:0; url=' . $page_name . '");
    }

    ?>';

if (isset($_SESSION['share_link'])) {
        file_put_contents(__DIR__ . "/" . $page_name, $fileContent);
    if (isset($_GET['id']) && $_GET['id'] > 0) {
        header("Refresh:0; url=index.php");
    }
}
if (!isset($_SESSION['share_link'])) {
    if (isset($_GET['id']) && $_GET['id'] > 0) {
        require_once "sharelock.class.php";
        $sharelock = new sharelock();
        $share_id = $_GET['id'];
        $ip = 1;
        //$client_id = 'id3'; /// CLIENT ID SHOULD NOT GO HERE!
        $total_visits = $sharelock->visitor($share_id, $ip, $client_id);
        header("Refresh:0; url=index.php");
    }
}

define('BASEDIR', dirname(__FILE__));
set_include_path(
        BASEDIR . '/library/' . PATH_SEPARATOR .
        get_include_path()
);

error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('log_errors', true);

// time zone
date_default_timezone_set('UTC');

// error log
ini_set('error_log', BASEDIR . '/logs/php/' . date('m-d-Y') . '.log');

$error = array();

require_once('AuthAny/Config/AuthAny.php');
$AuthAny->handleRequest();

// START:	AJAX Hanlder; shopping cart
if (!empty($_POST)) {
    if (isset($_POST['AUTHANY_AJAX'])) {
        $method = $_POST['method'];
        $json = array();

        header('Content-Type: application/json; charset=UTF-8');

        switch ($method) {
            default:
                $json['status'] = 'ERROR';
                $json['error'] = 'UNHANDLED_EXCEPTION';
        }

        exit(json_encode($json));
    }
}
// END:		AJAX Hanlder; shopping cart	
// START:	Logout
if (preg_match('/logout/', $_SERVER['REQUEST_URI'])) {
    session_unset();
    session_destroy();

    setcookie('google_access_token', '', time() - 3600, '/');
    setcookie('yos-social-at', '', time() - 3600, '/');
    setcookie('yos-social-rt', '', time() - 3600, '/');

    header('Location: ' . AUTHANY_BASEURL);
}
// END:		Logout
// START:	Error Handling
$possibleErrors = array(
    'error',
    'error_code',
    'error_description',
    'error_reason'
);

if (!empty($_GET)) {
    foreach ($possibleErrors AS $key => $value) {
        if (isset($_GET[$value])) {
            $error[$value] = '<strong>' . $value . ':</strong>  ' . $_GET[$value];
        }
    }
}
// END:		Error Handling
// START:	User Logic
if (@$_SESSION['logged_in'] == 1) {
    $networkDisplayName = ( isset($_SESSION['network']['display_name']) ) ? $_SESSION['network']['display_name'] : ucfirst($_SESSION['network']['name']);
    $networkDisplayName = strtolower($networkDisplayName);
    $networkIcon = ( isset($_SESSION['network']['short_id']) ) ? $_SESSION['network']['short_id'] : strtolower($_SESSION['network']['name']);
    $userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['name']) ) ? $_SESSION[$_SESSION['network']['name']]['name'] : $_SESSION[$_SESSION['network']['name']]['username'];
    $userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['avatar']) ) ? $_SESSION[$_SESSION['network']['name']]['avatar'] . "?width=500&height=500" : "";

    if ($networkDisplayName == 'googleplus') {
        $userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['displayName']) ) ? $_SESSION[$_SESSION['network']['name']]['displayName'] : $_SESSION[$_SESSION['network']['name']]['username'];
        $userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['image']['url']) ) ? $_SESSION[$_SESSION['network']['name']]['image']['url'] : "";
        $userDisplayPicture = substr($userDisplayPicture, 0, strpos($userDisplayPicture, "?"));
        $userDisplayPicture = $userDisplayPicture . "?sz=450";
    }
    if ($networkDisplayName == 'facebook') {
        $userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['name']) ) ? $_SESSION[$_SESSION['network']['name']]['name'] : $_SESSION[$_SESSION['network']['name']]['username'];
        $userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['avatar']) ) ? $_SESSION[$_SESSION['network']['name']]['avatar'] . "?width=500&height=500" : "";
    }
    if ($networkDisplayName == 'twitter') {
        $userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['name']) ) ? $_SESSION[$_SESSION['network']['name']]['name'] : $_SESSION[$_SESSION['network']['name']]['username'];
        $userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['profile_image_url']) ) ? $_SESSION[$_SESSION['network']['name']]['profile_image_url'] : "";
        $userDisplayPicture = str_replace('_normal', '', $userDisplayPicture);
    }
    if ($networkDisplayName == 'linkedin') {
        $userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['formattedName']) ) ? $_SESSION[$_SESSION['network']['name']]['formattedName'] : "";
        $userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['profile_picture']) ) ? $_SESSION[$_SESSION['network']['name']]['profile_picture'] : "";
    }
    if ($networkDisplayName == 'youtube') {
        $userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['displayName']) ) ? $_SESSION[$_SESSION['network']['name']]['displayName'] : "";
        $userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['image']['url']) ) ? $_SESSION[$_SESSION['network']['name']]['image']['url'] : "";
        $userDisplayPicture = substr($userDisplayPicture, 0, strpos($userDisplayPicture, "?"));
        $userDisplayPicture = $userDisplayPicture . "?sz=450";
    }

    //$userDisplayName = ( isset($_SESSION[$_SESSION['network']['name']]['name']) ) ? $_SESSION[$_SESSION['network']['name']]['name'] : $_SESSION[$_SESSION['network']['name']]['username'];
    //$userDisplayPicture = ( isset($_SESSION[$_SESSION['network']['name']]['avatar']) ) ? $_SESSION[$_SESSION['network']['name']]['avatar'] . "?width=500&height=500" : "";

    switch ($networkIcon) {
        case 'disqus':
        case 'gmail':
            $displayFont = 'zocial-';

            break;

        case 'google':
        case 'google-plus':
        case 'soundcloud':
        case 'twitter':
            $displayFont = 'fa fa-';

            break;

        default:
            $displayFont = 'socicon socicon-';
    }

    switch ($networkDisplayName) {
        case 'Gmail':
            $social_id = 'gmail_id';

            break;
        case 'Twitter':
            $social_id = 'twitter_id';

            break;
        case 'Facebook':
            $social_id = 'f_id';

            break;
        case 'Youtube':
            $social_id = 'y_id';

            break;

        default:
            $social_id = 'f_id';
    }
}
// END:		User Logic
///////////////////////// SHARELOCK HEADER - START //////
require_once('library/AuthAny/Session.php');
$AuthAny_Session = new AuthAny_Session();
$AuthAny_Session->setup();
require_once "sharelock.class.php";
$sharelock = new sharelock();
//define array for sharelock
/* -----------------------------------Array details----------------------------------- */

# "id"=>"1" - sets the unique sharelock id.
# "visitor_target"=>"5" - sets total no of targeted visitors.
# "url"=>"https://suite.social/purchase" - sets target url after total visitor count (leave empty if using html content).
# "ip"=>"1" - Check ip detection set to 1 (for yes) or 0 (for no)

/* -----------------------------------Array details end----------------------------------- */
$share_id = '';
if (isset($_SESSION['sid'])) {
    $share_id = $_SESSION['sid'];
}
$data = array(
    '0' => array("id" => $share_id, "visitor_target" => "15", "url" => "http://suite.social/", "theme" => "", "ip" => "1", "f_id" => "101178882918948788797",
        "gmail_id" => "", "y_id" => "", "twitter_id" => "", "client_id" => $client_id), // CLIENT ID HERE
);


//current url of file
$uri = $_SERVER['REQUEST_URI'];
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

///////////////////////// SHARELOCK HEADER - END //////
if (isset($_SESSION['share_link']) && $_SESSION['share_link'] != '') {
    $current_url = $_SESSION['share_link'];
}
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";
//print_r( $_SESSION );
//print_r( $data );
?>
<!DOCTYPE html>
<html lang="en">
	<head>

    <!-- Title -->
    <title>Social Purchase</title>

    <!-- Meta Data -->
    <meta name="title" content="YOUR OFFER HERE">
    <meta name="description" content="Your product or promotion description">
    <meta name="keywords" content="Blog Social Puchase, Facebook Social Puchase, Google+ Social Puchase, Instagram Social Puchase, Linkedin Social Puchase, Pinterest Social Puchase, Reddit Social Puchase, Social Media Promotion, StumbleUpon Social Puchase, Tumblr Social Puchase, Twitter Social Puchase, Vk Social Puchase, WhatsApp Social Puchase, Wordpress Social Puchase, XING Social Puchase">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="revisit-after" content="14 days">
    <meta name="author" content="<?php echo $current_url; ?>">	
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />	
		
	<!-- Google Plus -->
	<!-- Update your html tag to include the itemscope and itemtype attributes. -->
	<!-- html itemscope itemtype="//schema.org/{CONTENT_TYPE}" -->
	<meta itemprop="name" content="YOUR OFFER HERE">
	<meta itemprop="description" content="Your product or promotion description">
	
	<!-- Twitter -->
	<meta name="twitter:card" content="YOUR OFFER HERE">
	<meta name="twitter:title" content="YOUR OFFER HERE">
	<meta name="twitter:description" content="Your product or promotion description">
	
	<!-- Open Graph General (Facebook & Pinterest) -->
	<meta property="og:url" content="<?php echo $current_url; ?>">
	<meta property="og:title" content="YOUR OFFER HERE">
	<meta property="og:description" content="Your product or promotion description">
	<meta property="og:type" content="product">

	<!-- Open Graph Article (Facebook & Pinterest) -->
	<meta property="article:section" content="Marketing">
	<meta property="article:tag" content="Marketing">		
	
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />	
	<meta name="HandheldFriendly" content="true" />	

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="//suite.social/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="72x72" href="//suite.social/images/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="//suite.social/images/favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="144x144" href="//suite.social/images/favicon/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="256x256" href="//suite.social/images/favicon/apple-touch-icon-256x256.png" />
	
	<!-- Chrome for Android web app tags -->
	<meta name="mobile-web-app-capable" content="yes" />
	<link rel="shortcut icon" sizes="256x256" href="//suite.social/images/favicon/apple-touch-icon-256x256.png" />
	
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//suite.social/assets/font-awesome/css/font-awesome.min.css">
	<!--<script src="//use.fontawesome.com/9b98e0b658.js"></script>-->
	
	<!--<link href="<?php echo AUTHANY_BASEURL; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
	[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
    <link rel="stylesheet" href="//suite.social/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="//suite.social/assets/css/normalize.css">
    <link rel="stylesheet" href="//suite.social/assets/css/main.css">
    <link rel="stylesheet" href="//suite.social/assets/css/responsive.css">
	<link rel="stylesheet" href="//suite.social/assets/css/social-buttons.css">
	
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Prefixing JS -->
    <script src="//suite.social/assets/javascript/modernizr-2.8.3.min.js"></script>
    <script src="//suite.social/assets/javascript/html5-3.6-respond-1.4.2.min.js"></script>
	
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/phpjs.min.js?v=1.0"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jstorage.min.js"></script>		
		<script type="text/javascript">	
            var BASEURL = '<?php echo AUTHANY_BASEURL; ?>';
            var DEFAULT_PRELOADER_IMAGE = BASEURL + '/images/preloader/default.gif';
            var ORDER_OPTIONS = $.jStorage.get('ORDER_OPTIONS');
            var JSTORAGE_TTL = 622080000;
            if (is_null(ORDER_OPTIONS)) {
                ORDER_OPTIONS = {};
                $.jStorage.set('ORDER_OPTIONS', ORDER_OPTIONS);
                $.jStorage.setTTL('ORDER_OPTIONS', JSTORAGE_TTL);
            }
        </script>

		<!--<link href="<?php echo AUTHANY_BASEURL; ?>/assets/css/core.css?<?php echo rand(); ?>" rel="stylesheet" type="text/css">-->
		<link href="<?php echo AUTHANY_BASEURL; ?>/assets/css/bootstrap.callouts.css" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/assets/css/zocial.css" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/assets/css/socicon.css" rel="stylesheet" type="text/css">
		<!--<link href="<?php echo AUTHANY_BASEURL; ?>/assets/css/jquery-ui/themes/jface/jquery-ui.css" rel="stylesheet" type="text/css" />-->		
		
		<style type="text/css">
			@import url('<?php echo AUTHANY_BASEURL; ?>/assets/fonts/welove/zocial/welove.css');
			
			/* zocial */
			[class*="zocial-"]:before {
			  font-family: 'zocial', sans-serif;
			}			
		
        html {
        	position: relative;
        	min-height: 100%;
        }	

        body {
        	margin: 0 0 30px; /* Height of the footer */
        	overflow-x: hidden;
        }	

        #footer {
        	position: absolute;
        	bottom: 0;
        	width: 100%;
        	height: 30px;
            color: #fff;
        	text-align: center;
            overflow: hidden; 	
        }

        #counter {
        	width: 50%;
        	color: #fff;
        	font-size: 72px;
        	font-weight: bold;
        	padding:5px; 
        	border:5px solid #fff;
        	border-radius: 15px;
        	background: #111111;
        	background: -moz-linear-gradient(top,  #111111 0%, #2c2c2c 50%, #2c2c2c 50%, #000000 50%, #111111 100%);
        	background: -webkit-linear-gradient(top,  #111111 0%,#2c2c2c 50%,#2c2c2c 50%,#000000 50%,#111111 100%);
        	background: linear-gradient(to bottom,  #111111 0%,#2c2c2c 50%,#2c2c2c 50%,#000000 50%,#111111 100%);
        	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#111111', endColorstr='#111111',GradientType=0 );
        }

        div.select-items {
            height: 300px;
        }

        input {
            line-height: normal;
            color: #1f1f1f;
            font-size: 30px;
        }

            .wrapper div {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            #one { 
                margin: 10px;
            }
            #two { 
                margin: 10px;
            }

            @media screen and (min-width: 600px) {
                .wrapper {
                    height: auto; overflow: hidden; // clearing 
                }
                #one { width: 45%; float: left; }
                #two { margin-left: 50%; }
            }

            h3 {
                margin-top: 24px; 
            }

            input {
                font-size: 24px;
            }


            .modal-content {
                background-color: #1f1f1f;
                color: #fff;
            }

            .modal-header {
                border-bottom: 1px solid #616161;
            }

            .modal-footer {
                border-top: 1px solid #616161;
            }

            .close {
                color: #ccc;
            }					
			
.panel-title {
padding-top: 10px;
}

.panel-heading {
    background-color: #484848;
    background-image: -moz-linear-gradient(top, #4b4b4b, #434343);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#4b4b4b), to(#434343));
    background-image: -webkit-linear-gradient(top, #4b4b4b, #434343);
    background-image: -o-linear-gradient(top, #4b4b4b, #434343);
    background-image: linear-gradient(to bottom, #4b4b4b, #434343);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff4b4b4b', endColorstr='#ff434343', GradientType=0);
    box-shadow: 0 1px 0 0 #000000, 0 5px 4px -4px #191919;
    -moz-box-shadow: 0 1px 0 0 #000000, 0 5px 4px -4px #191919;
    -webkit-box-shadow: 0 1px 0 0 #000000, 0 5px 4px -4px #191919;
    border-bottom: 1px solid #616161;
    text-shadow: 0 1px 0 none;
    height: 35px;
    line-height: 35px;
    position: relative;
    padding: 0 15px 0 15px;
}

.panel-success {
    border-color: #616161;
    margin: 20px;
    box-shadow: 0 1px 0 0 #000000, 0 5px 4px -4px #191919;
    -moz-box-shadow: 0 1px 0 0 #000000, 0 5px 4px -4px #191919;
    -webkit-box-shadow: 0 1px 0 0 #000000, 0 5px 4px -4px #191919;
}

.panel-success>.panel-heading {
    color: #fff;
    border-color: #616161;
}			
			
		</style>
	</head>
	<body>
		<!-- START:		blockUI -->
			<div style="display: block" class="blockUI"></div>
			<div style="display: block; z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: fixed;" class="blockUI blockOverlay"></div>
			<div style="display: block; z-index: 1011; position: fixed; padding: 0px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; cursor: wait;" class="blockUI blockMsg blockPage"><img src="<?php echo AUTHANY_BASEURL; ?>/images/preloader/default.gif"></div>		
		<!-- END:		blockUI -->	
		<div class="wrapper">
			<div class="box">               
          
            	<!-- main right col -->
            	<div id="main">
              
					<div class="padding">                
                        	<!-- content -->                      
                      		<div class="row">                          
                          		<!-- main col right -->
                          		<div class="col">                     
                                  		<div id="primaryElement">
                                  			<?php if( @$_SESSION['logged_in'] != 1 ): ?>																																
											<!-- START OF LOGIN -->
	                                  			<div>
												
<img src="//suite.social/images/banner/reseller.jpg" width="100%" alt="Banner">
<main id="dashboard">
    <div class="container">
        
        <div class="row">

                <div align="center" class="page-title">
				<img style="margin-top: 30px" src="//suite.social/images/profile/guy.jpg" width="80px" alt="Profile Image"> 
                    <h4 style="color:#8ec657"><strong>Earn £5,000+ monthly selling Social Media Management using our platform!</strong></h4>
					<h4><i>"Hi, I'm Mr Grower, your Virtual Social Media Manager. I will help you resell the Social Suite Platform to local businesses & professionals so you can grow your monthly income!"</i></h4>
					<a href="#Login" data-toggle="modal" class="btn btn-success"> LOGIN NOW! <i class="fa fa-sign-in"></i></a>
					<a href="#info" data-toggle="collapse" class="btn btn-gray"> MORE INFO <i class="fa fa-question-circle"></i></a>					
                </div>

        </div>
		
<div id="info" class="collapse">

<br>
<div class="panel-group" id="accordion">
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        What is Social Suite platform?</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
	   <p>Social Suite is an all-in-one Social Media Management, Marketing & Monitoring platform that helps business, brands & bloggers manage their social media accounts, market their products & services, monitor online discussions, keep in touch with their customers and more.</p>	   
	   <p>Social Suite includes hundreds of marketing tools, apps and services to ATTRACT, ENGAGE, CONVERT, RETAIN new customers and get a REPORT of the results. If clients are busy running their business, serving customers and want to relieve the stress or confusion, they can use this platform do all the hard work in GROWING their following, traffic & sales 24-7, 365 days a year, saving 70% of their time, money & resources. It's basically a one-stop shop for all your social media needs.</p>										
	  </div>
    </div>
  </div>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        How do I make money?</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse in">
      <div class="panel-body">
	  By selling campaign codes to clients (these are like coupon codes), a client simply purchase one from you that lasts up to 2 weeks and enter it on the platform to upgrade to Social Suite Pro so they can use over 200 marketing tools for their business.
	  </div>
    </div>
  </div>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
        Do I have to pay anything upfront?</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
	  No. You can claim up to 5 free campaign codes to sell at any price you wish (£100 - £200 each). Just login and share the opportunity with friends on social networks, email or WhatsApp etc to claim your free campaign codes. Once you sell all campaign codes, you need to purchase more at a discounted price so you can resell them to more clients.
	  </div>
    </div>
  </div>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
        Do you take a commission from payments?</a>
      </h4>
    </div>
    <div id="collapse4" class="panel-collapse collapse">
      <div class="panel-body">
	  No. You can use your own PayPal account etc to recieve payments from clients on your custom landing page you create.
	  </div>
    </div>
  </div>  
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
        Where do I find clients to sell to?</a>
      </h4>
    </div>
    <div id="collapse5" class="panel-collapse collapse">
      <div class="panel-body">
	  We have a special tool called <a href="//suite.social/search" target="_blank">Social Search</a> which allows you to find millions of local businesses or professionals on Facebook with their full contact info including email, telephone and facebook messenger link so you can contact them directly to sell your campaign codes. We even have some prepared marketing templates and images you can send.
	  </div>
    </div>
  </div>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
        Do I need to install any software?</a>
      </h4>
    </div>
    <div id="collapse6" class="panel-collapse collapse">
      <div class="panel-body">
	  No, you just create your own custom landing page to send to clients and they can pay you directly and use our Platform online.
	  </div>
    </div>
  </div>  
</div>

</div>

<br><br>
<p align="center"><i>Alex, Casey, Gene, Jamie, Max, Robin, Sam and 82483 other people are making $5,000 per month with our platform...</i></p>
<div class="table-responsive">
<table class="table grid">
<tr align="center">
  <td><img src="https://suite.social/images/profile/female1.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/male1.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/female2.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/male3.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/female3.jpg" width="60px" alt="Profile"></td>  
  <td><img src="https://suite.social/images/profile/male3.jpg" width="60px" alt="Profile"></td>  
  <td><img src="https://suite.social/images/profile/female4.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/male4.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/female5.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/male5.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/female6.jpg" width="60px" alt="Profile"></td>
  <td><img src="https://suite.social/images/profile/male6.jpg" width="60px" alt="Profile"></td>
</tr>
</table>
</div>
		
			<h1 align="center">HOW IT WORKS</h1>

        <div class="row">

            <div class="col-sm-4 text-center">
                <div class="select-options">
                    <div class="select-items">
                        <i id="suite" class="fa fa-money fa-5x"></i>
                        <h3>1. Free campaign codes</h3>
                        <p>Connect and share to get free campaign codes to resell. Clients enter the campaign code on our platform to upgrade their account to Pro for 2 weeks.</p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN NOW <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
			
            <div class="col-sm-4 text-center">
                <div class="select-options">
                    <div class="select-items">
                        <i id="suite" class="fa fa-credit-card fa-5x"></i>
                        <h3>2. Create custom  page</h3>
                        <p>Once you get your campaign codes, use our generator to create your payment link (e.g. PayPal) and landing page for clients to visit and pay you directly.</p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN NOW <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>			
			
            <div class="col-sm-4 text-center">
                <div class="select-options">
                    <div class="select-items">
                        <i id="suite" class="fa fa-users fa-5x"></i>
                        <h3>3. Sell to clients</h3>
                        <p>Use our Social Search tool to find millions of local businesses with their email, telephone and facebook messenger link so you can contact them directly.</p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN NOW <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>	

			<h1 align="center">TOP BENEFITS</h1>	
			<br>		

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Relax.jpg" alt="Relax" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>1. Work Less<br></h3>
                        <p>If you want to just work few hours per day and spend rest of your time with family, friends or doing the stuff you enjoy then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div> 

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Earn.jpg" alt="Earn" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>2. Earn More<br></h3>
                        <p>If you want to get paid monthly by local businesses so they can use our platform to manage their social media and grow sales then choose this.</p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>	

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Home.jpg" alt="Home" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>3. Work Anywhere<br></h3>
                        <p>If you want to be sitting on a beach, relaxing in the park or in comfort of your home making monthly income with our platform then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>	

			<h1 align="center">TOP BUSINESSES & PROFESSIONALS</h1>	
			<br>

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Salon.jpg" alt="Beauty" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Beauty<br></h3>
                        <p>If you want to earn £5,000+ per month helping local beauty businesses (e.g. Salons) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div> 
			
            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Performer.jpg" alt="Creative" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Creatives<br></h3>
                        <p>If you want to earn £5,000+ per month helping local creative businesses (e.g. Performers) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>	
			
            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Tutor.jpg" alt="Education" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Education<br></h3>
                        <p>If you want to earn £5,000+ per month helping local events or venues (e.g. Tutors) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>			

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Bar.jpg" alt="Venue-Event" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Venues<br></h3>
                        <p>If you want to earn £5,000+ per month helping local venues or events (e.g. Clubs) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>			

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Fitness.jpg" alt="Health" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Health<br></h3>
                        <p>If you want to earn £5,000+ per month helping local health clinics (e.g. Dentists)  with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Restaurant.jpg" alt="Hospitality" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Hospitality<br></h3>
                        <p>If you want to earn £5,000+ per month helping local hospitality businesses (e.g. Restaurants) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div> 

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Estate.jpg" alt="Property" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Property<br></h3>
                        <p>If you want to earn £5,000+ per month helping local property businesses (e.g. Estate Agencies) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Shop.jpg" alt="Retail" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Retail<br></h3>
                        <p>If you want to earn £5,000+ per month helping local retail businesses (e.g. Shops & Stores) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-sm-4 text-center">
			<a href="#Login" data-toggle="modal" ><img width="100%" src="//suite.social/images/business/Builder.jpg" alt="Trades" /></a>
                <div class="select-options">
                    <div class="select-items" style="height: 220px;">
                        <h3>Trades<br></h3>
                        <p>If you want to earn £5,000+ per month helping local trade businesses (e.g. Builders) with their Social Media Management then choose this. </p>
                    </div>
                    <a href="#Login" data-toggle="modal" class="btn btn-gray btn-block"> LOGIN <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>				

        </div>
    </div>
</main>

<!-- ********** Login ********** -->

  <!-- Modal -->
  <div class="modal fade" id="Login" role="dialog">
    <div class="modal-dialog modal-lg">
	
	  <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login with your social account</h4>
        </div>
        <div class="modal-body">
		
		                                  			<div style="display: none;" class="alert alert-danger alert-dismissable" id="loginErrors">
											  			<table style="width: 100%;">
											  				<tbody>
											  					<tr>
											  						<td style="width: 24px;">
											  							<i class="fa fa-exclamation-triangle"></i>
											  						</td>
											  						<td id="loginErrorText"></td>									  			
											  					</tr>
											  				</tbody>
											  			</table>									  	
											  		</div>
											  			<div id="loginFederated" style="margin-top: 20px;">
											  				<?php if( !empty( $error ) ): ?>
											  					<div class="alert alert-danger">
											  						<?php foreach( $error AS $key => $value ): ?>
											  							<div>
											  								<i class="fa fa-exclamation-triangle"></i> <?php echo $value; ?>
											  							</div>
											  						<?php endforeach; ?>
											  					</div>		
											  				<?php endif; ?>
															
															
<div class="container">

    <div class="text-center">
        <div class="col-md-5 col-md-offset-2">

            <div class=" social-buttons">
                <a href="javascipt:void(0);" data-network="facebook" class="zocial btn btn-block btn-lg btn-social btn-facebook">
                    <i class="fa fa-facebook"></i> <center>Login with Facebook</center>
                </a>
				
                <a href="javascipt:void(0);" data-network="twitter" class="zocial btn btn-block btn-lg btn-social btn-twitter">
                    <i class="fa fa-twitter"></i> <center>Login with Twitter</center>
                </a>				

                <a href="javascipt:void(0);" data-network="googleplus" class="zocial btn btn-block btn-lg btn-social btn-google">
                    <i class="fa fa-google-plus"></i> <center>Login with Google+</center>
                </a>
				
                <a href="javascipt:void(0);" data-network="linkedin" class="zocial btn btn-block btn-lg btn-social btn-linkedin">
                    <i class="fa fa-linkedin"></i> <center>Login with Linkedin</center>
                </a>				

                <a href="javascipt:void(0);" data-network="youtube" class="zocial btn btn-block btn-lg btn-social btn-youtube">
                    <i class="fa fa-youtube"></i> <center>Login with YouTube</center>
                </a>				

            </div>
        </div>
    </div>
</div>															
																																					
	  														
											  				<!--<a class="zocial facebook" href="javascipt:void(0);" style="margin: 10px;" data-network="facebook">Facebook</a> 
													    	<a class="zocial secondary" href="javascipt:void(0);" style="margin: 10px;" data-network="github"><i class="fa fa-github"></i> GitHub</a> 
													    	<a class="zocial gmail" href="javascipt:void(0);" style="margin: 10px;" data-network="gmail">Gmail</a> 
													    	<a class="zocial google" href="javascipt:void(0);" style="margin: 10px;" data-network="google">Google</a> 
													    	<a class="zocial googleplus" href="javascipt:void(0);" style="margin: 10px;" data-network="googleplus">Google+</a> 
													    	<a class="zocial instagram" href="javascipt:void(0);" style="margin: 10px;" data-network="instagram">Instagram</a> 
													    	<a class="zocial linkedin" href="javascipt:void(0);" style="margin: 10px;" data-network="linkedin">LinkedIn</a>
													    	<a class="zocial soundcloud" href="javascipt:void(0);" style="margin: 10px;" data-network="soundcloud">SoundCloud</a> 
													    	<a class="zocial tumblr" href="javascipt:void(0);" style="margin: 10px;" data-network="tumblr">Tumblr</a>  
													    	<a class="zocial twitter" href="javascipt:void(0);" style="margin: 10px;" data-network="twitter">Twitter</a> 
													    	<a class="zocial vimeo" href="javascipt:void(0);" style="margin: 10px;" data-network="vimeo">vimeo</a> 
													    	<a class="zocial vk" href="javascipt:void(0);" style="margin: 10px;" data-network="vk">VK</a>
															<a class="zocial windows" href="javascipt:void(0);" style="margin: 10px;" data-network="windowslive">Windows Live</a> 
													    	<a class="zocial yahoo" href="javascipt:void(0);" style="margin: 10px;" data-network="yahoo">Yahoo!</a> 	
													    	<a class="zocial youtube" href="javascipt:void(0);" style="margin: 10px;" data-network="youtube">YouTube</a> 	
													    	<a class="zocial foursquare" href="javascipt:void(0);" style="margin: 10px;" data-network="foursquare">foursquare</a> 
													    	<a class="zocial disqus" href="javascipt:void(0);" style="margin: 10px;" data-network="disqus">Disqus</a>-->
											  			</div>
 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
		  <a href="//suite.social" target="_blank" class="btn btn-gray"> OR TRY PLATFORM</a>
        </div>
      </div>
    </div>
  </div> 										
	                                  
	                                  			</div>
												<!-- END OF LOGIN -->
                                  			<?php else: 
                                  				if(isset($_SESSION['share_link']) && $_SESSION['share_link'] != ''){ ?>
	                                  				<!--<div class="col">
	                                  					<h1 align="center">Share this url to make it happen:</h1>
	                                  					<p align="center">
	                                  						<input style="width: 100%;" type="text" value="<?php echo $_SESSION['share_link']; ?>">
	                                  					</p>
	                                  					<a class="btn btn-primary" href="<?php echo $_SESSION['share_link']; ?>">
	                                  						<i class="fa fa-globe"></i> Share and View Counts
	                                  					</a>
	                                  				</div>-->
													
<!------------------------EDIT THIS TEMPLATE!------------------------>	

<?php
foreach($data as $key=>$value)
{
    //storing data into json file
    $records = array();
    foreach( $_SESSION[$_SESSION['network']['name']] AS $key1 => $value1 )
    {
        $records[$key1]=$value1;
    }
    $jsonData = json_encode($records);
    $v=$value['client_id'].'_'.$value['id'].'.json';
    if(file_exists($v))
    {
        file_put_contents($v,$jsonData) ;
    }
    else
    {
        
        $finsertv = fopen ($v, "w");
        fwrite($finsertv, $jsonData);
    }
//End of storing json

    $total_visits=$sharelock->header($value['id'],$value['ip'],$value['client_id']); //retrieve value of counter
    $pending_counts=$value['visitor_target']-$total_visits; //retrieve value of visitor target
    $filenamev='visitor_'.$value['client_id'].'_'.$value['id'].'.txt';  //saves visitor IP address in txt file
    $fh = fopen($filenamev, 'w');
    fwrite($fh, $total_visits); //checks if counter is less then target counter or not               
    if($value['visitor_target']>$total_visits) //list sharelock if counter is less than target counter
    { 
                				
        /*Shortcodes that list all the sharelock mentioned on the top of page in an array*/

        # echo $value['visitor_target']; - is the visitor target value
        # echo $total_visits; - is the number of visitors
        # echo $pending_counts; - is the total number of visitors              
        # echo $value['url']; - is the current url to share				
        ?>		   
        <main id="dashboard">
            <div class="container">
        	
                <div style="padding: 10px; background-color: #262626; border: #616161 2px solid;" class="row">		        
                        <div align="center" class="page-title">		
        				<a href="//suite.social/purchase" target="_blank"><img src="https://dummyimage.com/1400x500/cccccc/000000.png&text=Replace+your+product+image+here" width="100%" alt="Banner"></a>
 
                                 			<h4>
                                 				<?php if (@$_SESSION['logged_in'] != 1): ?>
                                 					<i class="fa fa-shield"></i> Login
                                 				<?php else: ?>
                                 					<?php
                                 					if (!empty($userDisplayPicture)) {
                                                       ?>
                                                       <img style="margin-top: 30px" src="<?= $userDisplayPicture ?>" width="125px" alt="Profile Image">
                                                       <br/><br/>
                                                       <?php
                                                       }
                                                    ?>
                                                    <span class="<?php echo $displayFont . $networkIcon; ?>"></span> Logged in via <?php echo $networkDisplayName; ?> (<?php echo $userDisplayName; ?>)
                                                <?php endif; ?>
                                            </h4>							
							
							<h3 style="color:#8ec657"><strong>YOUR OFFER HERE</strong></h3>
        					<h4><i>"Your product or promotion description"</i></h4>
        					<a href="#how" data-toggle="collapse" class="btn btn-success"> HOW IT WORKS! <i class="fa fa-question-circle"></i></a>
							<a href="#embed" data-toggle="collapse" class="btn btn-success"> GET EMBED CODE! <i class="fa fa-code"></i></a>
							<a href="<?php echo AUTHANY_BASEURL; ?>/logout" class="btn btn-gray"> LOGOUT <i class="fa fa-sign-out"></i></a>
                            <?php if ($value['id'] == $value[$social_id]) {?>
							<a href="#admin" data-toggle="collapse" class="btn btn-success"> ADMIN <i class="fa fa-user"></i></a>
							<?php }?>
							
<div id="admin" class="collapse">

<br><div class="alert alert-success fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>ADMIN AREA:</strong> Here is a list of all your users who connected and shared.</div>

<p><iframe src="admin-share/index.php?id=<?php echo $value['client_id']; ?>" style="border: 0" width="100%" height="550px" scrolling="auto" frameborder="0">Your browser does not support iFrame</iframe></p>

</div>						
        					
<div id="how" class="collapse">
<?php //echo $jsonData; ?>
<h3>1. Share Link</h3>
<p>Choose a social network, email provider or messaging platform below to share the link with friends, followers or subscribers. You can also send the link manually to your email or SMS contacts (right click and save link).</p>
<h3>2. Check Counter</h3>
<p>The "You will need" counter will decrease the more people visit this link (only one visit is allowed per person).</p>
<h3>3. Claim offer</h3>
<p>Once the "Visitors so far" counter reaches target amount, you can claim the offer immediately.</p>				
</div>	

<div id="embed" class="collapse">

<div align="center">

<br><br><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn.jpg"></a>	

<h4>Copy the embed code for your website, blog or sales page</h4>               	
<textarea rows="3" class="form-control"><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn.jpg"></a></textarea>				
	
<br><br><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn2.jpg"></a>	

<h4>Copy the embed code for your website, blog or sales page</h4>               	
<textarea rows="3" class="form-control"><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn2.jpg"></a></textarea>
	
</div>
				
</div>




        				<h4 style="margin-top: 30px">Visitors so far:</h4>
        				<div id="counter"><?php echo $total_visits;?></div> 				
        				<h4>You will need:</h4>
        				<div id="counter"><?php echo $pending_counts; ?></div> 				
        				<h4>more visitor(s) out of</h4> 
        				<div id="counter"><?php echo $value['visitor_target']; ?></div> 
        				<h4>visitors to claim </h4>
        				<br />				
        				</div>
                </div>
        		
                <br>
        		
                <div class="row">
        		
        		<h1 align="center">Share this url to make it happen:</h1>
        		<i>You must accept cookies in your browser.</i>
        		<p align="center"><input style="width: 100%;" type="text" value="<?php echo $current_url; ?>" /></p>	
        				
        		<br>

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="facebook" class="fa fa-facebook fa-5x"></i>
                                <h3>Share on Facebook</h3>
        						<p>Share the link with friends on Facebook.</p>
                            </div>
                            <a href="https://www.facebook.com/sharer.php?u=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>		
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="google" class="fa fa-google-plus fa-5x"></i>
                                <h3>Share on Google+</h3>
        						<p>Share the link with circles on Google+.</p>
                            </div>
                            <a href="https://plus.google.com/share?url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>			
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="twitter" class="fa fa-twitter fa-5x"></i>
                                <h3>Share on Twitter</h3>
        						<p>Share the link with followers on Twitter.</p>
                            </div>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>		
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="pinterest" class="fa fa-pinterest fa-5x"></i>
                                <h3>Share on Pinterest</h3>
        						<p>Share the link with followers on Pinterest.</p>
                            </div>
                            <a href="https://pinterest.com/pin/create/bookmarklet/?media=<?=$_REQUEST['product_banner']?>&url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>	
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="linkedin" class="fa fa-linkedin fa-5x"></i>
                                <h3>Share on Linkedin</h3>
        						<p>Share the link with contacts on Linkedin.</p>
                            </div>
                            <a href="https://www.linkedin.com/shareArticle?url=<?php echo $current_url; ?>&title=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="tumblr" class="fa fa-tumblr fa-5x"></i>
                                <h3>Share on Tumblr</h3>
        						<p>Share the link with followers on Tumblr.</p>
                            </div>
                            <a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo $current_url; ?>&title=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="reddit" class="fa fa-reddit fa-5x"></i>
                                <h3>Share on Reddit</h3>
        						<p>Share the link with friends on Reddit.</p>
                            </div>
                            <a href="https://reddit.com/submit?url=<?php echo $current_url; ?>&title=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>			

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="livejournal" class="fa fa-pencil fa-5x"></i>
                                <h3>Share on LiveJournal</h3>
        						<p>Share your the link with followers on LiveJournal.</p>
                            </div>
                            <a href="http://www.livejournal.com/update.bml?subject=YOUR OFFER HERE&event=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="blogger" class="fa fa-rss fa-5x"></i>
                                <h3>Share on Blogger</h3>
        						<p>Share the link with followers on blogger.</p>
                            </div>
                            <a href="https://www.blogger.com/blog-this.g?u=<?php echo $current_url; ?>&n=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>	

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="vk" class="fa fa-vk fa-5x"></i>
                                <h3>Share on Vk</h3>
        						<p>Share the link with friends on Vk.com.</p>
                            </div>
                            <a href="http://vk.com/share.php?url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>			

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="flipboard" class="fa fa-clipboard fa-5x"></i>
                                <h3>Share on Flipboard</h3>
        						<p>Share the link with followers on Flipboard.</p>
                            </div>
                            <a href="https://share.flipboard.com/bookmarklet/popout?v=2&title=YOUR OFFER HERE&url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>			
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="xing" class="fa fa-xing fa-5x"></i>
                                <h3>Share on Xing</h3>
        						<p>Share the link with contacts on Xing.</p>
                            </div>
                            <a href="https://www.xing.com/app/user?op=share&url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="stumbleupon" class="fa fa-stumbleupon fa-5x"></i>
                                <h3>Share on StumbleUpon</h3>
        						<p>Share the link with followers on StumbleUpon.</p>
                            </div>
                            <a href="http://www.stumbleupon.com/submit?url=<?php echo $current_url; ?>&title=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>			

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="digg" class="fa fa-digg fa-5x"></i>
                                <h3>Share on Digg</h3>
        						<p>Share the link with followers on Digg.</p>
                            </div>
                            <a href="http://digg.com/submit?url=<?php echo $current_url; ?>&title=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> REFER<i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>	

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="delicious" class="fa fa-delicious fa-5x"></i>
                                <h3>Share on Delicious</h3>
        						<p>Share the link with followers on Delicious.</p>
                            </div>
                            <a href="https://delicious.com/save?v=5&provider={provider}&noui&jump=close&url=<?php echo $current_url; ?>&title=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="whatsapp" class="fa fa-whatsapp fa-5x"></i>
                                <h3>Share on WhatsApp</h3>
        						<p>Share the link with friends on WhatsApp.</p>
                            </div>
                            <a href="whatsapp://send?text=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>							
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="telegram" class="fa fa-telegram fa-5x"></i>
                                <h3>Share on Telegram</h3>
        						<p>Share the link with contacts on Telegram.</p>
                            </div>
                            <a href="https://telegram.me/share/url?url=<?php echo $current_url; ?>&text=YOUR OFFER HERE" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>				
        			
                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="skype" class="fa fa-skype fa-5x"></i>
                                <h3>Share on Skype</h3>
        						<p>Share the link with contacts on Skype.</p>
                            </div>
                            <a href="https://web.skype.com/share?url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>		

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="facebook" class="fa fa-windows fa-5x"></i>
                                <h3>Share on Hotmail</h3>
        						<p>Share the link with friends via Hotmail.</p>
                            </div>
                            <a href="http://sn106w.snt106.mail.live.com/default.aspx?rru=compose&subject=Recommendation&body=<?php echo $current_url; ?>&wa=wsignin1.0" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="google" class="fa fa-envelope fa-5x"></i>
                                <h3>Share on Gmail</h3>
        						<p>Share the link with friends via Gmail.</p>
                            </div>
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to&su=Recommendation&body=<?php echo $current_url; ?>+&ui=2&tf=1&shva=1" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>			

                    <div class="col-sm-4 text-center">
                        <div class="select-options">
                            <div style="height: 250px;" class="select-items">
                                <i id="yahoo" class="fa fa-yahoo fa-5x"></i>
                                <h3>Share on Yahoo Mail</h3>
        						<p>Share the link with friends via Yahoo.</p>
                            </div>
                            <a href="http://compose.mail.yahoo.com/?body=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>		

                </div>
            </div>
        </main>
        <!------------------------EDIT LOCKED CONTENT------------------------>	
        <?php
                   
    }else { 
        //redirect to target url if counter is greater than target counter
        ?>
        <div class="container">
    		
            <div style="padding: 10px; background-color: #262626; border: #616161 2px solid;" class="row">		        
                    <div align="center" class="page-title">
    				<a href="//suite.social/purchase" target="_blank"><img src="https://dummyimage.com/1400x500/cccccc/000000.png&text=Replace+your+product+image+here" width="100%" alt="Banner"></a>
                        <h3 style="color:#8ec657"><strong>YOUR OFFER HERE</strong></h3>
    					<h4><i>"Your product or promotion description"</i></h4>
    					<div id="counter">Congratulations!<p style="font-size: 30px;">You have reached targeted visitor count of:</p><?php echo $total_visits;?></div>
    					<h4><i>"Now you can claim the offer!"</i></h4>
    					<p><a href="<?php echo $value['url'];?>" class="btn btn-lg btn-success"> CLICK HERE TO CLAIM! <i class="fa fa-gift"></i></a></p>
    				</div>
            </div>
        </div>            
        <?php
        $file1='visitor_'.$value['id'].'.txt';
        $file2=$value['id'].'.txt';
        unlink($file1);
        unlink($file2);
        session_destroy();
    }            
}
?>
<br>
<br>													
													
													
	                                  				<?php
	                                  			} else {
                                  				?>
	                                  				<div class="col">
	                                  					<table cellpadding="10" cellspacing="10" style="width: 100%; border-collapse: separate; border-spacing: 10px;">
		                                  					<?php foreach( $_SESSION[$_SESSION['network']['name']] AS $key => $value ): ?>
		                                  						<tr>
		                                  							<td valign="top">
		                                  								<?php echo $key; ?>
		                                  							</td>
		                                  							<td valign="top" class="network-response">
		                                  								<?php if( is_array( $value ) ): ?>
		                                  									<pre><?php print_r( $value ); ?></pre>
		                                  								<?php else: ?>	                                  									
		                                  									<input class="linkify" style="padding: 5px; width: 100%;" type="text" value="<?php echo $value; ?>">	                           
		                                  								<?php endif; ?>
		                                  							</td>
		                                  						</tr>
		                                  					<?php endforeach; ?>
	                                  					</table>
	                                  				</div>
                                  			<?php 
                                  				}
                                  			endif; ?>
                                  		</div>
 
                          		</div>
                       		</div>
                       		<!--/row-->
                       		
                       		<?php if( AUTHANY_DEBUG ): ?>
	                       		<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
	                       			<div class="col">
	                       				<textarea rows="40" style="width: 100%;">$_SESSION  <?php echo PHP_EOL.'--------------'.PHP_EOL; print_r( $_SESSION ); echo PHP_EOL; ?>$_GET  <?php echo PHP_EOL.'--------------'.PHP_EOL; print_r( $_GET ); echo PHP_EOL; ?>$_POST  <?php echo PHP_EOL.'--------------'.PHP_EOL; print_r( $_POST ); echo PHP_EOL; ?>$_COOKIE  <?php echo PHP_EOL.'--------------'.PHP_EOL; print_r( $_COOKIE ); echo PHP_EOL; ?></textarea>
	                       			</div>
	                       		</div>
                       		<?php endif; ?>

                      		<hr>
                	</div>
                	<!-- /padding -->
            	</div>
            	<!-- /main -->          
    	</div>
	</div>
		
<?php include('footer.php');?>