
<?php
while ( have_posts() ) : the_post(); ?>
	<?php if( have_rows('section_one') ):
		while( have_rows('section_one') ): the_row();
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$secondary_title = get_sub_field('secondary_title');
			$secondary_verbiage = get_sub_field('secondary_verbiage');
			$button_text = get_sub_field('button_text');
			$button_link = get_sub_field('button_link');
			$id = get_sub_field('id');
			$category = get_sub_field('category');
			$action = get_sub_field('action');
			?>
			<div class="what-you-get">
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
								<p class="button-link"><a href="<?php echo $button_link; ?>" class="button-yellow" id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
							<?php endif; ?>
						</div>
						<div class="clear"></div>

					</div>
				</div>
			</div>
			<style>
				.what-you-get {
					color: <?php the_sub_field('font_color'); ?>;
				}
				<?php if( get_sub_field('background') == 'Color' ): ?>
				.what-you-get {
					background-color: <?php the_sub_field('color');?>;
				}
				<?php endif; ?>
				<?php if( get_sub_field('background') == 'Image' ): ?>
				.what-you-get {
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


	<?php if( have_rows('section_two') ):
		while( have_rows('section_two') ): the_row();
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_text= get_sub_field('button_text');
			$button_link=get_sub_field('button_link');
			$id1 = get_sub_field('id_1');
			$category1 = get_sub_field('category_1');
			$action1 = get_sub_field('action_1');
			?>
			<div class="savings">
				<div class="wrapper">
					<div class="spacer">
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
                                                    <?php if ($link): ?><a id="<?php echo $id; ?>" data-event-category="<?php echo $category; ?>"  data-event-action="<?php echo $action; ?>"  href="<?php echo $link;?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php endif; ?>
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
                            <p class="button-link"><a href="<?php echo $button_link; ?>" class="button-yellow" id="<?php echo $id1; ?>" data-event-category="<?php echo $category1; ?>"  data-event-action="<?php echo $action1; ?>" <?php if( get_sub_field('open_link_in_new_tab') == 'Yes' ): ?>target="_blank"<?php endif ;?>><?php echo $button_text; ?></a></p>
                        <?php endif; ?>
					</div>
				</div>
			</div>
			<style>
				.savings {
					color: <?php the_sub_field('font_color'); ?>;
				}
                .savings .grid a {
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
		<?php endwhile; ?>
	<?php endif; ?>


	<?php if( have_rows('apps_section_one') ):
		while( have_rows('apps_section_one') ): the_row();
			$image = get_sub_field('image');
			$title = get_sub_field('title');
			$verbiage = get_sub_field('verbiage');
			$button_link = get_sub_field('button_link');
			$button_text = get_sub_field('button_text');
			$id2 = get_sub_field('id');
			$category2 = get_sub_field('category');
			$action2 = get_sub_field('action');
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


	<?php
	$title_test = get_field('title_test');
	$tagline_test = get_field('tagline_test');
	?>
	<div class="testimonials">
		<div class="wrapper">
			<div class="spacer">
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
			.testimonials {
				color: <?php the_field('font_color'); ?>;
			}
			<?php if( get_field('background') == 'Color' ): ?>
			.testimonials {
				background-color: <?php the_field('bg_color');?>;
			}
			<?php endif; ?>
			<?php if( get_field('background') == 'Image' ): ?>
			.testimonials {
				background-image: url(<?php the_field('bg_image');?>);
			<?php if(get_field('bg_repeat_or_cover') == 'Cover'): ?>
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center;
			<?php endif; ?>
			<?php if(get_field('bg_repeat_or_cover') == 'Repeat'): ?>
				background-repeat: repeat;
			<?php endif; ?>
			}
			<?php endif; ?>
		</style>
	</div>


<?php endwhile; // End of the loop.
?>