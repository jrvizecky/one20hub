<?php

	add_action( 'add_meta_boxes', 'wprss_kf_add_meta_boxes');
	/**
	 * Adds the meta boxes to the wprss feed source screen.
	 * 
	 * @since 1.2
	 */
	function wprss_kf_add_meta_boxes(){
		add_meta_box(
            'keyword_filtering_meta_box', 
            __( 'Feed Keyword Filtering', WPRSS_TEXT_DOMAIN ), 
            'wprss_kf_meta_box_callback', 
            'wprss_feed', 
            'normal', 
            'core'
        );
	}


	/**
	 * The callback that renders the keyword filtering metabox.
	 * 
	 * @since 1.2
	 */
	function wprss_kf_meta_box_callback() {
		global $post;
		$keywords = get_post_meta( $post->ID, 'wprss_keywords', true );
		$keywords_any = get_post_meta( $post->ID, 'wprss_keywords_any', true );
		$keywords_not = get_post_meta( $post->ID, 'wprss_keywords_not', true );
		$keywords_tags = get_post_meta( $post->ID, 'wprss_keywords_tags', true );
		$keywords_not_tags = get_post_meta( $post->ID, 'wprss_keywords_not_tags', true );

		$filter_title = get_post_meta( $post->ID, 'wprss_filter_title', true );
		$filter_title = ( $filter_title == '' )? 'true' : $filter_title;
		$filter_title_checked = ( $filter_title == 'true' )? 'checked="checked"' : '';

		$filter_content = get_post_meta( $post->ID, 'wprss_filter_content', true );
		$filter_content = ( $filter_content == '' )? 'true' : $filter_content;
		$filter_content_checked = ( $filter_content == 'true' )? 'checked="checked"' : '';
		?>


		<?php // To move to stylesheet ?>
		<style>
			.wprss_kf_metabox_section {
				position: relative;
				display: block;
				padding: 5px;
			}
			.wprss_kf_metabox_section > div {
				display: block;
				margin-bottom: 15px;
			}
			.wprss_kf_metabox_section > div > label:first-child {
				display: block;
				margin-bottom: 3px;
			}
			.wprss_kf_metabox_section > div > input[type="text"] {
				display: block;
				width: 90%;
				max-width: 90%;
			}
			.wprss_kf_metabox_separator {
				color: white;
				border: 0;
				border-top: 1px solid #ccc;
			}
		</style>

		<h4><?php _e('Keyword Filtering', WPRSS_TEXT_DOMAIN); ?></h4>
		<div class="wprss_kf_metabox_section">
			<div>
				<label for="wprss_keywords"><?php _e('Contains <b>all</b> of these words/phrases:', WPRSS_TEXT_DOMAIN); ?></label>
				<input type="text" id="wprss_keywords" name="wprss_keywords" value="<?php echo esc_attr($keywords); ?>" placeholder="<?php _e('Enter comma separated words or phrases', WPRSS_TEXT_DOMAIN); ?>" size="60" />
			</div>

			<div>
				<label for="wprss_keywords_any"><?php _e('Contains <b>any</b> of these words/phrases:', WPRSS_TEXT_DOMAIN); ?></label>
				<input type="text" id="wprss_keywords_any" name="wprss_keywords_any" value="<?php echo esc_attr($keywords_any); ?>" placeholder="<?php _e('Enter comma separated words or phrases', WPRSS_TEXT_DOMAIN); ?>" size="60" />
			</div>

			<div>
				<label for="wprss_keywords_not"><?php _e('Contains <b>none</b> of these words/phrases:', WPRSS_TEXT_DOMAIN); ?></label>
				<input type="text" id="wprss_keywords_not" name="wprss_keywords_not" value="<?php echo esc_attr($keywords_not); ?>" placeholder="<?php _e('Enter comma separated words or phrases', WPRSS_TEXT_DOMAIN); ?>" size="60" />
			</div>

			<div>
				<label><?php _e('Apply the above filtering methods on the:', WPRSS_TEXT_DOMAIN); ?></label>

				<input type="hidden" name="wprss_filter_title" value="false" />
				<input type="checkbox" id="wprss_filter_title" name="wprss_filter_title" value="true" <?php echo $filter_title_checked; ?> />
				<label for="wprss_filter_title"><?php _e('Feed Title', WPRSS_TEXT_DOMAIN); ?></label>
				<br/>

				<input type="hidden" name="wprss_filter_content" value="false" />
				<input type="checkbox" id="wprss_filter_content" name="wprss_filter_content" value="true" <?php echo $filter_content_checked; ?> />
				<label for="wprss_filter_content"><?php _e('Feed Content', WPRSS_TEXT_DOMAIN); ?></label>
				<br/>
			</div>
		</div>

		<hr class="wprss_kf_metabox_separator" />

		<h4><?php _e('Tag Filtering', WPRSS_TEXT_DOMAIN); ?></h4>
		<div class="wprss_kf_metabox_section">
			<div>
				<label for="wprss_keywords_tags"><?php _e('Contains <b>any</b> of these tags:', WPRSS_TEXT_DOMAIN); ?></label>
				<input type="text" id="wprss_keywords_tags" name="wprss_keywords_tags" value="<?php echo esc_attr($keywords_tags); ?>" placeholder="<?php _e('Enter a comma separated list of tags', WPRSS_TEXT_DOMAIN); ?>" size="60" />
			</div>
			<div>
				<label for="wprss_keywords_not_tags"><?php _e('Contains <b>none</b> of these tags:', WPRSS_TEXT_DOMAIN); ?></label>
				<input type="text" id="wprss_keywords_not_tags" name="wprss_keywords_not_tags" value="<?php echo esc_attr($keywords_not_tags); ?>" placeholder="<?php _e('Enter a comma separated list of tags', WPRSS_TEXT_DOMAIN); ?>" size="60" />
			</div>
		</div>

		<?php
	}



	add_action( 'save_post', 'wprss_kf_save_post', 8, 2 );
	/**
	 * Saves the post meta, when a post is saved or created.
	 * 
	 * @since 1.2
	 */
	function wprss_kf_save_post( $post_id, $post ) {
		if ( isset($_POST['wprss_keywords']) ) {
			update_post_meta( $post_id, 'wprss_keywords', $_POST['wprss_keywords'] );
		}
		if ( isset($_POST['wprss_keywords_any']) ) {
			update_post_meta( $post_id, 'wprss_keywords_any', $_POST['wprss_keywords_any'] );
		}
		if ( isset($_POST['wprss_keywords_not']) ) {
			update_post_meta( $post_id, 'wprss_keywords_not', $_POST['wprss_keywords_not'] );
		}
		if ( isset($_POST['wprss_keywords_tags']) ) {
			update_post_meta( $post_id, 'wprss_keywords_tags', $_POST['wprss_keywords_tags'] );
		}
		if ( isset($_POST['wprss_keywords_not_tags']) ) {
			update_post_meta( $post_id, 'wprss_keywords_not_tags', $_POST['wprss_keywords_not_tags'] );
		}
		if ( isset($_POST['wprss_filter_title']) ) {
			update_post_meta( $post_id, 'wprss_filter_title', $_POST['wprss_filter_title'] );
		}
		if ( isset($_POST['wprss_filter_content']) ) {
			update_post_meta( $post_id, 'wprss_filter_content', $_POST['wprss_filter_content'] );
		}
	}