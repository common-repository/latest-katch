<?php
/*
Plugin Name: Latest Katch - Embed Katch.me Into WordPress
Description:  Embeds the latest Periscope or Meerkat video from a Katch.me account into any page or post. Type [katch] where you want the video to be displayed.
Version:      1.0.2
Author: MPWR Design
Author URI: http://www.mpwrdesign.com/
Plugin URI: http://www.mpwrdesign.com/products/latest-katch/

*/

add_action( 'admin_menu', 'latest_katch_menu' );

function latest_katch_menu() {
	add_options_page( 'Latest Katch Options', 'Latest Katch', 'manage_options', 'latest-katch', 'latest_katch_options');
	add_action('admin_init','register_katch_settings');
}

function katch_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=latest-katch">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$katchplugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$katchplugin", 'katch_settings_link' );

function register_katch_settings() {
	register_setting('latest-katch-settings', 'katchuser');
	register_setting('latest-katch-settings', 'katchwidth');
	register_setting('latest-katch-settings', 'katchheight');

}

function latest_katch_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<div class="wrap">
<h1>Latest Katch Options</h1>
<form action="options.php" method="post">
<?php settings_fields('latest-katch-settings'); ?>
<?php do_settings_sections('latest-katch-settings'); ?>
The Latest Katch plugin uses a shortcode to embed the latest video from a Katch.me feed into your site. Type [katch] where you want to embed the latest video.
<h2>Katch.me Username</h2>
Type the Katch.me username below.
<table class="form-table">
<tr>
<th scope="row"><label for="katchuser">Username</label></th>
<td><input name="katchuser" type="text" id="katchuser" value="<?php echo esc_attr( get_option('katchuser') ); ?>" class="regular-text ltr" /></td>
</tr>
</table>
<h2>Custom Display Size</h2>
Specify the size for the video to display. (Recommended: 282 width x 500 height)
<table class="form-table">
<tr>
<th scope="row"><label for="katchwidth">Width</label></th>
<td><input name="katchwidth" type="text" id="katchwidth" value="<?php echo esc_attr( get_option('katchwidth') ); ?>" class="regular-text ltr" /></td>
</tr>
<th scope="row"><label for="katchheight">Height</label></th>
<td><input name="katchheight" type="text" id="katchheight" value="<?php echo esc_attr( get_option('katchheight') ); ?>" class="regular-text ltr" /></td>
</tr>
</table>
<?php submit_button(); ?>
</form>
</div>
<?php
}

function latest_katch() {
require_once('katch-load.php');
return $embedcode;
}
add_shortcode('katch', 'latest_katch');
?>