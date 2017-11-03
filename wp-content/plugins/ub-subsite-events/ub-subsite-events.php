<?php
/**
 * Created by PhpStorm.
 * User: tvenna
 * Date: 7/26/17
 * Time: 2:34 PM
 */

/*
Plugin Name: UB Subsite Events
Plugin URI: http://webteam.bridgeport.edu
Description: Event management plugin for Emily Denaro.
Version: 1.0
Author: Trinadh Venna
Author URI: http://webteam.bridgeport.edu/trinadh
License: GPL2
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'ub-event-timeline.php';

add_action('init','ubevents_register_post_type');

function ubevents_register_post_type(){

	$labels = array(
		'name'               => _x( 'UB Events', 'post type general name', 'ub' ),
		'singular_name'      => _x( 'UB Event', 'post type singular name', 'ub' ),
		'menu_name'          => _x( 'UB Events', 'admin menu', 'ub' ),
		'name_admin_bar'     => _x( 'UB Event', 'add new on admin bar', 'ub' ),
		'add_new'            => _x( 'Add New', 'UB Event', 'ub' ),
		'add_new_item'       => __( 'Add New UB Event', 'ub' ),
		'new_item'           => __( 'New UB Event', 'ub' ),
		'edit_item'          => __( 'Edit UB Event', 'ub' ),
		'view_item'          => __( 'View UB Event', 'ub' ),
		'all_items'          => __( 'All UB Events', 'ub' ),
		'search_items'       => __( 'Search UB Events', 'ub' ),
		'parent_item_colon'  => __( 'Parent UB Events:', 'ub' ),
		'not_found'          => __( 'No UB Events found.', 'ub' ),
		'not_found_in_trash' => __( 'No UB Events found in Trash.', 'ub' )

	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'ub' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => false,
		'rewrite'            => array( 'slug' => 'ubevent' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title','editor'),
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-calendar-alt',
		'register_meta_box_cb' => 'ubevents_register_metaboxes'
	);

	register_post_type( 'ubevent', $args );

	$args = array(
		'label'        => __( 'Event Categories', 'ub' ),
		'public'       => true,
		'rewrite'      => false,
		'hierarchical' => true
	);

	register_taxonomy( 'ubevent-categories', 'ubevent', $args );

}



function ubevents_register_metaboxes($post){

	add_meta_box( 'meta-box-id', __( 'Event Details', 'ub' ), 'ubevents_metabox_display_callback');


}


function ubevents_metabox_display_callback($post){

	$outline = '<label for="ubevents-date" style="width:150px; display:inline-block;">'. esc_html__('Date', 'ub') .'</label>';
	$title_field = get_post_meta( $post->ID, '_ubevents_date', true );
	$outline .= '<input type="date" name="ubevents-date" id="ubevents-date" class="ubevents-date" value="'. esc_attr($title_field) .'" style="width:300px;"/><br><br>';


	$outline .= '<label for="ubevents-time" style="width:150px; display:inline-block;">'. esc_html__('Start Time', 'ub') .'</label>';
	$title_field = get_post_meta( $post->ID, '_ubevents_time', true );
	$outline .= '<input type="time" name="ubevents-time" id="ubevents-time" class="ubevents-time" value="'. esc_attr($title_field) .'" style="width:300px;"/><br><br>';

	$outline .= '<label for="ubevents-time-end" style="width:150px; display:inline-block;">'. esc_html__('End Time', 'ub') .'</label>';
	$title_field = get_post_meta( $post->ID, '_ubevents_time_end', true );
	$outline .= '<input type="time" name="ubevents-time-end" id="ubevents-time-end" class="ubevents-time-end" value="'. esc_attr($title_field) .'" style="width:300px;"/><br><br>';

	$outline .= '<label for="ubevents-location" style="width:150px; display:inline-block;">'. esc_html__('Location', 'ub') .'</label>';
	$title_field = get_post_meta( $post->ID, '_ubevents_location', true );
	$outline .= '<input type="text" name="ubevents-location" id="ubevents-location" class="ubevents-location" value="'. esc_attr($title_field) .'" style="width:300px;"/><br><br>';

	echo $outline;


}




function ubevents_save_meta_data($post_id)
{
	if (array_key_exists('ubevents-date', $_POST)) {
		update_post_meta(
			$post_id,
			'_ubevents_date',
			$_POST['ubevents-date']
		);
	}


	if (array_key_exists('ubevents-time', $_POST)) {
		update_post_meta(
			$post_id,
			'_ubevents_time',
			$_POST['ubevents-time']
		);
	}

	if (array_key_exists('ubevents-time-end', $_POST)) {
		update_post_meta(
			$post_id,
			'_ubevents_time_end',
			$_POST['ubevents-time-end']
		);
	}

	if (array_key_exists('ubevents-location', $_POST)) {
		update_post_meta(
			$post_id,
			'_ubevents_location',
			$_POST['ubevents-location']
		);
	}
	
	
}
add_action('save_post', 'ubevents_save_meta_data');

?>