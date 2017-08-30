<?php
//
//
//define('BASEDIR', dirname(__FILE__));
//set_include_path(
//    BASEDIR . '/library/' . PATH_SEPARATOR .
//    get_include_path()
//);
// 
//
//require_once('AuthAny/Config/AuthAny.php');
//$AuthAny->handleRequest();
//
//
//

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>

        <!-- Title -->
        <title>Social Sharer - <?php echo $headline; ?></title>

        <!-- Meta Data -->
        <meta name="title" content="<?php echo $headline; ?>">
        <meta name="description" content="<?php echo $caption; ?>">
        <meta name="keywords" content="Blog Share, Facebook Share, Google+ Share, Instagram Share, Linkedin Share, Pinterest Share, Reddit Share, Social Media Promotion, StumbleUpon Share, Tumblr Share, Twitter Share, Vk Share, WhatsApp Share, Wordpress Share, XING Share">
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
        <meta itemprop="name" content="<?php echo $headline; ?>">
        <meta itemprop="description" content="<?php echo $caption; ?>">

        <!-- Twitter -->
        <meta name="twitter:card" content="<?php echo $headline; ?>">
        <meta name="twitter:title" content="<?php echo $headline; ?>">
        <meta name="twitter:description" content="<?php echo $caption; ?>">

        <!-- Open Graph General (Facebook & Pinterest) -->
        <meta property="og:url" content="<?php echo $current_url; ?>">
        <meta property="og:title" content="<?php echo $headline; ?>">
        <meta property="og:description" content="<?php echo $caption; ?>">
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
                width: 60%;
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

                                    <img src="<?php echo $banner; ?>" width="100%" alt="Banner">
                                    <main id="dashboard">
                                        <div class="container">

                                            <div class="row">

                                                <div align="center" class="page-title">
                                                    <img style="margin-top: 30px" src="<?php echo $logo; ?>" width="80px" alt="Profile Image">
                                                    <h4 style="color:#8ec657"><strong><?php echo $headline; ?></strong></h4>
                                                    <h4><i>"<?php echo $caption; ?>"</i></h4>
                                                    <a href="#Login" data-toggle="modal" class="btn btn-success"> LOGIN NOW! <i class="fa fa-sign-in"></i></a>
                                                    <a href="#info" data-toggle="collapse" class="btn btn-gray"> MORE INFO <i class="fa fa-question-circle"></i></a>
                                                    <?php echo $website; ?> <i class="fa fa-link"></i></a>
                                                </div>

                                            </div>

                                            <div id="info" class="collapse">
                                                <br>
                                                <!--***** INFO START *****-->
                                                <?php echo $info; ?>
                                                <!--***** INFO END *****-->
                                            </div>

                                            <!--***** USERS START *****-->
                                            <?php echo $users; ?>
                                            <!--***** USERS END *****-->

                                            <!--***** CONTENT START *****-->
                                            <?php echo $content; ?>
                                            <!--***** CONTENT END *****-->

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

                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-gray" data-dismiss="modal">Close</button>
                                                    <?php echo $website; ?></a>
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
                                                            <img src="<?php echo $banner; ?>" width="100%" alt="Banner">

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
                                                                    <span class="<?php echo $displayFont . $networkIcon; ?>"></span> Logged in via <?php echo $networkDisplayName; ?> <!--(<?php echo $userDisplayName; ?>)-->
                                                                <?php endif; ?>
                                                            </h4>

                                                            <h3 style="color:#8ec657"><strong>Hi <?php echo $userDisplayName; ?>, <?php echo $headline; ?></strong></h3>
                                                            <h4><i>"<?php echo $caption; ?>"</i></h4>
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

                                                                <!--***** EMBED START *****-->
                                                                <?php echo $embed; ?>
                                                                <!--***** EMBED END *****-->

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
                                                                <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
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
                                                                <a href="https://www.linkedin.com/shareArticle?url=<?php echo $current_url; ?>&title=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="tumblr" class="fa fa-tumblr fa-5x"></i>
                                                                    <h3>Share on Tumblr</h3>
                                                                    <p>Share the link with followers on Tumblr.</p>
                                                                </div>
                                                                <a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo $current_url; ?>&title=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="reddit" class="fa fa-reddit fa-5x"></i>
                                                                    <h3>Share on Reddit</h3>
                                                                    <p>Share the link with friends on Reddit.</p>
                                                                </div>
                                                                <a href="https://reddit.com/submit?url=<?php echo $current_url; ?>&title=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="livejournal" class="fa fa-pencil fa-5x"></i>
                                                                    <h3>Share on LiveJournal</h3>
                                                                    <p>Share your the link with followers on LiveJournal.</p>
                                                                </div>
                                                                <a href="http://www.livejournal.com/update.bml?subject=<?php echo $headline; ?>&event=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="blogger" class="fa fa-rss fa-5x"></i>
                                                                    <h3>Share on Blogger</h3>
                                                                    <p>Share the link with followers on blogger.</p>
                                                                </div>
                                                                <a href="https://www.blogger.com/blog-this.g?u=<?php echo $current_url; ?>&n=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="vk" class="fa fa-vk fa-5x"></i>
                                                                    <h3>Share on Vk.com</h3>
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
                                                                <a href="https://share.flipboard.com/bookmarklet/popout?v=2&title=<?php echo $headline; ?>&url=<?php echo $current_url; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
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
                                                                <a href="http://www.stumbleupon.com/submit?url=<?php echo $current_url; ?>&title=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="digg" class="fa fa-digg fa-5x"></i>
                                                                    <h3>Share on Digg</h3>
                                                                    <p>Share the link with followers on Digg.</p>
                                                                </div>
                                                                <a href="http://digg.com/submit?url=<?php echo $current_url; ?>&title=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> REFER<i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">
                                                            <div class="select-options">
                                                                <div style="height: 250px;" class="select-items">
                                                                    <i id="delicious" class="fa fa-delicious fa-5x"></i>
                                                                    <h3>Share on Delicious</h3>
                                                                    <p>Share the link with followers on Delicious.</p>
                                                                </div>
                                                                <a href="https://delicious.com/save?v=5&provider={provider}&noui&jump=close&url=<?php echo $current_url; ?>&title=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
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
                                                                <a href="https://telegram.me/share/url?url=<?php echo $current_url; ?>&text=<?php echo $headline; ?>" target="_blank" class="btn btn-gray btn-block"> SHARE NOW <i class="fa fa-arrow-right"></i></a>
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
                                                        <img src="<?php echo $banner; ?>" width="100%" alt="Banner">
                                                        <h3 style="color:#8ec657"><strong><?php echo $headline; ?></strong></h3>
                                                        <h4><i>"<?php echo $caption; ?>"</i></h4>
                                                        <div id="counter">Congratulations!<p style="font-size: 30px;">You have reached the targeted visitor count of:</p><?php echo $total_visits;?></div>
                                                        <h4><i>"Now you can claim!"</i></h4>
                                                        <?php echo $locked; ?>
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