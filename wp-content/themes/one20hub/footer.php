<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ONE20Hub
 */

?>

</div><!-- #content -->
<?php /*
<?php if( get_field('turn_on') == 'Yes' ): ?>
	<?php if( have_rows('apps_section_one', 'option') ):
		while( have_rows('apps_section_one', 'option') ): the_row();
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			?>
            <div class="apps">
                <div class="wrapper">
                    <div class="spacer">
                        <div class="image-surround-mobile">
                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
                        </div>
                        <div class="text-surround">
							<?php if($title):?>
                                <h2><?php echo $title; ?></h2>
							<?php endif; ?>
							<?php if($verbiage):?>
                                <p><?php echo $verbiage; ?></p>
							<?php endif; ?>
							<?php if( have_rows('apps') ): ?>
                                <div class="spacer inner">
                                    <div class="grid">
										<?php while( have_rows('apps') ): the_row();
											$image_app = get_sub_field('image');
											$title = get_sub_field('title');
											$verbiage = get_sub_field('verbiage');
											$link = get_sub_field('link');
											?>
                                            <div class="col-6_sm-12">
                                                <img src="<?php echo $image_app['url']; ?>" alt="<?php echo $image_app['alt']; ?>">
                                                <h3>
													<?php if ($link): ?><a href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
														<?php echo $title; ?>
														<?php if ($link): ?></a><?php endif; ?>
                                                </h3>
                                                <p><?php echo $verbiage; ?></p>
                                            </div>
										<?php endwhile; ?>
                                    </div>
                                </div>
							<?php endif; ?>
                        </div>
                        <div class="image-surround">
                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
                        </div>
                        <div class="clear"></div>
						<?php if ($button_link): ?>
                            <p class="button-link"><a href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <style>
                .apps {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                .apps .grid a {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                <?php if( get_sub_field('background') == 'Color' ): ?>
                .apps {
                    background-color: <?php the_sub_field('bg_color');?>;
                }
                <?php endif; ?>
                <?php if( get_sub_field('background') == 'Image' ): ?>
                .apps {
                    background-image: url(<?php the_sub_field('bg_image');?>);
                <?php if(get_sub_field('bg_repeat_or_cover') == 'Cover'): ?>
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                <?php endif; ?>
                <?php if(get_sub_field('bg_repeat_or_cover') == 'Repeat'): ?>
                    background-repeat: repeat;
                <?php endif; ?>
                }
                <?php endif; ?>
            </style>
		<?php endwhile; ?>
	<?php endif; ?>
<?php endif; ?>
*/ ?>


<footer id="colophon" class="site-footer">
    <div class="social-surround">
        <div class="wrapper">
            <div class='grid'>
                <div class="col-3_sm-6">
                    <a href="https://www.facebook.com/myone20/" target="_blank">
                        <i class="fab fa-facebook-square"></i>
                        <p class="social-footer">Join Us on Facebook</p>
                    </a>
                </div>
                <div class="col-3_sm-6">
                    <a href="https://www.youtube.com/channel/UCoSFBy2vrLKaD6Po8Ur94lQ" target="_blank">
                        <i class="fab fa-youtube"></i>
                        <p class="social-footer">Watch Us on Youtube</p>
                    </a>
                </div>
                <div class="col-3_sm-6">
                    <a href="https://twitter.com/my_one20" target="_blank">
                        <i class="fab fa-twitter"></i>
                        <p class="social-footer">Follow Us on Twitter</p>
                    </a>
                </div>
                <div class="col-3_sm-6 last">
                    <a href="/contact">
                        <i class="fas fa-envelope"></i>
                        <p class="social-footer">Contact Us</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="wrapper"> -->
    <div class="site-links">
        <div class="grid">
            <div class="col-3_md-6_xs-12">
                <h3>ONE20 Membership</h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-2',
					'menu_id'        => 'footer-menu',
				) );
				?>
            </div>
            <div class="col-3_md-6_xs-12">
                <h3>Apps</h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-3',
					'menu_id'        => 'footer-menu',
				) );
				?>
            </div>
            <div class="col-3_md-6_xs-12">
                <h3>Company Info</h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-4',
					'menu_id'        => 'footer-menu',
				) );
				?>
            </div>
            <div class="col-3_md-6_xs-12">
                <h3>Get in Touch</h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-5',
					'menu_id'        => 'footer-menu',
				) );
				?>
            </div>
        </div>
        <!-- </div> -->
    </div>
    <div class="site-info">
        <span class="company">&copy;2018 One20, Inc.</span> <span>All Rights Reserved</span>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<?php if (is_user_logged_in()) :?>

    <?php //if ( stripos( $_SERVER['HTTP_REFERER'], 'warranty-signup-form/?email=' ) !== false ) :?>
        <script>
            jQuery(document).on( 'nfFormReady', function() {
                jQuery("input[type=email]").val(localStorage.getItem("email"));
            });
        </script>
	<?php //endif; ?>
<?php endif; ?>

<?php if ( is_page(array('warranty-signup-form','warranty-signup') ) )  { ?>
    <script>
        jQuery(document).ready( function() {
            var tmp = null;
            jQuery(document).on('nfFormSubmitResponse', function(event, response, id) {

                var formData;
                var htmlData;
                var offerType;

                // Fields and values user filled in
                var formFields = {};
                var ninja = response.response.data.fields;

                for (var field in ninja) {
                    var label = "";
                    var value = "";
                    for (var p in ninja[field]) {
                        console.log(ninja[field][p]);
                        if (p === "label") {
                            label = ninja[field][p].replace(/ /g,"_");;
                        } else if (p === "value") {
                            value = ninja[field][p];
                        }
                    }
                    formFields[label] = value;
                }

                var ntpData = {
                    "custname": formFields.Full_Name,
                    "email": formFields.Email,
                    "phone": formFields.Phone,
                    "truckVin": formFields.Last_8_Digits_of_Truck_VIN,
                    "vehicleOem": formFields.Vehicle_OEM,
                    "engineMake": formFields.Engine_Make,
                    "engineModel": formFields.Engine_Model,
                    "truckYear": formFields.Truck_Year,
                    "engineMileage": formFields.Engine_Mileage
                }

                jQuery.ajax({
                    type: "POST",
                    url: "https://ntpupgrade.com/NTPWebAPI/api/One20LeadForm",
                    // url: "https://ntpupgrade.net/NTPWebAPI/api/One20LeadForm",
                    headers: {
                        "Content-Type":"application/json",
                        "apiKey": "One20Bffskbdv4363kjsd9dvbHDFHghHKdbsdbaSdHNTP"
                        // "apiKey": "QAOne20skdsdHdsn45sdJFb1553bbdllsds3rbgdkhbNTP"
                    },
                    // dataType: "jsonp",
                    data: JSON.stringify(ntpData),
                    success: function (ntpData) {                        // If 200 OK
                        formData = ntpData;
                        offerType = formData.offerType;
                        console.log('Success response: ' + ntpData);
                        $("#ntp-response").html(showOffer(offerType));
                        callback(ntpData);
                    },
                    error: function (error) {
                        console.log('Error:', error);
                        var noOffer = '<div style="width: 100%; padding: 0px 20px 20px; max-width: 500px; margin: 0 auto;">' +

                        '<p style="font-size: 18px; font-weight: 500; color: red;">' + error.responseJSON.message + ' Please refresh the page and try again.</p>' +
                        '</div>';

                        $("#ntp-response").html(noOffer);
                    }
                });

                function callback(response) {
                    // console.log('formData: ' + formData);
                    // console.log('offerType ' + offerType);
                    // console.log('response: ' + JSON.stringify(response));
                    window.dataLayer.push({
                        "event": "NtpResponse",
                        "ntpResponse": response
                    });
                    jQuery('#hideLoad').hide();
                }


                function showOffer(offerType) {
                    // Build html
                    var freemiumOffer = '<div style="width: 100%; padding: 20px; max-width: 500px; margin: 0 auto;">' +
                        '<p style="font-size: 18px;">Congratulations!<br/><br/>You’re qualified for Free Engine Warranty coverage for your current vehicle. Check your email inbox for details and special warranty offers or call NTP directly to learn more 800-950-3377.</p>' +
                        '</div>';
                    var premiumOffer = '<div style="width: 100%; padding: 20px; max-width: 500px; margin: 0 auto;">' +
                        '<p style="font-size: 18px">Sorry,<br/><br/>We’re unable to offer free warranty coverage for your current vehicle. Check your email inbox for exclusive discounted warranty offers or call NTP directly to learn more 800-950-3377.</p>' +
                        '</div>';
                    var noOffer = '<div style="width: 100%; padding: 20px; max-width: 500px; margin: 0 auto;">' +
                        '<p style="font-size: 18px; ">Uh oh,<br/></br>We’re unable to verify free warranty coverage based on the information provided. Please try again or call NTP directly at 800-950-3377</p>' +
                        '</div>';



                    // Find offer
                    switch (offerType) {
                        case "Freemium With Upgrade Offer":
                            htmlData = freemiumOffer;
                            break;
                        case "No Freemium But Premium Offer":
                            htmlData = premiumOffer;
                            break;
                        case "No Offer":
                            htmlData = noOffer;
                            break;
                        default:
                            htmlData = '';
                    }
                    return htmlData;
                }




            });
        });
    </script>
<?php } ?>
<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>
</body>
</html>
