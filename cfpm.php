<?php 
class FDL_ET_Builder_Module_Fullwidth_Portfolio extends ET_Builder_Module {
	function init() {
		$this->name       = __( 'Fullwidth Portfolio', 'et_builder' );
		$this->slug       = 'et_pb_fullwidth_portfolio';
		$this->fullwidth  = true;

		$this->whitelisted_fields = array(
			'title',
			'fullwidth',
			'include_categories',
			'posts_number',
			'show_title',
			'show_date',
			'background_layout',
			'auto',
			'auto_speed',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'fullwidth'         => array( 'on' ),
			'show_title'        => array( 'on' ),
			'show_date'         => array( 'on' ),
			'background_layout' => array( 'light' ),
			'auto'              => array( 'off' ),
			'auto_speed'        => array( '7000' ),
		);
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => __( 'Portfolio Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Title displayed above the portfolio.', 'et_builder' ),
			),
			'fullwidth' => array(
				'label'             => __( 'Layout', 'et_builder' ),
				'type'              => 'select',
				'options'           => array(
					'on'  => __( 'Carousel', 'et_builder' ),
					'off' => __( 'Grid', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_auto',
				),
				'description'        => __( 'Choose your desired portfolio layout style.', 'et_builder' ),
			),
			'include_categories' => array(
				'label'           => __( 'Include Categories', 'et_builder' ),
				'renderer'        => 'et_builder_include_categories_option',
				'description'     => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
			),
			'posts_number' => array(
				'label'           => __( 'Posts Number', 'et_builder' ),
				'type'            => 'text',
				'description'     => __( 'Control how many projects are displayed. Leave blank or use 0 to not limit the amount.', 'et_builder' ),
			),
			'show_title' => array(
				'label'             => __( 'Show Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn project titles on or off.', 'et_builder' ),
			),
			'show_date' => array(
				'label'             => __( 'Show Date', 'et_builder' ),
				'type'              => 'yes_no_button',
				'options'           => array(
					'on'  => __( 'Yes', 'et_builder' ),
					'off' => __( 'No', 'et_builder' ),
				),
				'description'        => __( 'Turn the date display on or off.', 'et_builder' ),
			),
			'background_layout' => array(
				'label'             => __( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'options'           => array(
					'light'  => __( 'Dark', 'et_builder' ),
					'dark' => __( 'Light', 'et_builder' ),
				),
				'description'        => __( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'auto' => array(
				'label'             => __( 'Automatic Carousel Rotation', 'et_builder' ),
				'type'              => 'yes_no_button',
				'options'           => array(
					'off'  => __( 'Off', 'et_builder' ),
					'on' => __( 'On', 'et_builder' ),
				),
				'affects'           => array(
					'#et_pb_auto_speed',
				),
				'depends_show_if' => 'on',
				'description'        => __( 'If you the carousel layout option is chosen and you would like the carousel to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
			),
			'auto_speed' => array(
				'label'             => __( 'Automatic Carousel Rotation Speed (in ms)', 'et_builder' ),
				'type'              => 'text',
				'depends_default'   => true,
				'description'       => __( "Here you can designate how fast the carousel rotates, if 'Automatic Carousel Rotation' option is enabled above. The higher the number the longer the pause between each rotation. (Ex. 1000 = 1 sec)", 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => __( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => __( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
			),
			'module_class' => array(
				'label'           => __( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$title              = $this->shortcode_atts['title'];
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$fullwidth          = $this->shortcode_atts['fullwidth'];
		$include_categories = $this->shortcode_atts['include_categories'];
		$posts_number       = $this->shortcode_atts['posts_number'];
		$show_title         = $this->shortcode_atts['show_title'];
		$show_date          = $this->shortcode_atts['show_date'];
		$background_layout  = $this->shortcode_atts['background_layout'];
		$auto               = $this->shortcode_atts['auto'];
		$auto_speed         = $this->shortcode_atts['auto_speed'];

		$args = array();
		if ( is_numeric( $posts_number ) && $posts_number > 0 ) {
			$args['posts_per_page'] = $posts_number;
		} else {
			$args['nopaging'] = true;
		}

		if ( '' !== $include_categories ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => explode( ',', $include_categories ),
					'operator' => 'IN'
				)
			);
		}

		$projects = et_divi_get_projects( $args );

		ob_start();
		if( $projects->post_count > 0 ) {
			while ( $projects->have_posts() ) {
				$projects->the_post();
				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_portfolio_item et_pb_grid_item ch-grid' ); ?>>
				<?php
					$thumb = '';
				
					$width = 320;
					$width = (int) apply_filters( 'et_pb_portfolio_image_width', $width );

					$height = 320;
					$height = (int) apply_filters( 'et_pb_portfolio_image_height', $height );

					list($thumb_src, $thumb_width, $thumb_height) = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), array( $width, $height ) );

					$orientation = ( $thumb_height > $thumb_width ) ? 'portrait' : 'landscape';

					if ( '' !== $thumb_src ) : ?>
						<div style="background-image:url(<?php echo esc_attr( $thumb_src ); ?>);" class="et_pb_portfolio_image ch-item <?php echo esc_attr( $orientation ); ?>">
						<div class="ch-info-wrap">
							<a href="<?php the_permalink(); ?>">
								<div class="ch-info">
								<div style="background-image:url(<?php echo esc_attr( $thumb_src ); ?>);" class="ch-info-front"></div>
									<div class="ch-info-back">
									<?php if ( 'on' === $show_title ) : ?>
										<h3><?php the_title(); ?></h3>
									<?php endif; ?>

									<?php if ( 'on' === $show_date ) : ?>
										<p class="post-meta"><?php echo get_the_date(); ?></p>
									<?php endif; ?>
									</div>
								</div>
							</a>
						</div>
						</div>
				<?php endif; ?>
				</div>
				<?php
			}
		}

		wp_reset_postdata();

		$posts = ob_get_clean();

		$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

		$output = sprintf(
			'<div%4$s class="et_pb_fullwidth_portfolio %1$s%3$s%5$s" data-auto-rotate="%6$s" data-auto-rotate-speed="%7$s">
				%8$s
				<div class="et_pb_portfolio_items clearfix" data-portfolio-columns="">
					%2$s
				</div><!-- .et_pb_portfolio_items -->
			</div> <!-- .et_pb_fullwidth_portfolio -->',
			( 'on' === $fullwidth ? 'et_pb_fullwidth_portfolio_carousel' : 'et_pb_fullwidth_portfolio_grid clearfix' ),
			$posts,
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $auto && in_array( $auto, array('on', 'off') ) ? esc_attr( $auto ) : 'off' ),
			( '' !== $auto_speed && is_numeric( $auto_speed ) ? esc_attr( $auto_speed ) : '7000' ),
			( '' !== $title ? sprintf( '<h2>%s</h2>', esc_html( $title ) ) : '' )
		);

		return $output;
	}
}
