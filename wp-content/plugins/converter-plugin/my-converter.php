<?php
/*
 * Created by PhpStorm.
 * User: bprit
 * Date: 19-10-2017
 * Time: 02:07 PM
 * Plugin name: my converter
 * Author: Pritesh C. Bhasvar
 */

function my_content_filter( $content ) {
	$new_content = '';
		$new_content = str_replace(']]xyz' ,']]>',$content);
	return $new_content;
}
add_filter( 'the_content', 'my_content_filter', 1 );