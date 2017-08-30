<?php 
/**
 * AuthAny
 * Demo Index
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2014 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://authany.com
 * @license     Commercial
 *
 * @since  	    Thursday, June 19, 2014, 09:34 PM GMT+1
 * @modified    $Date: 2014-06-24 10:07:44 +0200 (Tue, 24 Jun 2014) $ $Author: dev@cloneui.com $
 * @version     $Id: index.php 12 2014-06-24 08:07:44Z dev@cloneui.com $
 *
 * @category    Demo Files
 * @package     AuthAny
*/

define('BASEDIR', dirname( __FILE__) );
set_include_path(   
	BASEDIR.'/library/'.PATH_SEPARATOR.
	get_include_path()
);

error_reporting( E_ALL );
ini_set('display_errors', false);
ini_set('log_errors', true);

// time zone
date_default_timezone_set('UTC');

// error log
ini_set('error_log', BASEDIR.'/logs/php/'.date('m-d-Y').'.log');

$error = array();

require_once('AuthAny/Config/AuthAny.php');
$AuthAny->handleRequest();

// START:	AJAX Hanlder; shopping cart
if( !empty( $_POST ) ) {
	if( isset( $_POST['AUTHANY_AJAX'] ) ) {
		$method = $_POST['method'];
		$json	= array();
				
		header('Content-Type: application/json; charset=UTF-8');

		switch( $method ) {
			default:
				$json['status'] = 'ERROR';
				$json['error']	= 'UNHANDLED_EXCEPTION';
		}
				
		exit( json_encode( $json ) );
	}	
}
// END:		AJAX Hanlder; shopping cart	

// START:	Logout
if( preg_match( '/logout/', $_SERVER['REQUEST_URI'] ) ) {
	session_unset();
	session_destroy();
	
	setcookie( 'google_access_token', '', time() - 3600, '/' );
	setcookie( 'yos-social-at', '', time() - 3600, '/' );
	setcookie( 'yos-social-rt', '', time() - 3600, '/' );	
	
	header( 'Location: '.AUTHANY_BASEURL );
}
// END:		Logout

// START:	Error Handling
$possibleErrors = array( 
	'error', 
	'error_code', 
	'error_description', 
	'error_reason'
);

if( !empty( $_GET ) ) {
	foreach( $possibleErrors AS $key => $value ) {
		if( isset( $_GET[$value] ) ) {
			$error[$value] = '<strong>'.$value.':</strong>  '.$_GET[$value];	
		}	
	}	
}
// END:		Error Handling

// START:	User Logic
if( @$_SESSION['logged_in'] == 1 ) {
	$networkDisplayName = ( isset( $_SESSION['network']['display_name'] ) ) ? $_SESSION['network']['display_name'] : ucfirst( $_SESSION['network']['name'] );
	$networkIcon		= ( isset( $_SESSION['network']['short_id'] ) ) ? $_SESSION['network']['short_id'] : strtolower( $_SESSION['network']['name'] );	
	$userDisplayName	= ( isset( $_SESSION[$_SESSION['network']['name']]['email'] ) ) ? $_SESSION[$_SESSION['network']['name']]['email'] : $_SESSION[$_SESSION['network']['name']]['username'];
	switch( $networkIcon ) {
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
}
// END:		User Logic

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="description" content="AuthAny Demo -- Federated Identity, Social Login">
		<meta name="keywords" content="AuthAny,demo,federated identity,federated login,social login,single sign on,SSO,BizLogic,CloneUI">
		<meta name="author" content="BizLogic">
		<title>AuthAny Demo &mdash; Federated Identity. Implement Federated / Social Login on your site!</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/phpjs.min.js?v=1.0"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jstorage.min.js"></script>		
		<script type="text/javascript">	
			var BASEURL					= '<?php echo AUTHANY_BASEURL; ?>';	
			var DEFAULT_PRELOADER_IMAGE = BASEURL + '/images/preloader/default.gif';
			var ORDER_OPTIONS			= $.jStorage.get( 'ORDER_OPTIONS' );
			var JSTORAGE_TTL			= 622080000;
			if( is_null( ORDER_OPTIONS ) ) {
				ORDER_OPTIONS = {};
				$.jStorage.set( 'ORDER_OPTIONS', ORDER_OPTIONS );
				$.jStorage.setTTL( 'ORDER_OPTIONS', JSTORAGE_TTL ); 
			}			
		</script>
			
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/core.css?<?php echo rand(); ?>" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/bootstrap.callouts.css" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/zocial.css" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/socicon.css" rel="stylesheet" type="text/css">
		<link href="<?php echo AUTHANY_BASEURL; ?>/css/jquery-ui/themes/jface/jquery-ui.css" rel="stylesheet" type="text/css" />
		
		<style type="text/css">
			@import url('<?php echo AUTHANY_BASEURL; ?>/fonts/welove/zocial/welove.css');
			
			/* zocial */
			[class*="zocial-"]:before {
			  font-family: 'zocial', sans-serif;
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
        		<div class="row row-offcanvas row-offcanvas-left">                        
            	<!-- sidebar -->
            	<div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">
              		<ul class="nav">
          				<li>
          					<a href="#" data-toggle="offcanvas" class="visible-xs text-center">
          						<i class="glyphicon glyphicon-chevron-right"></i>
          					</a>
          				</li>
            		</ul>
               
                	<ul class="nav hidden-xs" id="lg-menu">
                    	<li class="active">
                    		<a href="#featured">
                    			<i class="glyphicon glyphicon-list-alt"></i> Featured
                    		</a>
                    	</li>
                    	<li>
                    		<a href="#stories">
                    			<i class="glyphicon glyphicon-list"></i> Stories
                    		</a>
                    	</li>
                    	<li>
                    		<a href="#">
                    			<i class="glyphicon glyphicon-paperclip"></i> Saved
                    		</a>
                    	</li>
                    	<li>
                    		<a href="#">
                    			<i class="glyphicon glyphicon-refresh"></i> Refresh
                    		</a>
                    	</li>
                	</ul>
                	<ul class="list-unstyled hidden-xs" id="sidebar-footer">
                    	<li>
                      		<a href="http://bizlogicdev.com" target="_blank">
                      			<i class="fa fa-magic"></i> BizLogic
                      		</a>
                    	</li>
                	</ul>
              
              		<!-- tiny only nav-->
              		<ul class="nav visible-xs" id="xs-menu">
                  		<li>
                  			<a href="#featured" class="text-center">
                  				<i class="glyphicon glyphicon-list-alt"></i>
                  			</a>
                  		</li>
                    	<li>
                    		<a href="#stories" class="text-center">
                    			<i class="glyphicon glyphicon-list"></i>
                    		</a>
                    	</li>
                  		<li>
                  			<a href="#" class="text-center">
                  				<i class="glyphicon glyphicon-paperclip"></i>
                  			</a>
                  		</li>
                    	<li>
                    		<a href="#" class="text-center">
                    			<i class="glyphicon glyphicon-refresh"></i>
                    		</a>
                    	</li>
                	</ul>              
            	</div>
            	<!-- /sidebar -->
          
            	<!-- main right col -->
            	<div class="column col-sm-10 col-xs-11" id="main">              
                	<!-- top nav -->
              		<div class="navbar navbar-blue navbar-static-top">  
                    	<div class="navbar-header">
                      		<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        		<span class="sr-only">Toggle</span>
                        		<span class="icon-bar"></span>
          						<span class="icon-bar"></span>
          						<span class="icon-bar"></span>
                      		</button>
                  		</div>
                  		<nav class="collapse navbar-collapse" role="navigation">
                    		<ul class="nav navbar-nav">
                      			<li>
                        			<a class="blockUI-trigger" href="<?php echo AUTHANY_BASEURL; ?>">
                        				<i class="fa fa-paper-plane-o"></i>
                        			</a>
                      			</li>
                    		</ul>
                    		<ul class="nav navbar-nav navbar-right" style="margin-right: 2px;">
                      			<li class="dropdown">
                      				<?php if( @$_SESSION['logged_in'] != 1 )?>
                        			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        				<span class="<?php if( @$_SESSION['logged_in'] != 1 ): ?>glyphicon glyphicon-user<?php else: ?><?php echo $displayFont . $networkIcon; ?><?php endif; ?>"></span> <?php if( @$_SESSION['logged_in'] != 1 ): ?>Login<?php else: ?><?php echo $userDisplayName; ?><?php endif;?>
                        			</a>
                        			<ul class="dropdown-menu">
                        				<?php if( @$_SESSION['logged_in'] != 1 ): ?>
	                          				<li>
	                          					<a class="triggerTabLocal" href="javascript:void(0);">
	                          						<i class="fa fa-home"></i> Local
	                          					</a>
	                          				</li>
	                          				<li>
	                          					<a class="triggerTabFederated" href="javascript:void(0);">
	                          						<i class="fa fa-globe"></i> Federated
	                          					</a>
	                          				</li>
	                          			<?php else: ?>
	                          				<li>
	                          					<a class="blockUI-trigger" href="<?php echo AUTHANY_BASEURL; ?>/logout">
	                          						<i class="fa fa-times-circle"></i> Logout
	                          					</a>
	                          				</li>
                          				<?php endif; ?>
                        			</ul>
                      			</li>
                    		</ul>
                  		</nav>
                	</div>
                	<!-- /top nav -->
              
					<div class="padding">
                    	<div class="full col-sm-9">                      
                        	<!-- content -->                      
                      		<div class="row">                          
                          		<!-- main col right -->
                          		<div class="col">                     
                               		<div class="panel panel-default">
                                 		<div class="panel-heading">
                                 			<h4>
                                 				<?php if( @$_SESSION['logged_in'] != 1 ): ?>
                                 					<i class="fa fa-shield"></i> Login
                                 				<?php else: ?>
                                 					<?php $networkDisplayName = ( isset( $_SESSION['network']['display_name'] ) ) ? $_SESSION['network']['display_name'] : ucfirst( $_SESSION['network']['name'] ); ?>
                                 					<span class="<?php echo $displayFont . $networkIcon; ?>"></span> Logged in via <?php echo $networkDisplayName; ?> (<?php echo $userDisplayName; ?>)
                                 				<?php endif; ?>
                                 			</h4>
                                 		</div>
                                  		<div id="primaryElement" class="panel-body">
                                  			<?php if( @$_SESSION['logged_in'] != 1 ): ?>
	                                  			<div>
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
													<!-- Nav tabs -->
													<ul class="nav nav-tabs">
											  			<li>
											  				<a id="triggerTabLocal" href="#loginLocal" data-toggle="tab"> 
											  					<i class="fa fa-home"></i> Local
											  				</a>
											  			</li>
											  			<li class="active">
											  				<a id="triggerTabFederated" href="#loginFederated" data-toggle="tab">
											  					<i class="fa fa-globe"></i> Federated
											  				</a>
											  			</li>
													</ul>
											
													<!-- Tab panes -->
													<div class="tab-content">
											  			<div class="tab-pane fade" id="loginLocal" style="margin-top: 20px;">
											  				<div class="bs-callout bs-callout-danger">
	    														<i class="fa fa-exclamation-triangle"></i> Local logon is disabled in this demo. Try <a class="triggerTabFederated" href="javascript:void(0);"><button class="btn btn-default" type="button"><i class="fa fa-globe"></i> Federated Logon</button></a>
	  														</div>
											  				<div class="form-group">
										    					<input type="text" data-required="1" placeholder="e-mail address" class="form-control" id="userEmail" name="userEmail" DISABLED>
										  					</div>
										  		
										  					<div class="form-group">
										    					<input type="password" data-required="1" placeholder="Password" class="form-control" id="userPassword" name="userPassword" DISABLED>
										  					</div>
										  					<div class="pull-right">
										  						<button style="cursor: not-allowed;" class="btn btn-default disabled" id="btnUserLogin" DISABLED><i class="fa fa-key"></i> Login</button>									  
										  					</div>
											  			</div>
											  			<div class="tab-pane fade in active" id="loginFederated" style="margin-top: 20px;">
											  				<?php if( !empty( $error ) ): ?>
											  					<div class="alert alert-danger">
											  						<?php foreach( $error AS $key => $value ): ?>
											  							<div>
											  								<i class="fa fa-exclamation-triangle"></i> <?php echo $value; ?>
											  							</div>
											  						<?php endforeach; ?>
											  					</div>		
											  				<?php endif; ?>
											  			
											  				<div class="bs-callout bs-callout-info">
	    														<i class="fa fa-cog"></i> Select your preferred network
	  														</div>
	  														
											  				<a class="zocial facebook" href="javascipt:void(0);" style="margin: 10px;" data-network="facebook">Facebook</a> 
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
													    	<a class="zocial disqus" href="javascipt:void(0);" style="margin: 10px;" data-network="disqus">Disqus</a>
											  			</div>
													</div>
	                                  
	                                    			<div class="clearfix"></div>
	                                    			<hr>
	                                    			<i class="fa fa-info-circle"></i> This demo demonstrates <a href="http://en.wikipedia.org/wiki/Federated_identity" target="_blank">Federated Identity</a> via numerous third party systems
	                                  			</div>
                                  			<?php else: ?>
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
                                  			<?php endif; ?>
                                  		</div>
                                  		<!-- END:	.panel-body -->
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
                        		
                        	<div class="row">
                          		<div class="col-sm-6">
                            		<i alt="BizLogic" title="BizLogic" class="fa fa-magic"></i> <a href="http://bizlogicdev.com" target="_blank">BizLogic</a>
                          		</div>
                       		 </div>
                      
                        	<div class="row" id="footer">    
                          		<div class="col-sm-6"></div>
                          		<div class="col-sm-6">
                           	 		<p>
                            			<a href="#" target="_blank" class="pull-right">&copy; Copyright <?php echo date('Y'); ?> BizLogic. All Rights Reserved.</a>
                            		</p>
                          		</div>
                        	</div>
                      		<hr>
                    	</div>
                    	<!-- /col-9 -->
                	</div>
                	<!-- /padding -->
            	</div>
            	<!-- /main -->          
        	</div>
    	</div>
	</div>
	
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery.linkify.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery.serializejson.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery.blockUI.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery.blockUI.defaults.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/jquery.imgpreload.min.js"></script>		
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/js/core.js?<?php echo rand(); ?>"></script>
		
		<script type="text/javascript">
			$(document).ready(function() {	

				<?php if( @$_SESSION['logged_in'] == 1 ): ?>
					var originalColor = $('#primaryElement').css('backgroundColor');

					$('#primaryElement').animate({
						backgroundColor: '#fcf8e3'
					}, 'slow');
										
					setTimeout(function() {
						$('#primaryElement').animate({
							backgroundColor: originalColor
						});
					}, 2000);				
				<?php endif; ?>
				
				$('.linkify').linkify({
				    target: '_blank'
				});
								
				$.imgpreload([
					DEFAULT_PRELOADER_IMAGE, 
					BASEURL + '/images/preloader/tools.gif'
				], {
					each: function() {
				        // this = dom image object
				        // check for success with: $(this).data('loaded')
				        // callback executes on every image load
				    },
				    all: function() {
				        // this = array of dom image objects
				        // check for success with: $(this[i]).data('loaded')
				        // callback executes when all images are loaded	
				        $.unblockUI();			    		    			    			        
				    }
				});	
			});
		</script>
		
	</body>
</html>