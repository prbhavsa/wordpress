<?php

/*
 * Plugin Name: UB Faculty Experts
 * Author: University of Bridgeport
 */

$author_count = 0;
//----------------------------------------------Custom Post Type BEGINS-----------------------------------------------------

function ub_faculty_experts_custom_post_type()
{
	$labels = array(
		'name'                  =>  _x('UB Faculty Experts', 'Post type general name','UB' ),
		'singular_name'         =>  _x('UB Faculty Expert', 'Post type singular name', 'UB'),
		'menu_name'             =>  _x('Faculty Experts', 'admin menu', 'UB'),
		'name_admin_bar'        =>  _x('UB Faculty Expert', 'add new on admin bar', 'UB'),
		'add_new'               =>  _x('Add New Faculty Expert', 'UB', 'UB'),
		'add_new_item'          =>  __('Add New Faculty Expert','UB'),
		'new_item'              =>  __('New UB Faculty Expert','UB'),
		'edit_item'             =>  __('Edit UB Faculty Expert','UB'),
		'view_item'             =>  __('View UB Faculty Experts','UB'),
		'all_items'             =>  __('All UB Faculty Experts','UB'),
		'search_items'          =>  __('Search UB Faculty Experts','UB'),
		'parent_item_colon'     =>  __('Parent UB Faculty Expert','UB'),
		'not_found'             =>  __('No UB Faculty Expert found','UB'),
		'not_found_in_thrash'   =>  __('No UB Faculty Expert found in thrash','UB'),
	);

	$args = array(

		'labels'                =>  $labels,
		'description'           =>  __('To save the UB Faculty Expert record', 'UB'),
		'public'                =>  true,
		'publicly_queryable'    =>  true,
		'show_ui'               =>  true,
		'show_in_menu'          =>  true,
		'query_var'             =>  true,
		'menu_icon'             => 'dashicons-welcome-learn-more',
		'hierarchical'          =>  false,
		'supports'              =>  array('title', 'editor','thumbnail'),
		'taxonomies'            =>  array()
	);

	register_post_type('ub_faculty_expert', $args);
}

add_action('init','ub_faculty_experts_custom_post_type');




//----------------------------------------------Custom Post Type ENDS-----------------------------------------------------
