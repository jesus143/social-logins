		<div align="center" id="footer">	
			
			<!--  Copyright Line -->
			<div class="copy">&copy; <?php echo date('Y'); ?> - <a href="//suite.social">Social Suite</a> - All Rights Reserved. <a href="//suite.social/contact">Contact</a> I <a href="//suite.social/terms">Terms & Conditions</a> I <a href="//suite.social/privacy">Privacy Policy</a></div>
			<!--  End Copyright Line -->
	
		</div>

		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery.linkify.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery.serializejson.min.js"></script>
		<!--<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/bootstrap.min.js"></script>-->
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery.blockUI.min.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery.blockUI.defaults.js"></script>
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/jquery.imgpreload.min.js"></script>		
		<script type="text/javascript" src="<?php echo AUTHANY_BASEURL; ?>/assets/js/core.js?<?php echo rand(); ?>"></script>
		
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