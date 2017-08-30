<!DOCTYPE html>
<html lang="en">
<head>
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
</head>
<body>
<div class="container">
    <div class="text-center">
        <div class="col-md-5 col-md-offset-2">
            <div class=" social-buttons">
                <a href="javascipt:void(0);" data-network="facebook" class="zocial">
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
</body>
<?php include('footer.php');?>
</html>
<?php
print " <br> current url $current_url";
define("MY_CONSTANT", 1);
print "<pre>";
print "<br>constant";
print_r(get_defined_constants(true)['user']);
print "<hr><br>session";
print_r($_SESSION);
//print_r($_SESSION['twitter']);
print "<hr><br>cookie";
print_r($_COOKIE);
print "</pre>";

