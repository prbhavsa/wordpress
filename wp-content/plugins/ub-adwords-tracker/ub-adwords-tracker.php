<?php
/*
 * Plugin Name: UB AdWords Tracker
 * Author: Pritesh C. Bhavsar
 * Plugin URI: http://webteam.bridgeport.edu
 * Description: Adwords Tracking plugin for pages
 * Version: 1.0
 * License: GPL2
 */


function ub_adwords_tracker()
{
	add_meta_box(
		'ub_aswords_tracker_meta',
		'Adwords Tracking Codes',
		'metabox_callback',
		'page',
		'normal',
		'high'
	);
} // ub_adwords_tracker()

add_action('add_meta_boxes', 'ub_adwords_tracker');


function metabox_callback($post)
{
	wp_nonce_field('ub_adwords_tracker_metasave','verify_nonce');

	$raw_html = get_post_meta($post->ID,'ub_adwords_tracker_code',true);
	$structure = '<div><textarea name="adwords_tracker_code" rows="10" style="width: 100%" 
					placeholder="Insert any tracking code">'.$raw_html.'</textarea></div>';
	echo $structure;
} // metabox_callback

function ub_adwords_tracker_metasave($post_id)
{

	$rawHTML = $_POST['adwords_tracker_code'];

	if(array_key_exists('adwords_tracker_code',$_POST) && wp_verify_nonce($_POST['verify_nonce'],'ub_adwords_tracker_metasave'))
	{
		if($rawHTML == "")
		{
			delete_post_meta($post_id,'ub_adwords_tracker_code');
		}
		else
		{
			update_post_meta($post_id,'ub_adwords_tracker_code',$rawHTML);
		}
	}

} // ub_adwords_tracker_metasave

add_action('save_post','ub_adwords_tracker_metasave');

function append_raw_HTML()
{
	global $post;
	echo get_post_meta($post->ID,'ub_adwords_tracker_code',true);
}// append_raw_HTML

add_action('wp_footer','append_raw_HTML',1);



