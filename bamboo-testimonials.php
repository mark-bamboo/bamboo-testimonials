<?php
/************************************************************************************************************/
/*
Plugin Name: Bamboo Testimonials
Plugin URI:  https://www.bamboosolutions.co.uk/wordpress/bamboo-testimonials
Author:      Bamboo
Author URI:  https://www.bamboosolutions.co.uk
Version:     1.1.1
Description: Easily manage testimonials and display them throughout your website.
*/
/************************************************************************************************************/

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

/************************************************************************************************************/

	add_action( 'init', 'bamboo_testimonials_create_post_type' );
	function bamboo_testimonials_create_post_type() {

		$labels = array(
			'name' 			=> 'Testimonials',
			'singular_name'	=> 'Testimonial',
			'menu_name' 	=> 'Bamboo Testimonials',
			'all_items' 	=> 'All Testimonials',
			'add_new_item'	=> 'Add New Testimonial',
			'edit_item' 	=> 'Edit Testimonial',
			'new_item' 		=> 'New Testimonial',
			'view_item' 	=> 'View Testimonial',
			'search_items' 	=> 'Search Testimonials'
		);
		$supports = array( 'title', 'editor' );
		$args = array(
			'labels' 				=> $labels,
			'supports' 				=> $supports,
			'public' 				=> true,
			'has_archive' 			=> false,
			'exclude_from_search'	=> true,
			'show_in_nav_menus' 	=> false,
			'menu_position' 		=> 5,
			'menu_icon' 			=> 'dashicons-format-chat',
			'rewrite' 				=> array( 'slug' => 'testimonial' )
		);
		register_post_type( 'bamboo_testimonial', $args );

	}

/************************************************************************************************************/

	add_filter( 'enter_title_here', 'bamboo_testimonials_replace_post_title_prompt' );
	function bamboo_testimonials_replace_post_title_prompt( $prompt ) {

		global $post_type;

		if( is_admin() && 'bamboo_testimonial'==$post_type ) {
			return "Enter testimonial author here";
		}

		return $prompt;

	}

/************************************************************************************************************/

	add_shortcode( 'bamboo-testimonial', 'bamboo_testimonials_output_random_testimonial' );
	function bamboo_testimonials_output_random_testimonial( $atts, $content = null ) {

		$args = array(
			'post_type'		 => 'bamboo_testimonial',
			'orderby'		 => 'rand',
			'order'			 => 'ASC',
			'posts_per_page' => '1'
		);
		$posts = new WP_Query( $args );

		return bamboo_testimonials_get_html( $args );

	}

/************************************************************************************************************/

	add_shortcode( 'bamboo-testimonials', 'bamboo_testimonials_output_all_testimonials' );
	function bamboo_testimonials_output_all_testimonials( $atts, $content = null ) {

		$args = array(
			'post_type'		 => 'bamboo_testimonial',
			'orderby'		 => 'date',
			'order'			 => 'DESC',
			'posts_per_page' => '-1'
		);

		return bamboo_testimonials_get_html( $args );

	}

/************************************************************************************************************/

	function bamboo_testimonials_get_html( $args = null ) {

		$posts = new WP_Query( $args );

		$html = '';
		while ($posts->have_posts()) : $posts->the_post();
			$html.= bamboo_testimonial_to_html( get_the_title(), get_the_content() );
		endwhile;

		return $html;

	}

/************************************************************************************************************/

	function bamboo_testimonial_to_html( $author = '', $content = '' ) {

			$html.= '<blockquote class="bamboo-testimonial"><i>&ldquo;</i>';
			$html.= $author;
			$html.= '<i>&rdquo;</i><span>';
			$html.= $content;
			$html.='</span></blockquote>';

			return $html;

	}

/************************************************************************************************************/
?>
