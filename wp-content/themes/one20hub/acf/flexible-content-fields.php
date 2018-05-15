<?php
if( have_rows('flexible_content') ):
	while ( have_rows('flexible_content') ) : the_row();?>
		<?php if( get_row_layout() == 'image_floated_left' ):
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$secondary_title = get_sub_field('secondary_title');
			$secondary_verbiage = get_sub_field('secondary_verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$class_name = get_sub_field('class_name');
			$id = get_sub_field('id');
			$category = get_sub_field('category');
			$action = get_sub_field('action');
			?>
			<div class="what-you-get <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer">
						<div class="image-surround">
							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
						</div>
						<div class="text-surround">
							<?php if ($title):?>
								<h2><?php echo $title; ?></h2>
							<?php endif; ?>
							<?php if ($verbiage): ?>
								<?php echo $verbiage; ?>
							<?php endif; ?>
							<?php if ($secondary_title): ?>
								<h3 style="margin-top: 30px;"><?php echo $secondary_title; ?></h3>
							<?php endif; ?>
							<?php if ($secondary_verbiage): ?>
								<?php echo $secondary_verbiage; ?>
							<?php endif; ?>
							<?php if ($button_link): ?>
                                <p class="button-link"><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<style>
				.what-you-get.<?php echo $class_name; ?> .spacer {
					padding:<?php echo $desktop_padding; ?>;
				}
				@media (max-width: 1025px) {
					.what-you-get.<?php echo $class_name; ?> .spacer {
						padding:<?php echo $mobile_padding; ?>;
					}
				}
				.what-you-get.<?php echo $class_name; ?> {
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.what-you-get.<?php echo $class_name; ?> {
					background-color: <?php the_sub_field('bg_color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.what-you-get.<?php echo $class_name; ?> {
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


		<?php elseif( get_row_layout() == 'image_floated_right' ):
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$secondary_title = get_sub_field('secondary_title');
			$secondary_verbiage = get_sub_field('secondary_verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$class_name = get_sub_field('class_name');
			$id = get_sub_field('id');
			$category = get_sub_field('category');
			$action = get_sub_field('action');
			?>
			<div class="apps single-partner-image-right <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer">
						<div class="image-surround-mobile">
							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
						</div>
						<div class="text-surround">
							<?php if ($title):?>
								<h2><?php echo $title; ?></h2>
							<?php endif; ?>
							<?php if ($verbiage): ?>
								<?php echo $verbiage; ?>
							<?php endif; ?>
							<?php if ($secondary_title): ?>
								<h3 style="margin-top: 30px;"><?php echo $secondary_title; ?></h3>
							<?php endif; ?>
							<?php if ($secondary_verbiage): ?>
								<?php echo $secondary_verbiage; ?>
							<?php endif; ?>
							<?php if ($button_link): ?>
                                <p class="button-link"><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
							<?php endif; ?>
						</div>
						<div class="image-surround">
							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<style>

				.apps.single-partner-image-right.<?php echo $class_name; ?> .spacer {
					padding:<?php echo $desktop_padding; ?>;
				}
				@media (max-width: 1025px) {
					.apps.single-partner-image-right.<?php echo $class_name; ?> .spacer {
						padding:<?php echo $mobile_padding; ?>;
					}
				}
				.apps.<?php echo $class_name; ?>{
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.apps.<?php echo $class_name; ?> {
					background-color: <?php the_sub_field('bg_color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.apps.<?php echo $class_name; ?> {
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

		<?php elseif( get_row_layout() == 'centered_image_floated_left' ):
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$secondary_title = get_sub_field('secondary_title');
			$secondary_verbiage = get_sub_field('secondary_verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$class_name = get_sub_field('class_name');
			$id = get_sub_field('id');
			$category = get_sub_field('category');
			$action = get_sub_field('action');
			?>
			<div class="centered-image-left <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer">
						<div class="grid-middle">
							<div class="col_5-sm-12">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
							</div>
							<div class="col-7_sm-12">
								<?php if ($title):?>
									<h2><?php echo $title; ?></h2>
								<?php endif; ?>
								<?php if ($verbiage): ?>
									<?php echo $verbiage; ?>
								<?php endif; ?>
								<?php if ($secondary_title): ?>
									<h3 style="margin-top: 30px;"><?php echo $secondary_title; ?></h3>
								<?php endif; ?>
								<?php if ($secondary_verbiage): ?>
									<?php echo $secondary_verbiage; ?>
								<?php endif; ?>
								<?php if ($button_link): ?>
                                    <p class="button-link"><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<style>
				.centered-image-left.<?php echo $class_name; ?> .spacer {
					padding:<?php echo $desktop_padding; ?>;
				}
				@media (max-width: 1025px) {
					.centered-image-left.<?php echo $class_name; ?> .spacer {
						padding:<?php echo $mobile_padding; ?>;
					}
				}
				.centered-image-left.<?php echo $class_name; ?> {
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.centered-image-left.<?php echo $class_name; ?> {
					background-color: <?php the_sub_field('bg_color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.centered-image-left.<?php echo $class_name; ?> {
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

		<?php elseif( get_row_layout() == 'centered_image_floated_right' ):
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$secondary_title = get_sub_field('secondary_title');
			$secondary_verbiage = get_sub_field('secondary_verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$class_name = get_sub_field('class_name');
			$id = get_sub_field('id');
			$category = get_sub_field('category');
			$action = get_sub_field('action');
			?>
			<div class="centered-image-right <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer">
						<div class="grid-middle">
							<div class="col-7_sm-12">
								<?php if ($title):?>
									<h2><?php echo $title; ?></h2>
								<?php endif; ?>
								<?php if ($verbiage): ?>
									<?php echo $verbiage; ?>
								<?php endif; ?>
								<?php if ($secondary_title): ?>
									<h3 style="margin-top: 30px;"><?php echo $secondary_title; ?></h3>
								<?php endif; ?>
								<?php if ($secondary_verbiage): ?>
									<?php echo $secondary_verbiage; ?>
								<?php endif; ?>
								<?php if ($button_link): ?>
                                    <p class="button-link"><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
								<?php endif; ?>
							</div>
							<div class="col_5_sm-first">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="standout">
							</div>
						</div>
					</div>
				</div>
			</div>
			<style>
				.centered-image-right.<?php echo $class_name; ?> .spacer {
					padding:<?php echo $desktop_padding; ?>;
				}
				@media (max-width: 1025px) {
					.centered-image-right.<?php echo $class_name; ?> .spacer {
						padding:<?php echo $mobile_padding; ?>;
					}
				}
				.centered-image-right.<?php echo $class_name; ?> {
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.centered-image-right.<?php echo $class_name; ?> {
					background-color: <?php the_sub_field('bg_color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.centered-image-right.<?php echo $class_name; ?> {
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

		<?php elseif( get_row_layout() == 'full_width_content' ):
			$verbiage = get_sub_field('verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$class_name = get_sub_field('class_name');
			$id = get_sub_field('id');
			$category = get_sub_field('category');
			$action = get_sub_field('action');
			?>
			<div class="full-width-content <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer">
						<div class="grid">
							<div class="col-12">
								<?php if ($verbiage): ?>
									<?php echo $verbiage; ?>
								<?php endif; ?>
								<?php if ($button_link): ?>
                                    <p class="button-link"><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<style>
				.full-width-content.<?php echo $class_name; ?> .spacer {
					padding:<?php echo $desktop_padding; ?>;
				}
				@media (max-width: 1025px) {
					.full-width-content.<?php echo $class_name; ?> .spacer {
						padding:<?php echo $mobile_padding; ?>;
					}
				}
				.full-width-content.<?php echo $class_name; ?> {
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.full-width-content.<?php echo $class_name; ?> {
					background-color: <?php the_sub_field('bg_color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.full-width-content.<?php echo $class_name; ?> {
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

		<?php elseif( get_row_layout() == 'two_column_infographic' ):
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$image_left = get_sub_field('image_left');
			$image_right = get_sub_field('image_right');
			$class_name = get_sub_field('class_name');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			?>
			<div class="infographic <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer">
						<?php if ($title): ?>
							<h2><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php if ($verbiage): ?>
							<div style="margin-bottom: 50px;"><?php echo $verbiage; ?></div>
						<?php endif; ?>
						<div class="grid">
							<div class="col-6_sm-12">
								<img src="<?php echo $image_left; ?>">
							</div>
							<div class="col-6_sm-12">
								<img src="<?php echo $image_right; ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			<style>
				.infographic.<?php echo $class_name; ?> .spacer {
					padding:<?php echo $desktop_padding; ?>;
				}
				@media (max-width: 1025px) {
					.infographic.<?php echo $class_name; ?> .spacer {
						padding:<?php echo $mobile_padding; ?>;
					}
				}
				.infographic.<?php echo $class_name; ?> {
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.infographic.<?php echo $class_name; ?> {
					background-color: <?php the_sub_field('bg_color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.infographic.<?php echo $class_name; ?> {
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

		<?php elseif( get_row_layout() == 'apps_section' ):
			$mobile_padding = get_sub_field('mobile_padding');
			$desktop_padding = get_sub_field('desktop_padding');
			$class_name = get_sub_field('class_name');
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$id2 = get_sub_field('id');
			$category2 = get_sub_field('category');
			$action2 = get_sub_field('action');
			?>
            <div class="apps <?php echo $class_name; ?>">
                <div class="wrapper">
                    <div class="spacer outter">
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
											$id = get_sub_field('id');
											$category = get_sub_field('category');
											$action = get_sub_field('action');
											?>
                                            <div class="col-6_sm-12">
                                                <img src="<?php echo $image_app['url']; ?>" alt="<?php echo $image_app['alt']; ?>">
                                                <h3>
													<?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
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
                            <p class="button-link"><a id="<?php echo $id2; ?>" data-event-category="<?php echo $category2; ?>"  data-event-action="<?php echo $action2 ?>" href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <style>
                .apps.<?php echo $class_name; ?> .spacer.outter {
                    padding:<?php echo $desktop_padding; ?>;
                }
                @media (max-width: 1025px) {
                    .apps.<?php echo $class_name; ?> .spacer.outter {
                        padding:<?php echo $mobile_padding; ?>;
                    }
                }
                .apps.<?php echo $class_name; ?> {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                .apps.<?php echo $class_name; ?> .grid a {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                <?php if( get_sub_field('background') == 'Color' ): ?>
                .apps.<?php echo $class_name; ?> {
                    background-color: <?php the_sub_field('bg_color');?>;
                }
                <?php endif; ?>
                <?php if( get_sub_field('background') == 'Image' ): ?>
                .apps.<?php echo $class_name; ?> {
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




		<?php elseif( get_row_layout() == 'testimonial_section' ):
			$mobile_padding = get_sub_field('mobile_padding');
			$desktop_padding = get_sub_field('desktop_padding');
			$title_test = get_sub_field('title_test');
			$tagline_test = get_sub_field('tagline_test');
			$class_name = get_sub_field('class_name');
			?>
			<div class="testimonials <?php echo $class_name; ?>">
				<div class="wrapper">
					<div class="spacer outter">
						<?php if( $title_test ): ?><h2><?php echo $title_test; ?></h2><?php endif; ?>
						<?php if( $tagline_test ): ?><p class="tagline"><?php echo $tagline_test; ?></p><?php endif; ?>
						<?php if( have_rows('testimonials') ): ?>
							<div class="spacer inner">
								<div class="grid">
									<?php while( have_rows('testimonials') ): the_row();
										$name = get_sub_field('name');
										$test = get_sub_field('testimonial');
										$image = get_sub_field('image')
										?>
										<div class="col-4_sm-12">
											<img src="<?php echo $image; ?>">
											<p class="name"><?php echo $name; ?></p>
											<p class="verbiage"><?php echo $test; ?></p>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<style>
					.testimonials.<?php echo $class_name; ?> .spacer.outter {
						padding:<?php echo $desktop_padding; ?>;
					}
					@media (max-width: 1025px) {
						.testimonials.<?php echo $class_name; ?> .spacer.outter {
							padding:<?php echo $mobile_padding; ?>;
						}
					}
					.testimonials.<?php echo $class_name; ?> {
						color: <?php the_sub_field('font_color'); ?>;
					}
					<?php if( get_sub_field('background') == 'Color' ): ?>
					.testimonials.<?php echo $class_name; ?> {
						background-color: <?php the_sub_field('bg_color');?>;
					}
					<?php endif; ?>
					<?php if( get_sub_field('background') == 'Image' ): ?>
					.testimonials.<?php echo $class_name; ?> {
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
			</div>

		<?php elseif( get_row_layout() == 'two_column_ctas' ):
            $title = get_sub_field('title');
            $verbiage = get_sub_field('verbiage');
            $button_text= get_sub_field('button_text');
            $button_link=get_sub_field('button_link');
			$class_name = get_sub_field('class_name');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$id1 = get_sub_field('id');
			$category1 = get_sub_field('category');
			$action1 = get_sub_field('action');
            ?>
            <div class="savings <?php echo $class_name; ?>">
                <div class="wrapper">
                    <div class="spacer outter">
						<?php if ($title):?>
                            <h2><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php if ($verbiage):?>
                            <p class="tagline"><?php echo $verbiage; ?></p>
						<?php endif; ?>
						<?php if( have_rows('cta') ): ?>
                            <div class="spacer inner">
                                <div class="grid">
	                                <?php while( have_rows('cta') ): the_row();
		                                $image = get_sub_field('image');
		                                $title = get_sub_field('title');
		                                $verbiage = get_sub_field('verbiage');
		                                $link = get_sub_field('link');
		                                $id = get_sub_field('id');
		                                $category = get_sub_field('category');
		                                $action = get_sub_field('action');
		                                ?>
                                        <div class="col-6_sm-12 grid-middle">
                                            <div class="col-4_md-12">
				                                <?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
                                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
					                                <?php if ($link): ?></a><?php endif; ?>
                                            </div>
                                            <div class="col-8_md-12">
                                                <h3>
					                                <?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
						                                <?php echo $title; ?>
						                                <?php if ($link): ?></a><?php endif; ?>
                                                </h3>
                                                <p><?php echo $verbiage; ?> </p>
                                            </div>
                                        </div>
	                                <?php endwhile; ?>
                                </div>
                            </div>
						<?php endif; ?>
	                    <?php if ($button_link): ?>
                            <p class="button-link"><a id="<?php echo $id1; ?>" data-event-category="<?php echo $category1; ?>"  data-event-action="<?php echo $action1; ?>"  href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
	                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <style>
                .savings .grid a {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                .savings .spacer.outter {
                    padding:<?php echo $desktop_padding; ?>;
                }
                @media (max-width: 1025px) {
                    .savings .spacer.outter {
                        padding:<?php echo $mobile_padding; ?>;
                    }
                }
                .savings {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                <?php if( get_sub_field('background') == 'Color' ): ?>
                .savings {
                    background-color: <?php the_sub_field('color');?>;
                }
                <?php endif; ?>
                <?php if( get_sub_field('background') == 'Image' ): ?>
                .savings {
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
            </style><?php elseif( get_row_layout() == 'two_column_ctas' ):
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_text= get_sub_field('button_text');
			$button_link=get_sub_field('button_link');
			$class_name = get_sub_field('class_name');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$id1 = get_sub_field('id');
			$category1 = get_sub_field('category');
			$action1 = get_sub_field('action');
			?>
            <div class="savings <?php echo $class_name; ?>">
                <div class="wrapper">
                    <div class="spacer outter">
						<?php if ($title):?>
                            <h2><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php if ($verbiage):?>
                            <p class="tagline"><?php echo $verbiage; ?></p>
						<?php endif; ?>
						<?php if( have_rows('cta') ): ?>
                            <div class="spacer inner">
                                <div class="grid">
									<?php while( have_rows('cta') ): the_row();
										$image = get_sub_field('image');
										$title = get_sub_field('title');
										$verbiage = get_sub_field('verbiage');
										$link = get_sub_field('link');
										$id = get_sub_field('id');
										$category = get_sub_field('category');
										$action = get_sub_field('action');
										?>
                                        <div class="col-6_sm-12 grid-middle">
                                            <div class="col-4_md-12">
												<?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
                                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
													<?php if ($link): ?></a><?php endif; ?>
                                            </div>
                                            <div class="col-8_md-12">
	                                            <?php if ($title): ?>
                                                    <h3>
			                                            <?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
				                                            <?php echo $title; ?>
				                                            <?php if ($link): ?></a><?php endif; ?>
                                                    </h3>
	                                            <?php endif; ?>
                                                <p><?php echo $verbiage; ?> </p>
                                            </div>
                                        </div>
									<?php endwhile; ?>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ($button_link): ?>
                            <p class="button-link"><a id="<?php echo $id1; ?>" data-event-category="<?php echo $category1; ?>"  data-event-action="<?php echo $action1; ?>"  href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <style>
                .savings .grid a {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                .savings .spacer.outter {
                    padding:<?php echo $desktop_padding; ?>;
                }
                @media (max-width: 1025px) {
                    .savings .spacer.outter {
                        padding:<?php echo $mobile_padding; ?>;
                    }
                }
                .savings {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                <?php if( get_sub_field('background') == 'Color' ): ?>
                .savings {
                    background-color: <?php the_sub_field('color');?>;
                }
                <?php endif; ?>
                <?php if( get_sub_field('background') == 'Image' ): ?>
                .savings {
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

		<?php elseif( get_row_layout() == 'three_columns' ):
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_text= get_sub_field('button_text');
			$button_link=get_sub_field('button_link');
			$class_name = get_sub_field('class_name');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$id1 = get_sub_field('id');
			$category1 = get_sub_field('category');
			$action1 = get_sub_field('action');
			?>
            <div class="three-col <?php echo $class_name; ?>">
                <div class="wrapper">
                    <div class="spacer outter">
						<?php if ($title):?>
                            <h2><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php if ($verbiage):?>
                            <p class="tagline"><?php echo $verbiage; ?></p>
						<?php endif; ?>
						<?php if( have_rows('cta') ): ?>
                            <div class="spacer inner">
                                <div class="grid">
									<?php while( have_rows('cta') ): the_row();
										$image = get_sub_field('image');
										$title = get_sub_field('title');
										$verbiage = get_sub_field('verbiage');
										$link = get_sub_field('link');
										$id = get_sub_field('id');
										$category = get_sub_field('category');
										$action = get_sub_field('action');
										?>
                                        <div class="col-4_sm-12 bottom-space">
												<?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
		                                        <?php if ($image):?> <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"><?php endif; ?>
													<?php if ($link): ?></a><?php endif; ?>
                                            <?php if ($title): ?>
                                                <h3 <?php if( get_sub_field('center_title') == 'Yes' ): ?>style="text-align: center;"<?php endif ;?>>
													<?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
                                                        <?php echo $title; ?>
                                                    <?php if ($link): ?></a><?php endif; ?>
                                                </h3>
                                            <?php endif; ?>
                                                <?php if ($verbiage): ?><?php echo $verbiage; ?> <?php endif; ?>
                                        </div>
									<?php endwhile; ?>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ($button_link): ?>
                            <p class="button-link"><a id="<?php echo $id1; ?>" data-event-category="<?php echo $category1; ?>"  data-event-action="<?php echo $action1; ?>"  href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <style>
                .three-col .grid-middle h3 a {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                .three-col .spacer.outter {
                    padding:<?php echo $desktop_padding; ?>;
                }
                @media (max-width: 1025px) {
                    .three-col .spacer.outter {
                        padding:<?php echo $mobile_padding; ?>;
                    }
                }
                .three-col {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                <?php if( get_sub_field('background') == 'Color' ): ?>
                .three-col {
                    background-color: <?php the_sub_field('color');?>;
                }
                <?php endif; ?>
                <?php if( get_sub_field('background') == 'Image' ): ?>
                .three-col {
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

		<?php elseif( get_row_layout() == 'four_columns' ):
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_text= get_sub_field('button_text');
			$button_link=get_sub_field('button_link');
			$class_name = get_sub_field('class_name');
			$desktop_padding = get_sub_field('desktop_padding');
			$mobile_padding = get_sub_field('mobile_padding');
			$id1 = get_sub_field('id');
			$category1 = get_sub_field('category');
			$action1 = get_sub_field('action');
			?>
            <div class="four-col <?php echo $class_name; ?>">
                <div class="wrapper">
                    <div class="spacer outter">
						<?php if ($title):?>
                            <h2><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php if ($verbiage):?>
                            <p class="tagline"><?php echo $verbiage; ?></p>
						<?php endif; ?>
						<?php if( have_rows('cta') ): ?>
                            <div class="spacer inner">
                                <div class="grid">
									<?php while( have_rows('cta') ): the_row();
										$image = get_sub_field('image');
										$title = get_sub_field('title');
										$verbiage = get_sub_field('verbiage');
										$link = get_sub_field('link');
										$id = get_sub_field('id');
										$category = get_sub_field('category');
										$action = get_sub_field('action');
										?>
                                        <div class="col-3_sm-12_md-6 bottom-space">
											<?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
                                               <?php if ($image):?> <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"><?php endif; ?>
												<?php if ($link): ?></a><?php endif; ?>
	                                        <?php if ($title): ?>
                                                <h3 <?php if( get_sub_field('center_title') == 'Yes' ): ?>style="text-align: center;"<?php endif ;?>>
			                                        <?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
				                                        <?php echo $title; ?>
				                                        <?php if ($link): ?></a><?php endif; ?>
                                                </h3>
	                                        <?php endif; ?>
	                                        <?php if ($verbiage): ?><?php echo $verbiage; ?> <?php endif; ?>
                                        </div>
									<?php endwhile; ?>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ($button_link): ?>
                            <p class="button-link"><a id="<?php echo $id1; ?>" data-event-category="<?php echo $category1; ?>"  data-event-action="<?php echo $action1; ?>"  href="<?php echo $button_link; ?>" class="button-yellow"  <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <style>
                .four-col .grid-middle h3 a {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                .four-col .spacer.outter {
                    padding:<?php echo $desktop_padding; ?>;
                }
                @media (max-width: 1025px) {
                    .three-col .spacer.outter {
                        padding:<?php echo $mobile_padding; ?>;
                    }
                }
                .four-col {
                    color: <?php the_sub_field('font_color'); ?>;
                }
                <?php if( get_sub_field('background') == 'Color' ): ?>
                .four-col {
                    background-color: <?php the_sub_field('color');?>;
                }
                <?php endif; ?>
                <?php if( get_sub_field('background') == 'Image' ): ?>
                .four-col {
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

		<?php endif;?>
	<?php endwhile; ?>
<?php else : ?>
<?php endif;?>