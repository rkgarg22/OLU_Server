<?php 
// include(ABSPATH . "api/functions/index.php");

//Creating User Roles
function wps_change_role_sub_admin()
{
    global $wp_roles;
    if (!isset($wp_roles))
        $wp_roles = new WP_Roles();
    $wp_roles->roles['contributor']['name'] = 'Trainer';
    $wp_roles->role_names['contributor'] = 'trainer';
}
add_action('init', 'wps_change_role_sub_admin');

function wps_change_role_manager()
{
    global $wp_roles;
    if (!isset($wp_roles))
        $wp_roles = new WP_Roles();
    $wp_roles->roles['subscriber']['name'] = 'User';
    $wp_roles->role_names['subscriber'] = 'User';
}
add_action('init', 'wps_change_role_manager');
?>