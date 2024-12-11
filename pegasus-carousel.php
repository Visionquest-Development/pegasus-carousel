<?php
/*
Plugin Name: Pegasus Carousel Plugin
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: This allows you to create carousels on your website with just a shortcode.
Version:     1.0
Author:      Jim O'Brien
Author URI:  https://visionquestdevelopment.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

	/**
	 * Silence is golden; exit if accessed directly
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	global $theme;

	$theme = wp_get_theme();

	$theme_template = $theme->get_template();
	$theme_stylesheet = $theme->get_stylesheet();
	$theme_template_dir = $theme->get_template_directory_uri();
	$theme_stylesheet_dir = $theme->get_stylesheet_directory_uri();

	$results_array = array(
		'theme' => $theme,
		'theme_template' => $theme_template,
		'theme_stylesheet' => $theme_stylesheet,
		'theme_template_dir' => $theme_template_dir,
		'theme_stylesheet_dir' => $theme_stylesheet_dir
	);

	// echo "<pre>";
	// var_dump($results_array);
	// echo "</pre>";

	// function pegasus_carousel_submenu_item() {
	// 	//add_menu_page("Carousel", "Carousel", "manage_options", "pegasus_carousel_plugin_options", "pegasus_carousel_plugin_settings_page", null, 99);

	// 	//string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = ''
	// 	//add_submenu_page( "pegasus_options", "Carousel Usage", "Carousel Usage", "manage_options", "pegasus_carousel_plugin_shortcode_options", "pegasus_carousel_plugin_shortcode_settings_page" );
	// }

	function pegasus_carousel_menu_item() {
		add_menu_page( "Carousel", "Carousel", "manage_options", "pegasus_carousel_plugin_options", "pegasus_carousel_plugin_settings_page", null, 82 );
		add_submenu_page( "pegasus_carousel_plugin_options", "Shortcode Usage", "Usage", "manage_options", "pegasus_carousel_plugin_shortcode_options", "pegasus_carousel_plugin_shortcode_settings_page" );
	}

	//pegasus_carousel_plugin_settings_page

	if( 'pegasus' === $theme_template || 'pegasus' === $theme_stylesheet || 'pegasus-child' === $theme_template || 'pegasus-child' === $theme_stylesheet ) {
		//do nothing
		//add_action("admin_menu", "pegasus_carousel_submenu_item");
	} else {
		//stuff
		add_action("admin_menu", "pegasus_carousel_menu_item");
	}

	//add_action("admin_menu", "pegasus_carousel_menu_item");

	function pegasus_carousel_plugin_settings_page() { ?>
	    <div class="wrap">
	    <h1>Carousel</h1>

		<form method="post" action="options.php">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");
	            submit_button();
	        ?>
	    </form>

		</div>
	<?php
	}

	function pegasus_carousel_plugin_shortcode_settings_page() { ?>
		<div class="wrap pegasus-wrap">
			<h1>Shortcode Usage</h1>

			<div>
				<h3>Logo Slider Usage 1:</h3>
				<style>
					pre {
						background-color: #f9f9f9;
						border: 1px solid #aaa;
						page-break-inside: avoid;
						font-family: monospace;
						font-size: 15px;
						line-height: 1.6;
						margin-bottom: 1.6em;
						max-width: 100%;
						overflow: auto;
						padding: 1em 1.5em;
						display: block;
						word-wrap: break-word;
					}

					input[type="text"].code {
						width: 100%;
					}
				</style>
				<pre >[logo_slider the_query="post_type=logo_slider&showposts=100&order_by=title&order=ASC"]</pre>

				<input
					type="text"
					readonly
					value="<?php echo esc_html('[logo_slider the_query="post_type=logo_slider&showposts=100&order_by=title&order=ASC"]'); ?>"
					class="regular-text code"
					id="my-shortcode"
					onClick="this.select();"
				>
			</div>

			<div>
				<h3>Testimonial Usage 1:</h3>
				<style>
					pre {
						background-color: #f9f9f9;
						border: 1px solid #aaa;
						page-break-inside: avoid;
						font-family: monospace;
						font-size: 15px;
						line-height: 1.6;
						margin-bottom: 1.6em;
						max-width: 100%;
						overflow: auto;
						padding: 1em 1.5em;
						display: block;
						word-wrap: break-word;
					}

					input[type="text"].code {
						width: 100%;
					}
				</style>
				<pre >[testimonial_slider image="circle" type="bubble" class="test" the_query="post_type=testimonial&showposts=100" ]</pre>

				<input
					type="text"
					readonly
					value="<?php echo esc_html('[testimonial_slider image="circle" type="bubble" class="test" the_query="post_type=testimonial&showposts=100" ]'); ?>"
					class="regular-text code"
					id="my-shortcode"
					onClick="this.select();"
				>
			</div>

			<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>

		</div>
		<?php
	}

	function enable_logo_slider_cpt() { ?>
		<input name="chk_slider_cpt" type="checkbox" value="1" <?php checked(1, get_option('chk_slider_cpt'), true); ?> />

		<?php
	}


	function enable_testimonial_cpt() { ?>
		<input name="chk_testimonial_cpt" type="checkbox" value="1" <?php checked(1, get_option('chk_testimonial_cpt'), true); ?> />

		<?php
	}

	function display_carousel_plugin_panel_fields() {
		add_settings_section("section", "Shortcode Settings", null, "theme-options");

		add_settings_field("chk_slider_cpt", "Enable Logo Slider Custom Post Type", "enable_logo_slider_cpt", "theme-options", "section");
		add_settings_field("chk_testimonial_cpt", "Enable Testimonial Custom Post Type", "enable_testimonial_cpt", "theme-options", "section");

		/*================
		REGISTER SETTINGS
		=================*/

		register_setting("section", "chk_slider_cpt");
		register_setting("section", "chk_testimonial_cpt");

	}
	add_action("admin_init", "display_carousel_plugin_panel_fields");

	function pegasus_carousel_plugin_styles() {

		//wp_enqueue_style( 'slick-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/slick.css', array(), null, 'all' );
		//wp_enqueue_style( 'slick-theme-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/slick-theme.css', array(), null, 'all' );
		wp_register_style( 'slick-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/slick.css', array(), null, 'all' );
		wp_register_style( 'slick-theme-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/slick-theme.css', array(), null, 'all' );

	}
	add_action( 'wp_enqueue_scripts', 'pegasus_carousel_plugin_styles' );

	/**
	* Proper way to enqueue JS
	*/
	function pegasus_carousel_plugin_js() {

		//wp_enqueue_script( 'slick-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/slick.js', array( 'jquery' ), null, true );
		//wp_enqueue_script( 'match-height-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/matchHeight.js', array( 'jquery' ), null, true );
		//wp_enqueue_script( 'pegasus-carousel-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, true );
		wp_register_script( 'slick-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/slick.js', array( 'jquery' ), null, 'all' );
		wp_register_script( 'match-height-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/matchHeight.js', array( 'jquery' ), null, 'all' );
		wp_register_script( 'pegasus-carousel-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, 'all' );

	} //end function
	add_action( 'wp_enqueue_scripts', 'pegasus_carousel_plugin_js' );



		/* ===============================================================================================
		============================ CUSTOM POST TYPE  ==================================================
		================================================================================================*/
	add_action( 'init', 'pegasus_slider_cpt_init' );
	function pegasus_slider_cpt_init() {

		$enableTestimonialCPT = get_option('chk_testimonial_cpt');
		//echo "<pre>";  var_dump($test); echo "</pre><hr>";

		if( '1' == $enableTestimonialCPT ) {
			/*================================
			========Testimonial Post Type ========
			================================*/

			$review_labels = array(
				'name' => _x('Testimonials', 'post type general name', 'pegasus-bootstrap'),
				'singular_name' => _x('Testimonials', 'post type singular name', 'pegasus-bootstrap'),
				'add_new' => _x('Add New', 'testimonial', 'pegasus-bootstrap'),
				'add_new_item' => __('Add New Testimonials', 'pegasus-bootstrap'),
				'edit_item' => __('Edit Testimonial', 'pegasus-bootstrap'),
				'new_item' => __('New Testimonial', 'pegasus-bootstrap'),
				'view_item' => __('View Testimonials', 'pegasus-bootstrap'),
				'search_items' => __('Search Testimonials', 'pegasus-bootstrap'),
				'not_found' =>  __('No testimonial found', 'pegasus-bootstrap'),
				'not_found_in_trash' => __('No testimonial found in Trash', 'pegasus-bootstrap'),
				'parent_item_colon' => '',
				'menu_name' => 'Testimonial'
			);
			// Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
			$review_args = array(
				'labels' => $review_labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => true,
				/* this is important to make it so that page-portfolio.php will show when used */
				'capability_type' => 'post',
				'can_export' => true,
				 /* make sure has_archive is turned off if you plan on using page-portfolio.php */
				'has_archive' => false,
				'hierarchical' => false,
				'menu_position' => null,
				/* include this line to use global categories */
				//'taxonomies' => array('category'),
				'supports' => array('title','editor','author','thumbnail','excerpt','comments','custom-fields','page-attributes')
			);

			// We call this function to register the custom post type
			register_post_type('testimonial',$review_args);
		} //testimonial cpt check

		$enableSliderCPT = get_option('chk_slider_cpt');
		//echo "<pre>";  var_dump($test); echo "</pre><hr>";

		if( '1' == $enableSliderCPT ) {
			/*============================
			======= Logo Slider Post Type ========
			============================*/

			$logo_slider_labels = array(
				'name' => _x('Logos', 'logo slider general name', 'pegasus-bootstrap'),
				'singular_name' => _x('Logo', 'logo slider singular name', 'pegasus-bootstrap'),
				'add_new' => _x('Add New', 'logo', 'pegasus-bootstrap'),
				'add_new_item' => __('Add New Logo', 'pegasus-bootstrap'),
				'edit_item' => __('Edit Logo', 'pegasus-bootstrap'),
				'new_item' => __('New Logo', 'pegasus-bootstrap'),
				'view_item' => __('View Logo', 'pegasus-bootstrap'),
				'search_items' => __('Search Logo', 'pegasus-bootstrap'),
				'not_found' =>  __('No logo found', 'pegasus-bootstrap'),
				'not_found_in_trash' => __('No logo found in Trash', 'pegasus-bootstrap'),
				'parent_item_colon' => '',
				'menu_name' => 'Logo Slider'
			);

			// Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
			$logo_slider_args = array(
				'labels' => $logo_slider_labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => true,
				/* this is important to make it so that page-portfolio.php will show when used */
				'capability_type' => 'post',
				'can_export' => true,
				 /* make sure has_archive is turned off if you plan on using page-portfolio.php */
				'has_archive' => false,
				'hierarchical' => true,
				'menu_position' => null,
				/* include this line to use global categories */
				//'taxonomies' => array('category'),
				'supports' => array('title','editor','author','thumbnail','excerpt','comments','custom-fields','page-attributes')
			);

			// We call this function to register the custom post type
			register_post_type('logo_slider',$logo_slider_args);


		} //logo slider cpt check


	} //end cpt init


	function my_rewrite_flush() {
		flush_rewrite_rules();
	}
	add_action( 'after_switch_theme', 'my_rewrite_flush' );

	/* fixes permalinks for custom post types */
	add_action('init', 'my_rewrite');
	function my_rewrite() {
		global $wp_rewrite;
		$wp_rewrite->add_permastruct('typename', 'typename/%year%/%postname%/', true, 1);
		add_rewrite_rule('typename/([0-9]{4})/(.+)/?$', 'index.php?typename=$matches[2]', 'top');
		$wp_rewrite->flush_rules(); // !!!
	}



	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~META BOXES~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/*--- Demo URL meta box ---*/

	add_action('admin_init','testimonial_meta_init');

	function testimonial_meta_init() {
		// add a meta box for WordPress 'project' type
		//add_meta_box('testimonial_meta', 'Testimonial Options', 'testimonial_meta_setup', 'testimonial', 'side', 'high');
		add_meta_box('testimonial_meta', 'Testimonial Options', 'testimonial_meta_setup', 'testimonial', 'normal', 'high');

		// add a callback function to save any data a user enters in
		add_action('save_post','testimonial_meta_save');
	}

	function testimonial_meta_setup() {
		global $post;

		?>
			<div class="testimonial_meta_control">
				<label for="_position">Position</label>
				<p>
					<input type="text" name="_position" value="<?php echo get_post_meta($post->ID,'_position',TRUE); ?>" style="width: 100%;" />
				</p>
				<label for="_company_name">Company Name</label>
				<p>
					<input type="text" name="_company_name" value="<?php echo get_post_meta($post->ID,'_company_name',TRUE); ?>" style="width: 100%;" />
				</p>
				<label for="_company_url">Company URL</label>
				<p>
					<input type="text" name="_company_url" value="<?php echo get_post_meta($post->ID,'_company_url',TRUE); ?>" style="width: 100%;" />
				</p>
			</div>
		<?php

		// create for validation
		echo '<input type="hidden" name="meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}

	function testimonial_meta_save($post_id) {
		// check nonce
		if (!isset($_POST['meta_noncename']) || !wp_verify_nonce($_POST['meta_noncename'], __FILE__)) {
			return $post_id;
		}

		// check capabilities
		if ('post' == $_POST['post_type']) {
			if (!current_user_can('edit_post', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}

		// exit on autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		if(isset($_POST['_position'])) { update_post_meta($post_id, '_position', $_POST['_position']); } else { delete_post_meta($post_id, '_position'); }
		if(isset($_POST['_company_name'])) { update_post_meta($post_id, '_company_name', $_POST['_company_name']); } else { delete_post_meta($post_id, '_company_name'); }
		if(isset($_POST['_company_url'])) { update_post_meta($post_id, '_company_url', $_POST['_company_url']); } else { delete_post_meta($post_id, '_company_url'); }
	}

	/*--- #end  Demo URL meta box ---*/




	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~SHORTCODES~~~~~~~~~~~~~~~~~~~~~~~~
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/



	/*~~~~~~~~~~~~~~~~~~~~
		LOGO SLIDER
	~~~~~~~~~~~~~~~~~~~~~*/
	// [logo_slider the_query="post_type=logo_slider&showposts=100" ]
	function logo_slider_query_shortcode($atts) {

		$a = shortcode_atts( array(
			//"bkg_color" => ''
		), $atts );

		// Defaults
		extract(shortcode_atts(array(
			"the_query" => '',
			"display_caption" => '',
			"display_img_link" => '',
			"is_external" => ''
		), $atts));

		// de-funkify query
		//$the_query = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $the_query);
		//$the_query = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $the_query);

		if ( '' !== $display_caption ) {
			if( "true" === $display_caption || true === $display_caption ) {
				$display_caption = true;
			}
			if( "false" === $display_caption || false === $display_caption ) {
				$display_caption = false;
			}
		}

		if ( '' !== $display_img_link ) {
			if( "true" === $display_img_link || true === $display_img_link ) {
				$display_img_link = true;
			}
			if( "false" === $display_img_link || false === $display_img_link ) {
				$display_img_link = false;
			}
		}

		if ( '' !== $is_external ) {
			if( "true" === $is_external || true === $is_external ) {
				$is_external = true;
			}
			if( "false" === $is_external || false === $is_external ) {
				$is_external = false;
			}
		}


		$the_query = preg_replace_callback('~&#x0*([0-9a-f]+);~', function($matches){
			return chr( dechex( $matches[1] ) );
		}, $the_query);

		$the_query = preg_replace_callback('~&#0*([0-9]+);~', function($matches){
			return chr( $matches[1] );
		}, $the_query);

		if ( '' === $the_query || null === $the_query || empty( $the_query ) ) {
			$the_query = 'post_type=logo_slider&showposts=100';
		}

		$query_args = array(
			'post_type' => 'logo_slider', // Ensure you are querying the correct post type
			'posts_per_page' => -1, // Set the number of posts to retrieve
			//'post_status' => 'publish', // Ensure only published posts are retrieved
			//'category_name' => 'your-category-slug', // Optional: Filter by category
			//'orderby' => 'date', // Optional: Order by date
			//'order' => 'DESC' // Optional: Order descending
		);

		// echo '<pre>';
		// var_dump( $the_query );
		// echo '</pre>';
		// echo '<pre>';
		// var_dump( $query_args );
		// echo '</pre>';
		// Convert query string into array for WP_Query
		parse_str( $the_query, $query_args );
		// echo '<pre>';
		// var_dump( $query_args );
		// echo '</pre>';
		// Create a new WP_Query instance
		$query = new WP_Query( $query_args );

		// echo '<pre>';
		// var_dump( $query->posts );
		// echo '</pre>';

		// Reset and setup variables
		global $post;
		$output = '';
		$temp_title = '';
		$temp_link = '';
		$temp_date = '';
		$temp_pic = '';
		$temp_content = '';
		$the_id = '';

		// the loop
		//if (have_posts()) : while (have_posts()) : the_post();
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				// $temp_title = get_the_title( $post->ID );
				// $temp_link = get_permalink( $post->ID );
				// //$temp_date = get_the_date($post->ID);
				// $temp_pic = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				// //$temp_excerpt = wp_trim_words( get_the_excerpt(), 150 );
				// //$temp_content = wp_trim_words( get_the_content(), 300 );
				// $the_id = get_the_ID();

				$post_id    = get_the_ID();
				$post_title = get_the_title();
				$post_link  = get_permalink();
				$post_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'medium') : plugin_dir_url(__FILE__) . '/images/not-available.png';
				$categories = get_the_category();


				// output all findings - CUSTOMIZE TO YOUR LIKING
				$output .= "<article class='post-$post_id' >";

					$output .= '<div class="slick-slider-item">';
					if( true === $display_img_link ) {
						if ( true === $is_external ) {
							$output .= '<a class="" target="_blank" href="' . $post_link . '">';
						} else {
							$output .= '<a class="" href="' . $post_link . '">';
						}
					}
					if( $post_thumb ) {
						$output .= '<img class="post-img-feat" src="' . $post_thumb . '">';
					}
					if( true === $display_img_link ) {
						$output .= '</a>';
					}
					if( $post_title && $display_caption ) {
						$output .= '<p class="slick-p">' . $post_title . '</p>';
					}

					$output .= '</div>';

				$output .= "</article>";
			}//end while
			wp_reset_postdata();
		} else {
			echo '<p>No posts found.</p>';
		}
		//wp_reset_postdata();
		wp_reset_query();

		wp_enqueue_style( 'slick-css' );
		wp_enqueue_style( 'slick-theme-css' );
		wp_enqueue_script( 'slick-js' );
		wp_enqueue_script( 'match-height-js' );
		wp_enqueue_script( 'pegasus-carousel-plugin-js' );

		return '<section class="center logo-slider slider">' . $output . '</section>';

	}
	add_shortcode("logo_slider", "logo_slider_query_shortcode");

	/*~~~~~~~~~~~~~~~~~~~~
		TESTIMONIAL SLIDER
	~~~~~~~~~~~~~~~~~~~~~*/

	// [testimonial_slider the_query="post_type=testimonial&showposts=100" ]
	function testimonial_slider_query_shortcode($atts) {

		$a = shortcode_atts( array(
			"image" => '',
			"type" => '',
			"class" => ''
		), $atts );

		// Defaults
		extract(shortcode_atts(array(
			"the_query" => '',
		), $atts));

		// de-funkify query
		//$the_query = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $the_query);
		//$the_query = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $the_query);

		$the_query = preg_replace_callback('~&#x0*([0-9a-f]+);~', function($matches){
			return chr( dechex( $matches[1] ) );
		}, $the_query);

		$the_query = preg_replace_callback('~&#0*([0-9]+);~', function($matches){
			return chr( $matches[1] );
		}, $the_query);

		if ( '' === $the_query || null === $the_query || empty( $the_query ) ) {
			$the_query = 'post_type=testimonial&showposts=100';
		}

		$query_args = array(
			'post_type' => 'testimonial', // Ensure you are querying the correct post type
			'posts_per_page' => -1, // Set the number of posts to retrieve
			//'post_status' => 'publish', // Ensure only published posts are retrieved
			//'category_name' => 'your-category-slug', // Optional: Filter by category
			//'orderby' => 'date', // Optional: Order by date
			//'order' => 'DESC' // Optional: Order descending
		);

		// echo '<pre>';
		// var_dump( $the_query );
		// echo '</pre>';
		// echo '<pre>';
		// var_dump( $query_args );
		// echo '</pre>';
		// Convert query string into array for WP_Query
		//parse_str( $the_query, $query_args );
		// echo '<pre>';
		// var_dump( $query_args );
		// echo '</pre>';
		// Create a new WP_Query instance
		$query = new WP_Query( $query_args );

		// echo '<pre>';
		// var_dump( $query->posts );
		// echo '</pre>';
		global $post;

		$img_attr_val = "{$a['image']}";
		$type = "{$a['type']}";
		$class = "{$a['class']}";


		// Reset and setup variables
		$output = '';
		$temp_title = '';
		$temp_link = '';
		$temp_date = '';
		$temp_pic = '';
		$temp_content = '';
		$the_id = '';


		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();



				//$color_chk = "{$a['bkg_color']}";
				//if ($color_chk) { $output .= "<li style='background: {$a['bkg_color']}; '>"; }else{ $output .= "<li>"; }

				// the loop
				//if (have_posts()) : while (have_posts()) : the_post();

				$temp_title = get_the_title($post->ID);
				$temp_link = get_permalink($post->ID);
				$temp_date = get_the_date($post->ID);
				$temp_pic = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				$temp_excerpt = wp_trim_words( get_the_excerpt(), 150 );
				$temp_content = wp_trim_words( get_the_content(), 300 );
				$the_id = get_the_ID();

				$position = get_post_meta($post->ID, '_position', TRUE);
				$company_name = get_post_meta($post->ID, '_company_name', TRUE);
				$company_url = get_post_meta($post->ID, '_company_url', TRUE);


				// $the_id    = get_the_ID();
				// $temp_title = get_the_title();
				// $temp_link  = get_permalink();
				// $temp_pic = has_post_thumbnail() ? get_the_post_thumbnail_url($the_id, 'medium') : plugin_dir_url(__FILE__) . '/images/not-available.png';
				// $categories = get_the_category();
				// $temp_excerpt   = wp_trim_words( get_the_excerpt(), 150 );
				// $temp_content   = wp_trim_words( get_the_content(), 300 );

				// output all findings - CUSTOMIZE TO YOUR LIKING
				$output .= "<article class='post-$the_id' >";

					if($temp_pic && 'yes' == $img_attr_val || 'circle' == $img_attr_val ) {
						$output .= "<div class='testimonial-image'>";
						if ( 'circle' == $img_attr_val ) {
							$output .= "<img class='post-img-feat circle' src='$temp_pic'>";
						} else {
							$output .= "<img class='post-img-feat' src='$temp_pic'>";
						}
						$output .= "</div>";
					}



					$output .= "<div class='{$type} {$class}'><blockquote>";
					$output .= "<p class='post-content'>";
					if(isset($temp_excerpt)) {
						//$temporary_excerpt = substr(strip_tags($temp_excerpt), 0, 150);
						//if($temporary_excerpt){
								//$output .= $temporary_excerpt;
								//$output .= '...';
						//}
						$output .= $temp_excerpt;
					}else{
						//$more = 0;
						//$temporary = substr(strip_tags($temp_content), 0, 300);
						//if($temporary){ $output .= $temporary; $output .= '...'; }
						$output .= $temp_content;
					}
					$output .= "</p>";

					$output .= '<cite>'.$temp_title.'<br />';
						$output .= '<span class="">' . $temp_title . '</span>';
					$output .= '</cite>';
					$output .= '</blockquote></div>';

				$output .= "</article>";
			}//end while
			wp_reset_postdata();
		} else {
			echo '<p>No posts found.</p>';
		}

		//wp_reset_postdata();
		wp_reset_query();

		wp_enqueue_style( 'slick-css' );
		wp_enqueue_style( 'slick-theme-css' );
		wp_enqueue_script( 'slick-js' );
		wp_enqueue_script( 'match-height-js' );
		wp_enqueue_script( 'pegasus-carousel-plugin-js' );

		return '<section role="complementary" class="simple white-back testimonial-slider quotes no-fouc">' . $output . '</section>';

	}
	add_shortcode("testimonial_slider", "testimonial_slider_query_shortcode");


