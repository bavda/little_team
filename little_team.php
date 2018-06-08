<?php
/*
Plugin Name: Little Team
Plugin URI: https://www.facebook.com/andriy.bavda
Description: For test
Version: 1.0
Author: A.Bavda
Author URI: https://www.facebook.com/andriy.bavda
*/

defined('ABSPATH') || die('Direct access denied!');

define( 'LITTLE_TEAM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( LITTLE_TEAM_PLUGIN_DIR . 'classes/class_littel_team.php' );

add_action('wp_enqueue_scripts', 'littel_team_stylesheet' , 30);

//scrips and styles
function littel_team_stylesheet() 
{
	//wp_enqueue_script('masonry');
	wp_enqueue_style( 'littel_team_css', plugins_url( '/css/style.css', __FILE__ ) );

}

//Plugin start
$Little_Team = new little_team();
