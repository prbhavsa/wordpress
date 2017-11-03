<?php

/*
 * Plugin Name: UB Publications
 * Author: University of Bridgeport
 */

$author_count = 0;
//----------------------------------------------Custom Post Type BEGINS-----------------------------------------------------

function ub_publication_custom_post_type()
{
	$labels = array(
		'name'                  =>  _x('UB Publications', 'Post type general name','UB_Publications' ),
		'singular_name'         =>  _x('UB Publication', 'Post type singular name', 'UB_Publications'),
		'menu_name'             =>  _x('GIT Publications', 'admin menu', 'UB_Publications'),
		'name_admin_bar'        =>  _x('UB Publication', 'add new on admin bar', 'UB_Publications'),
		'add_new'               =>  _x('Add New Publication', 'UB_Publication', 'UB_Publications'),
		'add_new_item'          =>  __('Add New Publication','UB_Publications'),
		'new_item'              =>  __('New UB Publication','UB_Publications'),
		'edit_item'             =>  __('Edit UB Publication','UB_Publications'),
		'view_item'             =>  __('View UB Publications','UB_Publications'),
		'all_items'             =>  __('All UB Publications','UB_Publications'),
		'search_items'          =>  __('Search UB Publications','UB_Publications'),
		'parent_item_colon'     =>  __('Parent UB Publication','UB_Publications'),
		'not_found'             =>  __('No UB Publication found','UB_Publications'),
		'not_found_in_thrash'   =>  __('No UB Publication found in thrash','UB_Publications'),
	);

	$args = array(

		'labels'                =>  $labels,
		'description'           =>  __('To save the UB Publication record', 'UB_Publication'),
		'public'                =>  true,
		'publicly_queryable'    =>  true,
		'show_ui'               =>  true,
		'show_in_menu'          =>  true,
		'query_var'             =>  true,
		'menu_icon'             => 'dashicons-book-alt',
		'rewrite'               =>  array('slug'    =>  'publication-category'),
		'has_archive'           =>  true,
		'hierarchical'          =>  false,
		'supports'              =>  array('title', 'editor'),
		'taxonomies'            =>  array('categories')
	);

	register_post_type('ub_publications', $args);
}

add_action('init','ub_publication_custom_post_type');


//----------------------------------------------Custom Post Type ENDS-----------------------------------------------------
//
//
//----------------------------------------------Custom Taxonomy BEGINS-----------------------------------------------------

function ub_publication_categories_taxonomy(){

	$labels = array(
		'name'               => 'Categories',
		'singular_name'      => 'Category',
		'add_new_item'       => __( 'Add New Category' ),
		'edit_item'          => __( 'Edit Category' ),
		'all_items'          => __( 'All Categories' ),
		'view_item'          => __( 'View Category' ),
		'search_items'       => __( 'Search Categories' ),
		'not_found'          => __( 'Category not found' ),
		'menu_name'          => 'GIT Categories'
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => flase,
		'rewrite'           => array('slug'=>'publication-categories'),
		'show_ui'           =>  true,
		'show_admin_column' =>  true,
		'query_var'         =>  true,
	);
	register_taxonomy('ub_categories', 'ub_publications', $args);
}
add_action('init','ub_publication_categories_taxonomy');

//----------------------------------------------Custom Taxonomy ENDS-----------------------------------------------------
//
//
//----------------------------------------------Custom Custom Meta Box BEGINS-----------------------------------------------------


function ub_publication_custom_metabox(){

	add_meta_box(
		'publication_meta',
		'Publication Details',
		'publication_meta_callback',
		'ub_publications',
		'normal',
		'high'

	);
}


add_action('add_meta_boxes', 'ub_publication_custom_metabox');

//----------------------------------------------Custom Custom Meta Box ENDS-----------------------------------------------------

//----------------------------------------------Publication Meta Callback Definition BEGINS-----------------------------------------------------


/**
 * @param $post
 */
function publication_meta_callback($post)
{
	$structure = '<label for="publication_link" style="width:150px; display:inline-block;">'.esc_html('Publication Link:','ub').'</label>';
	$data = get_post_meta($post->ID,'ub_publication_link',true);
	$structure .= '<input type="url" name="publication_link" style="width: 250px;" value="'. esc_attr($data).'" placeholder="Publication Link"/> <br> <br>';

	$structure .= '<label for="publication_date" style="width:150px; display:inline-block;">'.esc_html('Publication Date:','ub').'</label>';
	$data = get_post_meta($post->ID,'ub_publication_date',true);
	$structure .= '<input type="text" name="face_date" placeholder="MM-DD-YYYY" onclick="date_change()" value="'. esc_attr($data).'"/>';
	$structure .= '<input type="date" name="publication_date" style="width: 250px;"  hidden/> <br> <br>';

	$structure .= '<label for="publisher_name" style="width:150px; display:inline-block;">'.esc_html('Publisher:','ub').'</label>';
	$data = get_post_meta($post->ID,'ub_publisher_name',true);
	$structure .= '<input type="text" name="publisher_name" style="width:250px;" value="'. esc_attr($data).'" placeholder="Publisher Name"/> <br> <br>';

	$structure .= '<label for="published_in" style="width:150px; display:inline-block;">'.esc_html('Published In:','ub').'</label>';
	$data = get_post_meta($post->ID,'ub_published_in',true);
	$selected='';
	$published_in= array('Conference','Journal','option3','option4','option5');

	$structure .= '<select  name="published_in" style="width: 250px;" >';

	foreach($published_in as $pubilisher){
		$selected='';
		if(strcmp($data,$pubilisher)==0){
			$selected='selected="selected"';
		}else{
			$selected='';
		}
		$structure .='<option value="'.$pubilisher.'" '.$selected.'>'.$pubilisher.'</option>';
	}
	$structure.='</select> <br> <br>';

	$structure .= '<label for="additional_author" style="width:150px; display:inline-block;">'.esc_html('Additional Authors:','ub').'</label>';
	$data = get_post_meta($post->ID,'ub_pub_additional_author',true);
	$structure .= '<input type="text" name="additional_authors_fname[]" style="width: 250px;" value="'. esc_attr($data).'" placeholder="First Name"/> ';
	$structure .= '<input type="text" name="additional_authors_lname[]" style="width: 250px;" value="'. esc_attr($data).'" placeholder="Last Name"/> ';

	$structure .= '<script>';

	$structure .= 'function date_change()
					{   document.getElementById("publication_date").hidden = false;
                        document.getElementById("face_date").hidden = true;
                        document.getElementById("publication_date").focus();
                    }
	
					function date_change2()
					{
						
					} 
	
	
	
	';

	$structure .= '</script>';

	echo $structure;


}// publication_meta_callback()


//----------------------------------------------Publication Meta Callback Definition ENDS-----------------------------------------------------

//----------------------------------------------Post Meta Data Saving BEGINS-----------------------------------------------------

    function publication_meta_save($post_id)
    {
        global $post;
        $postID = $post->ID;
        $publication_link = $_POST['publication_link'];
        $publication_date= $_POST['face_date'];
        $publisher_name= $_POST['publisher_name'];
        $published_in= $_POST['published_in'];
        //$additional_author= $_POST['author_first_name'] ."_".$_POST['author_last_name'];

        $counter = count($_POST['author_first_name']);

	    if(array_key_exists('publication_link',$_POST)){
		    update_post_meta($post_id,'ub_publication_link',$publication_link);
		    update_post_meta($post_id,'ub_publication_date',$publication_date);
		    update_post_meta($post_id,'ub_publisher_name',$publisher_name);
		    update_post_meta($post_id,'ub_published_in',$published_in);
		    //update_post_meta($post_id, 'ub_pub_additional_author',$additional_author);
		    for($i=0;$i<$counter;$i++)
		    {
			    $additional_author[$i]=$_POST['author_first_name'][$i] ."_".$_POST['author_last_name'][$i];
			    add_post_meta($postID,'ub_pub_additional_author',$additional_author[$i],false);
		    }
	    }

        //get all additional authors
  //       $authors= get_post_meta($post_id, 'ub_pub_additional_author',false);
/*
         foreach($authors as $author)
         {

             update_post_meta($post_id,);
         }
*/
    } // publication_meta_save

add_action('save_post','publication_meta_save');
//----------------------------------------------Post Meta Data Saving ENS-----------------------------------------------------

?>


