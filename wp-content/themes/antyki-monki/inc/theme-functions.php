<?php

function hide_menu_for_editor()
{
    if (current_user_can('editor')) {
        remove_menu_page('index.php');                  //Dashboard
        remove_menu_page('jetpack');                    //Jetpack*
        remove_menu_page('edit.php');                   //Posts
        // remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page('edit.php?post_type=page');    //Pages
        remove_menu_page('edit-comments.php');          //Comments
        remove_menu_page('themes.php');                 //Appearance
        remove_menu_page('plugins.php');                //Plugins
        // remove_menu_page( 'users.php' );                  //Users
        remove_menu_page('profile.php');                  //Profile
        remove_menu_page('tools.php');                  //Tools
        remove_menu_page('options-general.php');        //Settings
    }
}
add_action('admin_menu', 'hide_menu_for_editor');

function hide_admin_bar_for_editor()
{
    if (current_user_can('editor')) : ?>
        <style>
            #wp-admin-bar-new-content,
            #wp-admin-bar-comments,
            #wp-admin-bar-wp-logo {
                display: none;
            }
        </style>
<?php endif;
}
add_action('admin_footer', 'hide_admin_bar_for_editor');
