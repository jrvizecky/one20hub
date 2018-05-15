<?php
/**
 * Template Name: Deals Page
 */

 /*
 call https://deals.myone20.com/api/deals/all/ and then display the JSON results formatted?  That would be my preference.

 http://staging.deals.myone20.com/
 https://deals.myone20.com/api/deals/all/
  */

 // endpoint
 $url = 'https://deals.myone20.com/api/deals/all/';

 // variables

 // grab json
 $content = file_get_contents( $url );
 $json = json_decode( $content, false );
 $resultsObj = $json->result;

 // sort
 usort( $resultsObj, "cmp_seq" );

 // echo '<pre>';
 // var_dump($resultsObj);
 // echo '</pre>';

get_header(); ?>


		<main id="main" class="site-full-width deals-page">
            <div class="wrapper">
							<div class="grid">
							<?php
							/*
							display results in nice grid
							 */
							$i = 1;
							// foreach ( $resultsObj as $id => $deal ) if ($i < 10) {
							foreach ( $resultsObj as $id => $deal ) {

									if ( isActiveCoupon( $deal->date_start,$deal->date_end ) ) {
										//show if current date falls between begin and end
										$dealImage = $deal->image;
										$one20Brand = 'http://deals-wordpress-prod-uploads.s3.amazonaws.com/uploads/2017/07/Slice@300w.png';
										$dealTitle = $deal->title;
										$dealShortDesc = $deal->short_description;
										$dealExpires = $deal->date_end;
										$dealRetailerLogo = $deal->retailer_logo;
										$dealRetailerName = $deal->retailer_db_name;
										$dealUrl = $deal->url;

										echo '<div class="col-3_sm-12 deals-width">';

										// card
										echo '<a href="' . $dealUrl . '" class="dealThumb dealLink" style="display:block" target="_blank">';
										echo '<img style="max-height: 230px;" src="' . $dealImage . '"  data-event-category="internal-link" data-event-action="deal_' . $dealTitle . '_clicked"/>';
										echo '</a>';

										echo '<a href="' . $dealUrl . '" class="dealLink" target="_blank">';
										echo '<h3 data-event-category="internal-link" data-event-action="deal_' . $dealTitle . '_clicked">' . $dealTitle . '</h3>';
										echo '</a>';
										echo '<p class="dealDesc">' . $dealShortDesc . '</p>';

										echo "</div>";

										// start modal
										// echo '<div id="' . $id . '" class="modal dealContainer">';
										// echo '<div class="col-4">';
										// 	echo '<div class="col-6"><img src="' . $dealRetailerLogo . '" style="max-height: 50px; max-width: 75px;" /></div>';
										// 	echo '<div class="col-6-right"><img src="' . $one20Brand . '" style="" /></div>';
										// 	echo '<div class="col-12 grid">';
										// 	echo '<div class="col-12"><img src="' . $dealImage . '" /></div>';
										// 	echo '<div class="col-6"><span class="dealTitle">' . $dealTitle . '</span></div>';
										// 	echo '<div class="col-6"><span class="dealHighlight">' . $dealShortDesc . '</span></div>';
										// 	echo '<div class="col-12"><p class="dealDesc">' . $dealShortDesc . '</p>';
										// 	echo '<p>EXPIRES ' . date('F d', strtotime( $dealExpires )) . '</p>';
										// 	echo '<a href="'. $dealUrl . '" class="dealButton button-yellow"  data-event-category="internal-link" data-event-action="deal_' . $dealTitle . '_detail_viewed">Tap Here For Details</a>';
										// 	echo '</div>';
										// 	echo '</div>';
										// echo '</div>';
										// echo '</div>';
										// echo '<pre>';
										// var_dump($deal);
										// echo '</pre>';
									}

							}

							?>
							</div><!-- .grid -->
            </div>
		</main><!-- #main -->

<?php
//get_sidebar();
get_footer();

/*
Functions
*/
function isActiveCoupon( $beginDate, $endDate )
{
	$currentDate = date('Ymd');
	if ( ($currentDate > $beginDate) && ( $currentDate < $endDate ) )
	{
		return True;
	}
	else { return False; }
}

function cmp_seq( $a, $b )
{
	if ( $a->seq_num == $b->seq_num ) {
        return 0;
    }
  return ( $a->seq_num < $b->seq_num ) ? -1 : 1;
}
