<?php
/*
Plugin Name: WordPressIgniter
Description: A CodeIgniter Wordpress Plugin
Version: 1.4
Author: Dan Hartman
Author URI: http://www.dynamodan.com
License: GPL2
*/
/*
Copyright 2013  Dan Hartman  (email : info@dynamodan.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// global! gotta keep things global, or CI just barfs!
global $CI_OUTPUT;  // CodeIgniter's output, caught with ob_start() and ob_get_contents()
global $CI_STATUS;  // CodeIgniter's very own http status, for example to represent its own 404's
global $CI_REQUEST; // so that CodeIgniter knows about the URI for segmentation, otherwise everything goes through the default controller
global $CI_USER; // to hold a copy of the wordpress user object, trying to put it in $current_user causes strange bugs
$CI_USER = null;
$CI_STATUS = 200;

// most of this gets ignored if we're not in the main site, i.e. if we're in admin, if we're in user registration etc etc
if(!is_admin()) {
	
	// these functions are gotten from pluggable.php, because we need them now, but
	// they don't get loaded until after the plugins are loaded!
	if ( !function_exists('get_user_by')) {
	function get_user_by( $field, $value ) {
		$userdata = WP_User::get_data_by( $field, $value );
	
		if ( !$userdata )
			return false;
	
		$user = new WP_User;
		$user->init( $userdata );
	
		return $user;
	}
	}
	
	if ( !function_exists('wp_parse_auth_cookie')) {
	function wp_parse_auth_cookie($cookie = '', $scheme = '') {
		if ( empty($cookie) ) {
			switch ($scheme){
				case 'auth':
					$cookie_name = AUTH_COOKIE;
					break;
				case 'secure_auth':
					$cookie_name = SECURE_AUTH_COOKIE;
					break;
				case "logged_in":
					$cookie_name = LOGGED_IN_COOKIE;
					break;
				default:
					if ( is_ssl() ) {
						$cookie_name = SECURE_AUTH_COOKIE;
						$scheme = 'secure_auth';
					} else {
						$cookie_name = AUTH_COOKIE;
						$scheme = 'auth';
					}
			}
	
			if ( empty($_COOKIE[$cookie_name]) )
				return false;
			$cookie = $_COOKIE[$cookie_name];
		}
	
		$cookie_elements = explode('|', $cookie);
		if ( count($cookie_elements) != 3 )
			return false;
	
		list($username, $expiration, $hmac) = $cookie_elements;
	
		return compact('username', 'expiration', 'hmac', 'scheme');
	}
	}
	
	if($cookie_elements = wp_parse_auth_cookie($_COOKIE[LOGGED_IN_COOKIE], 'logged_in')) {
		
		extract($cookie_elements, EXTR_OVERWRITE);
	
		$expired = $expiration;
	
		// Allow a grace period for POST and AJAX requests
		if ( defined('DOING_AJAX') || 'POST' == $_SERVER['REQUEST_METHOD'] )
			$expired += HOUR_IN_SECONDS;
	
		// Quick check to see if an honest cookie has expired
		if ( $expired >= time() ) {
			$CI_USER = get_user_by('login', $username);
		}
	}

	$ci_path = get_option('wp_igniter_ci_path');
	$cwd = getcwd();
	$errmsg = '';
	
	
	// always force CodeIgniter to load it's default controller, 
	// unless the user specifically has a hook to get the $_SERVER['REQUEST_URI'] back:
	$CI_REQUEST = $_SERVER['REQUEST_URI'];
	$get_backup = $_GET; // because CodeIgniter clobbers it, but parts of wordpress need it
	
	if(!get_option('wp_igniter_ci_urihook')) { $_SERVER['REQUEST_URI'] = '/'; }

	// using the WordPressIgniter to set the CI constants:
	if(!get_option('wp_igniter_native_constants')) {
		$application_folder = $ci_path.'application/';
		$system_path = $ci_path.'system/';
		
		// TODO: provide settings for the application and system folders
		if(get_option('wp_igniter_custom_apppath')) {
			$application_folder = get_option('wp_igniter_custom_apppath');
		}
		if(get_option('wp_igniter_custom_sysfolder')) {
			$system_path = get_option('wp_igniter_custom_sysfolder');
		}
		
		if (realpath($system_path) !== FALSE) {
			$system_path = realpath($system_path).'/';
		}
	
		// ensure there's a trailing slash
		$system_path = rtrim($system_path, '/').'/';
	
		// Is the system path correct?
		if ( ! is_dir($system_path)) {
			$errmsg = "Your system folder path $system_path does not appear to be set correctly.";
		}

		// The name of THIS file
		define('SELF', pathinfo($ci_path.'/index.php', PATHINFO_BASENAME));
	
		// The PHP file extension
		// this global constant is deprecated.
		define('EXT', '.php');
	
		// Path to the system folder
		define('BASEPATH', str_replace("\\", "/", $system_path));
	
		// Path to the front controller (this file)
		define('FCPATH', str_replace(SELF, '', $ci_path.'/index.php'));
	
		// Name of the "system folder"
		define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
	
	
		// The path to the "application" folder
		if (is_dir($application_folder)) {
			define('APPPATH', $application_folder.'/');
		} else {
			if ( ! is_dir(BASEPATH.$application_folder.'/')) {
				$errmsg = "Your application folder path $application_folder does not appear to be set correctly.";
			}
	
			define('APPPATH', BASEPATH.$application_folder.'/');
		}

		// load the ci bootstrap file here instead of letting CI's native index.php do it: 
		
		if($errmsg == '') {
			ob_start();
			require_once BASEPATH.'core/CodeIgniter.php';
			$CI_OUTPUT = ob_get_contents();
			ob_end_clean();
		} else {
			$CI_OUTPUT = $errmsg;
		}
	}
	
	// using the CI front controller to set the constants:
	else {
		if(file_exists($ci_path).'/index.php') {
			chdir($ci_path);
			ob_start();
			require_once($ci_path.'/index.php');
			$CI_OUTPUT = ob_get_contents();
			ob_end_clean();
			chdir($cwd);
		} else {
			$CI_OUTPUT = "Couldn't locate CodeIgniter native index.php file.";
		}
	}
	
	$_GET = $get_backup;
	$_SERVER['REQUEST_URI'] = $CI_REQUEST;
	error_reporting(0);

}
// END if(!is_admin())


// now for the WP Igniter class itself:
if(!class_exists('WP_Igniter')) {
	class WP_Igniter {
		/**
		 * Construct the plugin object
		 */
		 
		var $triggered = false;
		var $page_matches = array();
 		 
		public function __construct() {
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$WP_Igniter_Settings = new WP_Igniter_Settings();
			
			if(!is_admin()) {
				if(get_option('wp_igniter_handle_404')) {
					add_action('template_redirect', array(&$this, 'main_page'), 1);
				}
				
				add_filter('the_title', array(&$this, 'main_title'));
				add_filter('the_content', array(&$this, 'main_content'));
				
	 			$this->page_matches = explode(',', get_option('wp_igniter_page_override'));
			}
		  
 		} // END public function __construct()
		
		
 		public function main_page() {
 			global $wp_query, $CI_STATUS;
 			if(!$wp_query->is_404) { return; }
 			
 			$this->triggered = true;
			status_header($CI_STATUS);
 			$wp_query->is_404 = false;
 			$wp_query->is_page = true;
 			
 			// "fictitiously" enter our target post here, patching up some variables here and there
 			// to make the menu work properly etc
 			if(count($this->page_matches) > 0) {
				$page = &get_page_by_title($this->page_matches[0]);
				rewind_posts();
				$wp_query->is_singular = true;
				$wp_query->found_posts = 1;
				$wp_query->post_count = 1;
				$wp_query->queried_object = $page;
				$wp_query->queried_object_id = $page->ID;
				$wp_query->posts = array($page);
				$wp_query->post = $page;
			}
 		}
 		
		public function main_content($content) {
			global $wp_query, $CI_OUTPUT;

			// check for the shortcode: (I know this isn't EXACTLY the wordpress way of doing shortcodes)
			$shortcode_found = strpos($content, '[wordpressigniter]');
			
			// not a page, and, BOTH 1. not shortcoding posts, and 2. not finding a shortcode
			if(!$wp_query->is_page && !get_option('wp_igniter_shortcode_posts') && $shortcode_found !== false) {
				return $content;
			}
			
			// triggered by a 404, so we should handle as such:
			if($this->triggered) {
				$content = 'Congrats, you got to the WordPressIgniter Plugin!';
				if(!get_option('wp_igniter_ci_path')) { $content .= "<br />You need to set the path to the CodeIgniter front controller."; return $content; } 
				$ci_path = get_option('wp_igniter_ci_path').'index.php';
				if(!file_exists($ci_path)) { return "Couldn't locate path ".$ci_path; }
				
				
				return $CI_OUTPUT;
				
			}
			
			// use the shortcode:
			else if($shortcode_found === false) {
				return $content;
			}
			
			return $CI_OUTPUT;
			
		}
		
		public function main_title($content) {
			// maybe CI set a title already:
			if(function_exists('get_instance')) {
				$ci = &get_instance();
				if($this->triggered && isset($ci->content['page_title'])) { $ci_title = $ci->content['page_title']; }
				// return $ci_title;
			}
			
			// trigger by title
			if(!in_array($content, $this->page_matches)) { return $content; }
			
			// only do this for the title within the post, not on the menu: (TODO: make a switch to alter this behaviour)
			if(did_action('the_post') == 0) { return $content; }
			
			$this->triggered = true;
			$ci_title = 'WordPressIgniter';
			if(function_exists('get_instance')) {
				$ci_title = 'WordPressIgnition!';
				$ci = &get_instance();
				if(isset($ci->content['page_title'])) { $ci_title = $ci->content['page_title']; }
			}
			return $ci_title;
		}
		
		// a handy function for debugging here and there
		function dmp($obj) {
			return '<pre>'.htmlspecialchars(var_export($obj, true)).'</pre>';
		}
		
		
		/**
		 * Activate the plugin
		 */
		public static function activate() {
			// Do nothing
		} // END public static function activate
	
		/**
		 * Deactivate the plugin
		 */		
		public static function deactivate() {
			// Do nothing
		} // END public static function deactivate
		
	} // END class WP_Igniter
} // END if(!class_exists('WP_Igniter'))

if(class_exists('WP_igniter')) {
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Igniter', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Igniter', 'deactivate'));

	// instantiate the plugin class
	$wp_igniter = new WP_Igniter();
	
	// Add a link to the settings page onto the plugin page
	if(isset($wp_igniter)) {
		// Add the settings link to the plugins page
		function plugin_settings_link($links) { 
			$settings_link = '<a href="options-general.php?page=wp_igniter">Settings</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		}

		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
	}
}