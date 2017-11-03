<?php
/*
 * Plugin Name: sample_authentication
 * Author: Pritesh Bhavasr
 */

function my_auth( $user, $username, $password ){

	if($username == '' || $password == '') return;

	$ldapHost = "localhost";
	$ldapPort = 389;
	$ldapUname= "cn=Manager,dc=maxcrc,dc=com";
	$ldapPassword="secret";
	$dn = "ou=People,dc=maxcrc,dc=com";

	$ldapconn = ldap_connect($ldapHost,$ldapPort)or die("Could not connect to $ldapHost");
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

	if($ldapconn)
	{
		if(ldap_bind($ldapconn))
		{
			$sr = ldap_search($ldapconn, $dn,"uid=$username");
			$info = ldap_get_entries($ldapconn,$sr);
			$dnUser = $info[0]['dn'];

			if($username && $password)
			{
				$ldapuserconnect = ldap_connect($ldapHost,$ldapPort);
				ldap_set_option($ldapuserconnect, LDAP_OPT_PROTOCOL_VERSION, 3);

				if(ldap_bind($ldapuserconnect,$dnUser,$password))
				{

					$userobj = new WP_User();
					//$sr = ldap_search($ldapuserconnect,$dnUser,"uid=$username");
					//$info = ldap_get_entries($ldapuserconnect,$sr);
					$email = $info[0]['mail'][0];
					$fname = $info[0]['cn'][0];
					$lname = $info[0]['sn'][0];

					$userobj = get_user_by( 'login', $username );
					$user = new WP_User($userobj->ID);
					if( $user->ID == null ) {
						echo $user->ID ."<br><br>";
						//alert('text');
						$userdata = array( 'user_email' => $email,
						                   'user_login' => $username,
						                   'first_name' => $fname,
						                   'last_name'  => $lname,
						                   'user_pass'  => $password
						);
						$new_user_id = wp_insert_user( $userdata ); // A new user has been created
						$user = new WP_User ($new_user_id);
					}
				}
				else
				{
					$user = new WP_Error( 'denied', __("ERROR: User/pass bad") );
				} // if(ldap_bind($ldapuserconnect,$dnUser,$password))
			} // if($username && $password)
		} // end $ldap_bind
	} // end $ldapconn

	//remove_action('authenticate', 'wp_authenticate_username_password', 20);
	return $user;
}


add_filter('authenticate','my_auth',10,3);