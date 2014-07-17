<?php
if(!class_exists('WP_igniter_Settings'))
{
	class WP_igniter_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_igniter-group', 'wp_igniter_shortcode_posts');
        	register_setting('wp_igniter-group', 'wp_igniter_page_override');
        	register_setting('wp_igniter-group', 'wp_igniter_ci_path');
        	register_setting('wp_igniter-group', 'wp_igniter_handle_404');
        	register_setting('wp_igniter-group', 'wp_igniter_native_constants');
        	register_setting('wp_igniter-group', 'wp_igniter_custom_apppath');
        	register_setting('wp_igniter-group', 'wp_igniter_custom_sysfolder');
        	register_setting('wp_igniter-group', 'wp_igniter_ci_urihook');

        	// add your settings section
        	add_settings_section(
        	    'wp_igniter-section', 
        	    'WordPressIgniter Settings', 
        	    array(&$this, 'settings_section_wp_igniter'), 
        	    'wp_igniter'
        	);
        	
            add_settings_field(
                'wp_igniter_shortcode_posts', 
                'Trigger with [wordpressigniter] shortcode in posts, too.', 
                array(&$this, 'settings_field_input_checkbox'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_shortcode_posts'
                )
            );
            
            add_settings_field(
                'wp_igniter-page_override', 
                'Page Override', 
                array(&$this, 'settings_field_input_text'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_page_override'
                )
            );
            
            add_settings_field(
                'wp_igniter-ci_path', 
                'CodeIgniter Path', 
                array(&$this, 'settings_field_input_text'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_ci_path'
                )
            );
            
            add_settings_field(
                'wp_igniter-handle_404', 
                'Divert 404s to WordPressIgniter', 
                array(&$this, 'settings_field_input_checkbox'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_handle_404'
                )
            );
            
            add_settings_field(
                'wp_igniter-ci_uri_hook', 
                'CodeIgniter grabs all SEO urls<br />(use with caution!!)', 
                array(&$this, 'settings_field_input_checkbox'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_ci_urihook'
                )
            );
            
            add_settings_field(
                'wp_igniter-native_constants', 
                'Use CodeIgniter\'s native index.php to generate constants (i.e. APPPATH, BASEPATH)', 
                array(&$this, 'settings_field_input_checkbox'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_native_constants'
                )
            );
            
            add_settings_field(
            	null,
            	'The settings below have no effect if the &quot;native index.php...&quot; box above is checked',
                array(&$this, 'settings_field_null'), 
            	'wp_igniter',
            	'wp_igniter-section',
            	null
            );
            
             add_settings_field(
                'wp_igniter-custom_apppath', 
                'Customize APPPATH', 
                array(&$this, 'settings_field_input_text'),
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_custom_apppath'
                )
            );
             
            add_settings_field(
                'wp_igniter-custom_sysfolder', 
                'Customize BASEPATH', 
                array(&$this, 'settings_field_input_text'), 
                'wp_igniter', 
                'wp_igniter-section',
                array(
                    'field' => 'wp_igniter_custom_sysfolder'
                )
            );
            
           // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_wp_igniter()
        {
            // Think of this as help text for the section.
            ?>
            These settings control how WordPressIgniter loads CodeIgniter.
            <ul style="list-style:initial;list-style-position:inside;">
            <li>Engage CodeIgniter content into your blog by inserting the [wordpressigniter] shortcode into pages.</li>
            <li>Check the &quot;Trigger with [wordpressigniter] shortcode in posts, too&quot; box so that CI content shows in posts. (Most useful with the shortcode after the "read more" tag.)</li>
            <li>Page Override setting is deprecated and will disappear in future versions. Leave this blank and use the [wordpressigniter] shortcode instead.</li>
            <li>The CI Path points to CI's index.php front controller.</li>
            <li>The CI Path can be relative, but depending on your server settings, but you may need to edit the CI's index.php if you choose to use CI's index.php to generate constants.</li>
            <ul>
            <?php
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_null($args)
        {
        	echo '&nbsp;';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * This function provides checkbox inputs for settings fields
         */
        public function settings_field_input_checkbox($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo '<input type="checkbox" name="'.$field.'" id="'.$field.'" '.($value == true ? 'checked="checked"':'').' />';
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WordPressIgniter Settings', 
        	    'WordPressIgniter', 
        	    'manage_options', 
        	    'wp_igniter', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_igniter_Settings
} // END if(!class_exists('WP_igniter_Settings'))
