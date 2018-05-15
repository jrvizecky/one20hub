<?php

/**
 * This file contains functions relating to custom conversions, such as for YouTube feeds.
 * 
 * @since 2.8
 * @todo Make function that given a video link, detects YT, Vimeo or Dailymotion and returns the embed link.
 * @package WP RSS Aggregator
 * @subpackage Feed to Post
 */


add_action( 'wprss_ftp_converter_inserted_post', 'wprss_ftp_check_yt_feed', 100, 2 );
/**
 * Check if the import post is a YouTube, Dailymotion or Vimeo feed item.
 * 
 * @since 2.8
 */
function wprss_ftp_check_yt_feed( $post_id, $source_id ) {
	// Get the post form the ID
	$post = get_post( $post_id );
	// If the post is null, exit function.
	if ( $post === NULL ) return;

	// Get the permalink
	$permalink = get_post_meta( $post_id, 'wprss_item_permalink', TRUE );
	// If the permalink is empty, do not continue. Exit function
	if ( $permalink === '' ) return;

	// Get the source options
	$options = WPRSS_FTP_Settings::get_instance()->get_computed_options( $source_id );
	// If embedded content is not allowed, exit function.
	if ( WPRSS_FTP_Utils::multiboolean( $options['allow_embedded_content'] ) === FALSE ) return;

	// Search for the video host
	$found_video_host = preg_match( '/http[s]?:\/\/(www\.)?(youtube|dailymotion|vimeo)\.com\/(.*)/i', $permalink, $matches );

	// If video host was found and embedded content is allowed
	if ( $found_video_host !== 0 && $found_video_host !== FALSE ) {

		// Determine the embed link
		$embed = NULL;

		// Check which video host was found in the URL and prepare the embed link
		$host = $matches[2];
		switch( $host ) {
			case 'youtube':
				preg_match( '/(&|\?)v=([^&]+)/', $permalink, $yt_matches );
				$embed = 'http://www.youtube.com/embed/' . $yt_matches[2];
				$embed = apply_filters( 'wprss_ftp_yt_auto_embed_url', $embed, $post_id, $source_id );
				break;
			case 'vimeo':
				preg_match( '/(\d*)$/i', $permalink, $vim_matches );
				$embed = 'http://player.vimeo.com/video/' . $vim_matches[0];
				$embed = apply_filters( 'wprss_ftp_vimeo_auto_embed_url', $embed, $post_id, $source_id );
				break;
			case 'dailymotion':
				preg_match( '/(\.com\/)(video\/)(.*)/i', $permalink, $dm_matches );
				$embed = 'http://www.dailymotion.com/embed/video/' . $dm_matches[3];
				$embed = apply_filters( 'wprss_ftp_dm_auto_embed_url', $embed, $post_id, $source_id );
				break;
		}

		// If the embed link was successfully generated, add it to the post
		if ( $embed !== NULL ) {
			$content = $post->post_content;
			$video_link = apply_filters( 'wprss_ftp_enable_auto_embed_videos', FALSE ) === TRUE ? $embed : $permalink;
			$new_content = $video_link . "\n\n" . $content;
			WPRSS_FTP_Utils::update_post_content( $post_id, $new_content );

			// YouTube table fix
			// If the host found is YouTube, and the source is using featured images and removing them from the content
			// then remove the first column in the table that YouTube puts in the feed
			if ( $host === 'youtube' && WPRSS_FTP_Utils::multiboolean( $options['use_featured_image'] ) && WPRSS_FTP_Utils::multiboolean( $options['remove_ft_image'] ) ) {
				// Add a builtin extraction rule
				add_filter('wprss_ftp_built_in_rules', 'wprss_ftp_yt_table_cleanup');
			}
		}

	}
}


/**
 * Cleans up the table that is found in YouTube feeds.
 * Only runs when Youtube feeds are detected, and the feed source uses featured images and
 * removes them from post content.
 * 
 * @since 2.8
 */
function wprss_ftp_yt_table_cleanup( $rules ) {
	$rules['table tbody tr:first-child td:first-child'] = 'remove';
	return $rules;
}

add_action( 'wprss_ftp_converter_inserted_post', 'wprss_ftp_save_yt_links', 10, 2 );
/**
 * Saves all YT links found in the post content as meta fields:
 *
 * wprss_yt_link_1, wprss_yt_embed_1
 * wprss_yt_link_2, wprss_yt_embed_2
 * ...
 *
 * @since 2.9.5
 * @todo Add support for Vimeo and Dailymotion
 * @param string|int $post_id
 * @param string|int $source_id
 */
function wprss_ftp_save_yt_links( $post_id, $source_id ) {
	// Get the post content
	$content = get_post_field( 'post_content', $post_id );
	// Find all YT links
	preg_match_all('/((?:http[s]?)?:\/\/(?:www\.)?youtube\.com\/watch\?v=[%&=#\w-\.]+)/mix', $content, $matches);
	$i = 1;
	// For each found link
	foreach( $matches as $match ) {
		if ( !empty( $match[0] ) ) {
			// Force http protocol (YT oEmbed bug)
			$link = str_replace('https', 'http', $match[0]);
			// Get the video ID
			preg_match( '/(&|\?)v=([^&]+)/', $link, $yt_matches );
			// Generate the embed link
			$embed = 'http://www.youtube.com/embed/' . $yt_matches[2];
			// Add the post meta
			add_post_meta( $post_id, "wprss_yt_link_$i", $link );
			add_post_meta( $post_id, "wprss_yt_embed_$i", $embed );
			$i++;
		}
	}
}


add_filter('wprss_ftp_converter_post_content', 'wprss_remove_fb_intermediary_links', 10, 2);
/**
 * Converts Facebook intermediary links to direct links.
 *
 * @param	$content The post content
 * @param	$source The source ID
 * @return	The post content.
 * @since 3.3.3
 */
function wprss_remove_fb_intermediary_links($content, $source) {
	if ( stripos($content, 'http://l.facebook.com/l.php?u=') === FALSE ) {
		return $content;
	}

	$content = preg_replace_callback("/<a([^>]+)>(.+?)<\/a>/", 'wprss_convert_fb_a_tag', $content);
	return $content;
}

function wprss_convert_fb_a_tag($matches) {
	$url = preg_replace_callback('/\s*href\s*=\s*(\"([^"]*\")|\'[^\']*\'|([^\'">\s]+))/', 'wprss_convert_fb_url', $matches[1]);
	$tag = "<a href=$url>" . $matches[2] . '</a>';

	wprss_log("Converted FB link " . $matches[0] . " to {$tag}", WPRSS_LOG_LEVEL_INFO );

	return $tag;
}

function wprss_convert_fb_url($matches) {
	$url = str_replace( 'http://l.facebook.com/l.php?u=', '', $matches[1] ); // Strip FB URL

	// Remove trailing info from original URL. This exists for links but not internal FB links.
	$trailing_info = stripos($url, "&amp;h="); 
	if ( $trailing_info !== FALSE ) {
		$url = substr( $url, 0, $trailing_info ) . '"';
	}

	$url = urldecode( $url ); // Decode the URL
	return $url;
}
