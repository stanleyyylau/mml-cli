<?php
/*
  Plugin Name:Cool Timeline Pro
  Plugin URI:http://www.cooltimeline.com
  Description:Use Cool Timeline pro wordpress plugin to showcase your life or your company story in a vertical timeline format. Cool Timeline Pro is an advanced timeline plugin that creates responsive vertical storyline automatically in chronological order based on the year and date of your posts.
  Version:1.8
  Author:CoolHappy
  Author URI:http://www.cooltimeline.com
  License: GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain:cool-timeline
 */

/** Configuration * */
if (!defined('COOL_TIMELINE_VERSION_CURRENT')){
    define('COOL_TIMELINE_VERSION_CURRENT', '1.8');

}
define('COOL_TIMELINE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('COOL_TIMELINE_PLUGIN_DIR', plugin_dir_path(__FILE__));
defined( 'FA_DIR' ) or define( 'FA_DIR', plugin_dir_path( __FILE__ ).'/font-awesome-field/' );
defined( 'FA_URL' ) or define( 'FA_URL', plugin_dir_url( __FILE__ ).'/font-awesome-field/'  );

if (!class_exists('Cool_Timeline')) {

    class Cool_Timeline {

        /**
         * Construct the plugin objects
         */
        public function __construct() {

            $this->plugin_path = plugin_dir_path(__FILE__);

            // Installation and uninstallation hooks
            register_activation_hook(__FILE__ , array($this,'activate'));
            register_deactivation_hook(__FILE__ , array($this,'deactivate'));

            //include the main class file
            require_once( COOL_TIMELINE_PLUGIN_DIR . "admin-page-class/admin-page-class.php");
            require_once COOL_TIMELINE_PLUGIN_DIR . 'includes/ctl-helpers.php';
            // cooltimeline post type
            require_once COOL_TIMELINE_PLUGIN_DIR . 'includes/cool_timeline_posttype.php';
            //include the main class file
            require_once COOL_TIMELINE_PLUGIN_DIR . "meta-box-class/my-meta-box-class.php";
            
             require_once COOL_TIMELINE_PLUGIN_DIR . "includes/categories-images.php";
            /*
             * View
             */
            require_once COOL_TIMELINE_PLUGIN_DIR . 'includes/cool_timeline_template.php';

            $cool_timeline_posttype = new CoolTimeline_Posttype();
            new CoolTimeline_Template();

            /*
             * Options panel
             */
            $this->ctl_option_panel();
            /*
             *  custom meta boxes 
             */
            $this->clt_meta_boxes();

            /**
             * Add an instance of our plugin to WordPress
             **/

            // 1. Load the library (skip this if you are loading the library as a plugin)
            require_once COOL_TIMELINE_PLUGIN_DIR .'dynamic-css/bootstrap.php';
            // styles
            require COOL_TIMELINE_PLUGIN_DIR . 'includes/cool_timeline_custom_styles.php';
            new Cooltimeline_Styles();

            require_once COOL_TIMELINE_PLUGIN_DIR .'font-awesome-field/font-awesome-field.php';
            new Font_Awesome_Field();
            // Include other PHP scripts
            add_action( 'init', array( $this, 'include_files' ) );


            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array($this, 'plugin_settings_link'));

            // add a tinymce button that generates our shortcode for the user
            add_action('admin_head', array(&$this, 'ctl_add_tinymce'));
            add_image_size('ctl_avatar', 250, 250, true); // Hard crop left top
            // Register a new custom image size
            // add_image_size('cool_timeline_custom_size', '350', '120', true);
            add_action('plugins_loaded', array(&$this, 'clt_load_plugin_textdomain'));

            //Fixed bridge theme confliction using this action hook
            add_action( 'wp_print_scripts', array(&$this,'ctl_deregister_javascript'), 100 );
        }

        function clt_load_plugin_textdomain() {

            $rs = load_plugin_textdomain('cool-timeline', FALSE, basename(dirname(__FILE__)) . '/languages/');
        }

        // Add the settings link to the plugins page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=cool_timeline_page">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }
        /**
         * Include other PHP scripts for the plugin
         * @return void
         *
         **/
        public function include_files() {
            // Files specific for the front-ned
            if ( ! is_admin() ) {
                // Load template tags (always last)
                require_once COOL_TIMELINE_PLUGIN_DIR .'font-awesome-field/includes/template-tags.php';
            }
        }

        /*
        * Fixed Bridge theme confliction
        */
        function ctl_deregister_javascript() {

            if(is_admin()) {
                $screen = get_current_screen();
                if ($screen->base == "toplevel_page_cool_timeline_page") {
                    wp_deregister_script('default');
                }
            }
        }


        function ctl_option_panel() {

            /**
             * configure your admin page
             */
            $config = array(
                'menu' => array('top' => 'cool_timeline'), //sub page to settings page
                'page_title' => __('Cool Timeline Pro', 'apc'), //The name of this page 
                'capability' => 'edit_themes', // The capability needed to view the page 
                'option_group' => 'cool_timeline_options', //the name of the option to create in the database
                'id' => 'cool_timeline_page', // meta box id, unique per page
                'fields' => array(), // list of fields (can be added by field arrays)
                'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
            );

            /**
             * instantiate your admin page
             */
            $options_panel = new BF_Admin_Page_Class($config);
            $options_panel->OpenTabs_container('');

            /**
             * define your admin page tabs listing
             */
            $options_panel->TabsListing(array(
                'links' => array(
                    'options_1' => __('General Settings', 'apc'),
                    'options_2' => __('Style Settings', 'apc'),
                    'options_3' => __('Typography Settings', 'apc'),
                    'options_4' => __('Stories Settings', 'apc'),
                    'options_5' => __('Date Settings', 'apc'),
                    'options_7' => __('Navigation Settings', 'apc'),
                    'options_9' => __('Stories Details Settings', 'apc'),
                    'options_8' => __('Timeline Display', 'apc'),
                    'options_6' => __('Extra Settings', 'apc'),
                )
            ));

            /**
             * Open admin page first tab
             */
            $options_panel->OpenTab('options_1');

            /**
             * Add fields to your admin page first tab
             * 
             * Simple options:
             * input text, checbox, select, radio 
             * textarea
             */
            //title
            $options_panel->Title(__("General Settings", "apc"));
            $options_panel->addText('title_text', array('name' => __('Timeline Title (Default) ', 'apc'), 'std' => 'Cool Timeline', 'desc' => __('', 'apc')));

            //select field
            $options_panel->addSelect('title_tag', array('h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6'), array('name' => __('Title Heading Tag ', 'apc'), 'std' => array('h1'), 'desc' => __('', 'apc')));
            $options_panel->addRadio('title_alignment', array('left' => 'Left',
                'center' => 'Center', 'right' => 'Right'), array('name' => __('Title Alignment ?', 'apc'), 'std' => array('center'), 'desc' => __('', 'apc')));
            $options_panel->addRadio('display_title', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Display Title ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));

            $options_panel->addText('post_per_page', array('name' => __('Number of stories to display ?', 'apc'), 'std' =>20, 'desc' => __('It is default option and overrided by shortcode. Please check your shortcode tag.', 'apc')));
         
            $options_panel->addText('content_length', array('name' => __('Content Length ? ', 'apc'), 'std' => 50, 'desc' => __('Please enter no of words', 'apc')));
            //Image field
            
            $options_panel->addImage('user_avatar', array('name' => __('Timeline default Image', 'apc'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('desc_type', array('short' => 'Short (Default)',
                'full' => 'Full (with HTML)'), array('name' => __('Stories Description?', 'apc'), 'std' => array('short'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('display_readmore', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Display read more ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));


            $options_panel->addRadio('posts_orders', array('DESC' => 'DESC',
                'ASC' => 'ASC'), array('name' => __('Stories Order ?', 'apc'), 'std' => array('DESC'), 'desc' => __('', 'apc')));
              //select field
              $options_panel->CloseTab();

			 /**
             * Open admin page secondsetting-error-tgmpa tab
             */
            $options_panel->OpenTab('options_2');
            $options_panel->Title(__("Style Settings", "apc"));
            /**
             * To Create a Conditional Block first create an array of fields (just like a repeater block
             * use the same functions as above but add true as a last param
             */
            $Conditinal_fields[] = $options_panel->addColor('bg_color', array('name' => __('Background Color', 'apc')), true);

            /**
             * Then just add the fields to the repeater block
             */
            //conditinal block 
            $options_panel->addCondition('background', array(
                'name' => __('Container Background ', 'apc'),
                'desc' => __('', 'apc'),
                'fields' => $Conditinal_fields,
                'std' => false
            ));

            //Color field
            $options_panel->addColor('content_bg_color', array('name' => __('Story Background Color', 'apc'), 'std' =>'#f9f9f9', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/bg-color.png" style="width:125px;height:97px;"></div>', 'apc')));

            $options_panel->addColor('content_color', array('name' => __('Content Font Color', 'apc'),'std' =>'#666666', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/font-color.png" style="width:125px;height:97px;"></div>', 'apc')));
            $options_panel->addColor('title_color', array('name' => __('Story Title Color', 'apc'),'std' =>'#fff', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/title-color.png"></div>', 'apc')));

            $options_panel->addColor('circle_border_color', array('name' => __('Circle Color', 'apc'), 'std' =>'#222222', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/circle-color.png" style="width:100px;height:86px;"></div>', 'apc')));

            $options_panel->addColor('line_color', array('name' => __('Line Color', 'apc'), 'std' =>'#000', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/line.png" style="height:86px;"></div>', 'apc')));
            //Color field
            $options_panel->addColor('first_post', array('name' => __('First Color', 'apc'), 'std' =>'#02c5be', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/first.png" style="width:250px;"></div>', 'apc')));
            $options_panel->addColor('second_post', array('name' => __('Second Color', 'apc'), 'std' =>'#f12945', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/second.png" style="width:250px;"></div>', 'apc')));
            // $options_panel->addColor('third_post',array('name'=> __('Third Post','apc'),'std'=>array('#000'), 'desc' => __('','apc')));
            $options_panel->CloseTab();

			
			
            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_3');

            //title
            $options_panel->Title(__("Typography Settings", "apc"));
            $options_panel->addTypo('main_title_typo', array('name' => __("Main Title", "apc"), 'std' => array('size' => '22px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img-small"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/main-title.png" style="width:150px;"></div>', 'apc')));

            $options_panel->addTypo('post_title_typo', array('name' => __("Story Title", "apc"), 'std' => array('size' => '20px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img-small"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/story-title.png" style="width:150px;"></div>', 'apc')));

            $options_panel->addRadio('post_title_text_style', array('lowercase' => 'Lowercase',
                'uppercase' => 'Uppercase', 'capitalize' => 'Capitalize'), array('name' => __('Story Title Style ?', 'apc'), 'std' => array('capitalize'), 'desc' => __('<div class="info_img-small"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/story-title.png" style="width:150px;"></div>', 'apc')));

            $options_panel->addTypo('post_content_typo', array('name' => __("Story Content", "apc"), 'std' => array('size' => '14px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/story-content.png" style="width:150px;"></div>', 'apc')));



            $options_panel->CloseTab();

           

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_4');
            $options_panel->Title(__("Stories Settings", "apc"));

            //select field
            $options_panel->addSelect('scroll_effects', array('none' => 'No animation',
                'scale' => 'Scale',
                'fade' => 'FadeIn',
                // 'rotate' => 'Rotate',
                'slideout' => 'SlideOut',
                'flip' => 'Flip',
                    //  'rotate-skew'=>'Rotate Skew',
                    ), array('name' => __('Animation Effects ', 'apc'), 'std' => array('none'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('stories_images', array('popup' => 'In Popup',
                'single' => 'Story detail link','disable_links'=>'Disable links'), array('name' => __('Stories Images?', 'apc'), 'std' => array('popup'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('ctl_slideshow', array('true' => 'Enable',
                'false' => 'Disable'), array('name' => __('Stories slideshow ?', 'apc'), 'std' => array('true'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('slider_animation', array('slide' => 'Slide',
                'fade' => 'FadeIn'), array('name' => __('Slider animation ?', 'apc'), 'std' => array('slide'), 'desc' => __('', 'apc')));
            $options_panel->addText('animation_speed', array('name' => __('Slide Show Speed ?', 'apc'), 'std' => '5000', 'desc' => __('Enter the speed in milliseconds 1000 = 1 second', 'apc')));

            $options_panel->addText('default_icon', array('name' => __('Stories default icon', 'apc'), 'std' => '', 'desc' => __('Please add stories default  icon class from here <a target="_blank" href="http://fontawesome.io/icons">Font Awesome</a>', 'apc')));


            $options_panel->CloseTab();


            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_5');
            $options_panel->Title(__("Stories Date Settings", "apc"));
            $options_panel->addRadio('disable_months', array('yes' => 'Yes',
                'no' => 'no'), array('name' => __('Disable Stories Dates ?', 'apc'), 'std' => array('no'), 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));

            $options_panel->addRadio('ctl_date_formats', array('M d' => date('M d'),
                'F j, Y' => date('F j, Y'), 'Y-m-d' => date('Y-m-d'),
                'm/d/Y' => date('m/d/Y'), 'd/m/Y' => date('d/m/Y')
                    ), array('name' => __('Stories Date Formats ?', 'apc'), 'std' => array('M d'), 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));

            $options_panel->addText('custom_date_formats', array('name' => __('Custom date formats', 'apc'), 'std' => '', 'desc' => __('Stories date formats   e.g  D,M,Y <a  target="_blank" href="http://php.net/manual/en/function.date.php">Click here to view more</a>', 'apc')));

            $options_panel->addRadio('custom_date_style', array('no' => 'No(Default style)',
                'yes' => 'Yes'), array('name' => __('Enable custom date styles', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));

            $options_panel->addTypo('ctl_date_typo', array('name' => __("Stories date Font style", "apc"), 'std' => array('size' => '22px', 'color' => '#000000', 'face' => 'arial', 'style' => 'normal'), 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));
           
		   $options_panel->addRadio('custom_date_color', array('no' => 'No(Default style)',
                'yes' => 'Yes'), array('name' => __('Enable custom date Color', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));
		   $options_panel->addColor('ctl_date_color', array('name' => __('Stories date color', 'apc'), 'std' =>'#000000', 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/month.png" style="width:118px;"></div>', 'apc')));



            $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_7');
            $options_panel->Title(__("Timeline Scrolling Navigation settings", "apc"));
            $options_panel->addRadio('enable_navigation', array('yes' => 'Yes',
                'no' => 'no'), array('name' => __('Enable Scrolling  Navigation ?', 'apc'), 'std' => array('yes'), 'desc' => __('<div class="info_img"><img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/small-nav.png" style="width:66%;"></div>', 'apc')));

            $options_panel->addRadio('navigation_position', array(
                'left' => 'Left Side', 'right' => 'Right Side',
                    ), array('name' => __('Scrolling Navigation Position ?', 'apc'), 'std' => array('right'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('enable_pagination', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Enable Pagination ?', 'apc'), 'std' => array('yes'), 'desc' => __('', 'apc')));

            $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_6');
            /**
             * Editor options:
             * WYSIWYG (tinyMCE editor)
             * Syntax code editor (css,html,js,php)
             */
            //code editor field
            $options_panel->addCode('custom_styles', array('name' => 'Custom Styles', 'syntax' => 'css'));
// Close 3rd tab
            //title
            //  $options_panel->Title(__("Editor Options","apc"));
            //wysiwyg field
           // $options_panel->addWysiwyg('no_posts', array('name' => __('No Timeline Posts content', 'apc'), 'desc' => __('', 'apc')));

            $options_panel->CloseTab();

            /**
             * Open admin page third tab
             */
            $options_panel->OpenTab('options_8');
            //An optionl descrption paragraph
            $options_panel->addParagraph(__('<img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/timeline shortcode.png" style="width:100%">', "apc"));
            $options_panel->addParagraph(__('<img src="' . COOL_TIMELINE_PLUGIN_URL . '/admin-page-class/images/category-based timeline.png" style=" width:100%">', "apc"));
            $options_panel->addParagraph(__('Please use below added shortcode for default timeline. <br><br>
		<code><strong>[cool-timeline layout="default" skin="default" show-posts="20" order="DESC" icons="NO"] </strong> </code>', "apc"));

            $options_panel->addParagraph(__('Please use below added shortcode for multiple timeline (category based timeline). <br> <br> <code><strong>[cool-timeline  layout="default"  skin="default"  order="DESC" icons="NO" category="{add here story category id}" show-posts="20"] </strong></code>', "apc"));

          $options_panel->addParagraph(__('Horizontal Timeline. <br><br>
		<code><strong>[cool-timeline type="horizontal" category="{add here story category id}" skin="default" show-posts="20" order="DESC" icons="NO"]</strong> </code>', "apc"));

            $options_panel->addParagraph(__('Content Timeline(any post type). <br><br>
		<code><strong>[cool-timeline post-type="post" layout="default" skin="default" show-posts="20" order="DESC" icons="NO"]</strong> </code>', "apc"));

            $options_panel->CloseTab();

            $options_panel->OpenTab('options_9');
            //select field
            for ($p = 0; $p <= 100; $p++) {
                $pd = $p . 'px';
                $ctl_p_arr[$pd] = $pd;
            }

            $options_panel->addRadio('single_custom_template', array('yes' => 'Yes',
                'no' => 'no'), array('name' => __('Single Stories Custom Template ?', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));

            $options_panel->addRadio('disable_r_stories', array('yes' => 'Yes',
                'no' => 'No'), array('name' => __('Disable Related Stories ?', 'apc'), 'std' => array('no'), 'desc' => __('', 'apc')));
             $options_panel->addParagraph(__('<h2>Adjust Single page container spacing(padding).</h2>', "apc"));

            $options_panel->addSelect('ctl-padding-left', $ctl_p_arr, array('name' => 'Padding Left', 'std' => array("10px")));
            $options_panel->addSelect('ctl-padding-right', $ctl_p_arr, array('name' => 'Padding Right', 'std' => array("10px")));
            $options_panel->addSelect('ctl-padding-top', $ctl_p_arr, array('name' => 'Padding Top', 'std' => array("10px")));
            $options_panel->addSelect('ctl-padding-bottom', $ctl_p_arr, array('name' => 'Padding Bottom', 'std' => array("10px")));


            //radio field
            //
             $options_panel->CloseTab();
            //Now Just for the fun I'll add Help tabs
            $options_panel->HelpTab(array(
                'id' => 'tab_id',
                'title' => __('My help tab title', 'apc'),
                'content' => '<p>' . __('This is my Help Tab content', 'apc') . '</p>'
            ));
            $options_panel->HelpTab(array(
                'id' => 'tab_id2',
                'title' => __('My 2nd help tab title', 'apc'),
                'callback' => 'help_tab_callback_demo'
            ));

            //help tab callback function
            function help_tab_callback_demo() {
                echo '<p>' . __('This is my 2nd Help Tab content from a callback function', 'apc') . '</p>';
            }

        }

    
        public function ctl_add_tinymce() {
            global $typenow;
          
            if (!in_array($typenow, array('page', 'post')))
                return;
            add_filter('mce_external_plugins', array(&$this, 'ctl_add_tinymce_plugin'));
            add_filter('mce_buttons', array(&$this, 'ctl_add_tinymce_button'));
        }

       
        public function ctl_add_tinymce_plugin($plugin_array) {
            $plugin_array['cool_timeline'] = plugins_url('cool-timeline-pro/includes/js/shortcode-btn.js');
            $plugin_array['cool_timeline'] = plugins_url('cool-timeline-pro/includes/js/shortcode-btn.js');
            // Print all plugin js path
            // var_dump( $plugin_array );
            return $plugin_array;
        }

        // Add the button key for address via JS
        function ctl_add_tinymce_button($buttons) {
            array_push($buttons, 'cool_timeline_shortcode_button');
            // Print all buttons
            // var_dump( $buttons );
            return $buttons;
        }

        // end tinymce button functions           

        /**
         * Activate the plugin
         */
        public function activate() {

        }

        // END public static function activate

        /**
         * Deactivate the plugin
         */
        public function deactivate() {

        }

        public function clt_meta_boxes() {
            /*
             * configure your meta box
             */
            $config = array(
                'id' => 'demo_meta_box', // meta box id, unique per meta box 
                'title' => __('Timeline story settings', 'apc'), // meta box title
                'pages' => array('cool_timeline'), // post types, accept custom post types as well, default is array('post'); optional
                'context' => 'normal', // where the meta box appear: normal (default), advanced, side; optional
                'priority' => 'high', // order of meta box: high (default), low; optional
                'fields' => array(), // list of meta fields (can be added by field arrays) or using the class's functions
                'local_images' => false, // Use local or hosted images (meta box images for add/remove)
                'use_with_theme' => false            //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
            );

            for ($i = 1000; $i <= 2020; $i++) {
                $story_year_list[$i] = $i;
            }

            /*
             * Initiate your meta box
             */
            $my_meta = new AT_Meta_Box($config);

            /*
             * Add fields to your meta box
             */
            $my_meta->addSelect('ctl_story_year', $story_year_list, array('name' => 'Story Year  <span class="ctl_required">*</span>', 'desc' => '<p class="ctl_required">Please select story year.</p>', 'std' => array(date('Y'))));
            /* 	$my_meta->addDate('ctl_story_year',array('name'=> 'Story Year','desc'=>'des','std'=>date('Y'),'format'=>'yy'));
             */
            $my_meta->addDate('ctl_story_date', array('name' => 'Story Date <span class="ctl_required">*</span>', 'desc' => '<p class="ctl_required">Please select same year of story date.</p>', 'std' => date('m/d/Y'), 'format' => 'd MM yy'));
           //radio field
            $my_meta->addRadio('story_format', array('default' => __('Default', 'apc'), 'video' => __('Video', 'apc'), 'slideshow' => __('Slideshow', 'apc')), array('name' => __('Story Format', 'apc'), 'std' => array('default')));

            /*
             * To Create a reapeater Block first create an array of fields
             * use the same functions as above but add true as a last param
             */

            $repeater_fields[] = $my_meta->addImage('ctl_slide', array('name' => __('Slide', 'apc')), true);

            /*
             * Then just add the fields to the repeater block
             */
            //repeater block
            $my_meta->addRepeaterBlock('re_', array('inline' => true, 'name' => __('Add slideshow slides', 'apc'), 'fields' => $repeater_fields));
            /*
             * Don't Forget to Close up the meta box deceleration
             */

            $my_meta->addTextarea('ctl_video', array('name' => __('Add Youtube video url e.g <small>https://www.youtube.com/watch?v=PLHo6uyICVk</small>', 'apc')));

            $my_meta->addRadio('img_cont_size', array('full' => __('Full', 'apc'), 'small' => __('Small', 'apc')), array('name' => __('Story image size', 'apc'), 'std' => array('full')));

            //Finish Meta Box Deceleration
            $my_meta->Finish();
        }

    }

    //end class
}


/**
 *  Meta Box { Conditional Logic
 *
 *  @description: hide / show fields based on a "change" field

 */
add_action('admin_head', 'non_acf_conditional_logic');
if (!function_exists('non_acf_conditional_logic')) {

    function non_acf_conditional_logic() {
        global $current_screen;
        if ('page' == 'post-new.php' || 'post.php') {
            ?>
            <!-- code for select boxes -->
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#re_').parents('tr').hide();
                    $("#ctl_video").parents('tr').hide();
                    $("#img_cont_size").parents('tr').hide();
                    $('input[name="stroy_img_cont"]').parents('.rwmb-radio-wrapper').hide();
                    /**
                     * Adjust visibility of the meta box at startup
                     */

                    if ($('input[name="story_format"]:checked').val() == 'video') {
                        // show the meta box
                        $('input[name="img_cont_size"]').parents('tr').hide();
                        $("#ctl_video").parents('tr').show();

                    } else if ($('input[name="story_format"]:checked').val() == 'slideshow') {
                        $('input[name="img_cont_size"]').parents('tr').hide();
                        $('.at-repeat').parents('tr').show();
                    } else if ($(this).val() == 'default') {
                        // hide your meta box
                        $('input[name="img_cont_size"]').parents('tr').show();
                    }

                    /**
                     * Live adjustment of the meta box visibility
                     */
                    $('input[name="story_format"]').on('change', function () {

                        if ($(this).val() == 'video') {
                            // show the meta box
                            $('.at-repeat').parents('tr').hide();
                            $('input[name="img_cont_size"]').parents('tr').hide();
                            $("#ctl_video").parents('tr').show();
                        } else if ($(this).val() == 'slideshow') {
                            // show the meta box
                            $("#ctl_video").parents('tr').hide();
                            $('input[name="img_cont_size"]').parents('tr').hide();
                            $('.at-repeat').parents('tr').show();
                        }
                        else {
                            // hide your meta box
                            $("#ctl_video").parents('tr').hide();
                            $('.at-repeat').parents('tr').hide();
                            $('input[name="img_cont_size"]').parents('tr').show();
                        }
                    });
                });
            </script>
            <?php
        }
    }

}


foreach (array('post.php', 'post-new.php') as $hook) {
    add_action("admin_head-$hook", 'my_admin_head');
}

/**
 * Localize Script
 */
function my_admin_head() {
    $plugin_url = plugins_url('/', __FILE__);
    $terms = get_terms(array(
        'taxonomy' => 'ctl-stories',
        'hide_empty' => false,
    ));
    if (!empty($terms) || !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $ctl_terms_l[$term->term_id] = $term->name;
        }
    }


    if (isset($ctl_terms_l) && array_filter($ctl_terms_l) != null) {
        $category = json_encode($ctl_terms_l);
    } else {
        $category = json_encode(array('0' => 'No category'));
    }
    ?>
    <!-- TinyMCE Shortcode Plugin -->
    <script type='text/javascript'>
        var my_plugin = {
            'category': '<?php echo $category; ?>'
        };
    </script>
    <!-- TinyMCE Shortcode Plugin -->
    <?php
}

// instantiate the plugin class
$cool_timeline = new Cool_Timeline();
?>
