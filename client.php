<?php 
session_start();
//if (isset($_GET["id"]) && $_GET["id"] > 0) {
//require_once "sharelock.class.php";

//$sharelock = new sharelock();
//$share_id = $_GET["id"];
//$ip = 1;
//$client_id = $_GET["cid"];
//$total_visits = $sharelock->visitor($share_id, $ip, $client_id);

$banner = '//suite.social/images/banner/reseller.jpg';
$logo = '//suite.social/images/profile/guy.jpg';
$headline = 'Earn £5,000+ monthly selling Social Media Management using our platform!';
$caption = 'Hi, I am Mr Grower, your Virtual Social Media Manager. I will help you resell the Social Suite Platform to local businesses & professionals so you can grow your monthly income!';
$info = '
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
	   <p>Social Suite includes hundreds of marketing tools, apps and services to ATTRACT, ENGAGE, CONVERT, RETAIN new customers and get a REPORT of the results. If clients are busy running their business, serving customers and want to relieve the stress or confusion, they can use this platform do all the hard work in GROWING their following, traffic & sales 24-7, 365 days a year, saving 70% of their time, money & resources. It is basically a one-stop shop for all your social media needs.</p>										
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
';
$users = '
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
';
$content = '
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
';
$website = '<a href="//suite.social" target="_blank" class="btn btn-success"> TRY PLATFORM!';
$embed = '
<div align="center">

<br><br><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn.jpg"></a>	

<h4>Copy the embed code for your website, blog or sales page</h4>               	
<textarea rows="3" class="form-control"><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn.jpg"></a></textarea>				
	
<br><br><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn2.jpg"></a>	

<h4>Copy the embed code for your website, blog or sales page</h4>               	
<textarea rows="3" class="form-control"><a href="<?php echo $current_url; ?>" target="_blank"><img src="//suite.social/images/ad/earn2.jpg"></a></textarea>
	
</div>
';
$locked = '
<p><a href="<?php echo $value[\'url\'];?>" class="btn btn-lg btn-success"> CLICK HERE TO CLAIM! <i class="fa fa-gift"></i></a></p>
';

$_SESSION['banner'] = $banner;
$_SESSION['logo'] = $logo;
$_SESSION['headline'] = $headline;
$_SESSION['caption'] = $caption;
$_SESSION['info'] = $info;
$_SESSION['users'] = $users;
$_SESSION['content'] = $content;
$_SESSION['website'] = $website;
$_SESSION['embed'] = $embed;
$_SESSION['locked'] = $locked;

header("Location: index.php");	
