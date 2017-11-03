<?php
/*
    Plugin Name: UB Student
    Author: University of Bridgeport

*/
//Defining Custom Post Type:- UB-Student
function ub_student() {
	$labels = array(
		'name'               => _x( 'UB Students', 'post type general name' ),
		'singular_name'      => _x( 'UB Student', 'post type singular name' ),
		'add_new'            => _x( 'Add New Student', 'book' ),
		'add_new_item'       => __( 'Add New Student' ),
		'edit_item'          => __( 'Edit Product' ),
		'new_item'           => __( 'New Student' ),
		'all_items'          => __( 'All Students' ),
		'view_item'          => __( 'View Student' ),
		'search_items'       => __( 'Search Products' ),
		'not_found'          => __( 'Student not found' ),
		'not_found_in_trash' => __( 'Student not found in the Trash' ),
		'menu_name'          => 'UB Students'
	);
	$args = array(
		'labels'            => $labels,
		'description'       => 'Holds our products and product specific data',
		'public'            => true,
		'show_in_nav_menu'  => true,
		'menu_icon'         => 'dashicons-admin-users',
		'menu_position'     => 5,
		'supports'          => array( 'title'),
		'has_archive'       => true,
        'hierarchical'      => false,
        'taxonomies'        => array('school')
	);
	register_post_type( 'student', $args );
}
add_action( 'init', 'ub_student' );

// Defining Taxonomy:- UB_School_Taxonomy

function ub_school_taxonomy(){

	$labels = array(
		'name'               => 'Schools',
		'singular_name'      => 'School',
		'add_new_item'       => __( 'Add New School' ),
		'edit_item'          => __( 'Edit School' ),
		'all_items'          => __( 'All Schools' ),
		'view_item'          => __( 'View School' ),
		'search_items'       => __( 'Search Schools' ),
		'not_found'          => __( 'School not found' ),
		'menu_name'          => 'School'
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'rewrite'           => array('slug'=>'School'),
		'show_ui'           =>  true,
		'show_admin_column' =>  true,
		'query_var'         =>  true,
	);
	register_taxonomy('school', 'student', $args);
}
add_action('init','ub_school_taxonomy');




function ub_student_custom_metabox(){

	add_meta_box(
		'student_meta',
		'Personal details',
		'student_meta_callback',
		'student',
		'normal',
		'high',
		'core'
	);
}

add_action('add_meta_boxes','ub_student_custom_metabox');

function student_meta_callback($post) {

    //global $post;
    $post_id = $post->ID;
    if(get_post_meta($post_id,'ub_student_age') != "")
    {
	   $age = get_post_meta($post_id, 'ub_student_age',true);
	   $email = get_post_meta($post_id, 'ub_student_email',true);
	   $gender = get_post_meta($post_id, 'ub_student_gender',true);
	   $education = get_post_meta($post_id, 'ub_student_education_level',true);
	   $gpa = get_post_meta($post_id, 'ub_student_gpa',true);
    }


	?>
    <div>
        <table style="width:60%">
            <tr>
                <td>
                    <label>Age: </label>
                </td>
                <td>
                    <input type="text" name="ub_student_age" id="student-age" onfocusout="validate_age()"  required value="<?php echo $age ?>"/>
                    <p id="error-age"></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Email ID: </label>
                </td>
                <td>
                    <input type="text" name="ub_student_email" id="student-email" onfocusout="validate_email() " required value="<?php echo $email ?>"/>

                    <p id="error-email"></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Select the gender: </label>
                </td>
                <td>
                    <input type="radio" name="ub_student_gender" id="gender_male" value="male" checked required/> Male <br>
                    <input type="radio" name="ub_student_gender" id="gender_female" value="female" required/> Female
                </td>
            </tr>
            <tr>
                <td>
                    <label>Education Level:</label>
                </td>
                <td>
                    <select name="ub_student_edu_level" required >
                        <option value="freshmen">Freshmen</option>
                        <option value="under_graduate">Under Graduate</option>
                        <option value="graduate">Graduate</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label>G.P.A.:</label>
                </td>
                <td>
                    <input type="text" name="ub_student_gpa" id="student-gpa" onfocusout="validate_gpa()" required value="<?php echo $gpa ?>"/>
                    <p id="error_gpa"></p>
                </td>
            </tr>

        </table>


    </div>

    <script>

        function check_db()
        {
            var gender = <?php $gender ?>;
            alert(gender);
            if( gender == "male")
            {
                document.getElementById("gender_male").checked = true;
                document.getElementById("gender_female").checked = false;
            }
            else
            {
                document.getElementById("gender_male").checked = false;
                document.getElementById("gender_female").checked = true;
            }


        }
        function validate_age() {

            var age;
            age = document.getElementById("student-age").value;

            if (isNaN(age) || age > 100 || age < 1) {
                document.getElementById("student-age").value = "";
                document.getElementById("error-age").innerHTML = "Enter Valid Age";
            }
            else {
                document.getElementById("error-age").innerHTML = "";
            }
        }

        function validate_email() {
            var email = document.getElementById("student-email").value;
            var atpos = email.indexOf("@");
            var dotpos = email.lastIndexOf(".");
            if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
                document.getElementById("error-email").innerHTML = "Please enter your email Address";
            }
            else {
                document.getElementById("error-email").innerHTML = "";
            }
        }

        function validate_gpa() {
            var gpa = document.getElementById("student-gpa").value;
            if (gpa == "" || gpa > 4 || gpa < 0) {
                document.getElementById("student-gpa").value = "";
                document.getElementById("error_gpa").innerHTML = "Enter a valid GPA";
            }
            else {
                document.getElementById("error_gpa").innerHTML = "";
            }
        }
    </script>

	<?php
}


	function ub_student_metasave($post_id)
	{

		$age = $_POST["ub_student_age"];
		$email = $_POST["ub_student_email"];
		$gender = $_POST["ub_student_gender"];
		$education_level = $_POST["ub_student_edu_level"];
		$gpa = $_POST["ub_student_gpa"];

		if(array_key_exists('ub_student_age',$_POST)){

			update_post_meta($post_id, 'ub_student_age', $age);
			update_post_meta($post_id, 'ub_student_email', $email);
			update_post_meta($post_id, 'ub_student_gender', $gender);
			update_post_meta($post_id, 'ub_student_education_level', $education_level);
			update_post_meta($post_id, 'ub_student_gpa', $gpa);
        }
	}

	add_action('save_post','ub_student_metasave');








