<?php

if (!class_exists('CoolTimeline_Template')) {

    class CoolTimeline_Template {

        /**
         * The Constructor
         */
        public function __construct() {
            // register actions
            add_action('init', array(&$this, 'cooltimeline_register_shortcode'));
            add_action('wp_enqueue_scripts', array(&$this, 'ctl_load_scripts_styles'));
         
            // Call actions and filters in after_setup_theme hook
            add_action('after_setup_theme', array(&$this, 'ctl_custom_read_more'));
            add_filter('excerpt_length', array(&$this, 'ctl_custom_excerpt_length'), 999);
           

            /* load a custom page template (override the default) */
            add_filter('single_template', array($this, 'ctl_single_page_template'));
        }

        function ctl_custom_read_more() {

            // add more link to excerpt
            function ctl_custom_excerpt_more($more) {
                global $post;
                $ctl_options_arr = get_option('cool_timeline_options');
                $r_more= $ctl_options_arr['display_readmore']?$ctl_options_arr['display_readmore']:"yes";
                if ($post->post_type == 'cool_timeline' && !is_single()) {
                    if ($r_more == 'yes') {
                        return '..<a class="read_more ctl_read_more" href="' . get_permalink($post->ID) . '">' . __('Read more', 'cool-timeline') . '</a>';
                    }
                } else {
                    return $more;
                }
            }

            add_filter('excerpt_more', 'ctl_custom_excerpt_more', 999);
        }

        function ctl_custom_excerpt_length($length) {
            global $post;
            $ctl_options_arr = get_option('cool_timeline_options');
            $ctl_content_length = $ctl_options_arr['content_length'] ? $ctl_options_arr['content_length'] : 100;
            if ($post->post_type == 'cool_timeline' && !is_single()) {
                return $ctl_content_length;
            }
            return $length;
        }

        function cooltimeline_register_shortcode() {
            add_shortcode('cool-timeline', array(&$this, 'cooltimeline_view'));

        }


        function cooltimeline_view($atts, $content = null) {

            $attribute = shortcode_atts(array(
                'class' => 'caption',
                'show-posts' => '',
                'order' => '',
                'category' => 0,
                'taxonomy'=>'',
                'layout' => 'default',
                'skin' =>'',
                'type'=>'',
                'icons' =>'',
                'fb-app-id'=>'',
                'fb-app-secret-key'=>'',
                'fb-page-name'=>'',
                'post-type'=>''
            ), $atts);

          if( isset( $attribute['type']) &&  $attribute['type']=="horizontal"){
                wp_enqueue_style('ctl-styles-horizontal');
                wp_enqueue_style('ctl_prettyPhoto');
                wp_enqueue_script('ctl_prettyPhoto');
                wp_enqueue_script('ctl-slick-js');
                wp_enqueue_script('ctl_horizontal_scripts');
                wp_enqueue_style('ctl_prettyPhoto');
                wp_enqueue_style('ctl-styles-slick');

                $clt_hori_view='';
                $ctl_options_arr = get_option('cool_timeline_options');
                  wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');

              require('views/horizontal-timeline.php');

                return $clt_hori_view;

            }
          elseif( isset( $attribute['type']) &&  $attribute['type']=="social"){
              wp_enqueue_style('ctl-social-timeline-style');
              wp_enqueue_script('ctl-social-timeline-mod');
              wp_enqueue_script('ctl-social-timeline');
              wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
              $out = '';
              $ctl_options_arr = get_option('cool_timeline_options');
              require('views/social-timeline.php');
                return $out;
            }
          else if( isset( $attribute['post-type']) && !empty( $attribute['post-type'])) {

                wp_enqueue_style('ctl_styles');
                wp_enqueue_style('ctl_prettyPhoto');
                wp_enqueue_style('ctl_flexslider_style');
                wp_enqueue_style('section-scroll');
                wp_enqueue_script('ctl_skrollr');
                wp_enqueue_script('ctl_prettyPhoto');
                wp_enqueue_script('ctl_scripts');
                wp_enqueue_script('ctl_jquery_flexslider');
                wp_enqueue_script('section-scroll-js');

                  wp_enqueue_style('ctl-styles-horizontal');
                  wp_enqueue_script('ctl-slick-js');
                  wp_enqueue_script('ctl_horizontal_scripts');

                  wp_enqueue_style('ctl-styles-slick');

                wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
                 $output='';
                require('views/content-timeline.php');

              return $output;
            }
            else {

                wp_enqueue_style('ctl_styles');
                wp_enqueue_style('ctl_prettyPhoto');
                wp_enqueue_style('ctl_flexslider_style');
                wp_enqueue_style('section-scroll');
                wp_enqueue_script('ctl_skrollr');
                wp_enqueue_script('ctl_prettyPhoto');
                wp_enqueue_script('ctl_scripts');
                wp_enqueue_script('ctl_jquery_flexslider');
                wp_enqueue_script('section-scroll-js');

                wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');


                $ctl_options_arr = get_option('cool_timeline_options');
                $timeline_skin = isset($attribute['skin']) ? $attribute['skin'] : 'default';
                $wrp_cls = '';
                $wrapper_cls = '';
                $post_skin_cls = '';
                if ($timeline_skin == "light") {
                    $wrp_cls = 'light-timeline';
                    $wrapper_cls = 'light-timeline-wrapper';
                    $post_skin_cls = 'white-post';
                } else if ($timeline_skin == "dark") {
                    $wrp_cls = 'dark-timeline';
                    $wrapper_cls = 'dark-timeline-wrapper';
                    $post_skin_cls = 'black-post';
                } else {
                    $wrp_cls = 'white-timeline';
                    $post_skin_cls = 'light-grey-post';
                    $wrapper_cls = 'white-timeline-wrapper';
                }
                $output = '';
                $ctl_html = '';
                $ctl_format_html = '';
                /*
                 * Gerneral options
                 */

                //  $ctl_timeline_type = $ctl_options_arr['timeline_type'];
                $ctl_title_text = $ctl_options_arr['title_text'];
                $ctl_title_tag = $ctl_options_arr['title_tag'];
                //  $ctl_title_pos = $ctl_options_arr['title_pos'];

                $scroll_effects = $ctl_options_arr['scroll_effects'] ? $ctl_options_arr['scroll_effects'] : 'none';
                $stories_images_link = $ctl_options_arr['stories_images'];


              //  var_dump($stories_images_link);

                switch ($scroll_effects) {
                    case 'scale':
                        $animation_type = 'transform: scale(0.5);transition: all 0.5s';
                        $animation_type_end = 'transform: scale(1);';
                        $ctl_animation = 'data--20-bottom-top="' . $animation_type . '"data--50-bottom-bottom="' . $animation_type_end . '"';
                        break;
                    case 'fade':
                        $animation_type = 'opacity:0;transition: all 0.5s;';
                        $animation_type_end = 'opacity:1;';
                        $ctl_animation = 'data--40-bottom-top="' . $animation_type . '"data--60-bottom-bottom="' . $animation_type_end . '"';
                        break;
                    case 'slideout':
                        $animation_type = 'opacity: 0;left: 50px;';
                        $animation_type_end = 'opacity: 1;left: 0;';
                        $ctl_animation = 'data--20-bottom-top="' . $animation_type . '"data--50-bottom-bottom="' . $animation_type_end . '"';
                        break;

                    case 'flip':
                        $animation_type = 'transform: rotateX(-90deg); opacity: 0;';
                        $animation_type_end = 'transform: rotateX(0deg); opacity: 1;';
                        $ctl_animation = 'data--10-bottom-top="' . $animation_type . '"data--80-bottom-bottom="' . $animation_type_end . '"';
                        break;

                    case 'rotate-skew':
                        $animation_type = 'transform:scale(0) rotate(0deg);';
                        $animation_type_end = 'transform:scale(1) rotate(1440deg);opacity:1;';
                        $ctl_animation = 'data--40-bottom-top="' . $animation_type . '"data--80-bottom-bottom="' . $animation_type_end . '"';
                        break;

                    case 'none':
                        $ctl_animation = '';
                        break;

                    default;
                        $animation_type = 'opacity:0;transition: all 0.5s;';
                        $animation_type_end = 'opacity:1;';
                        $ctl_animation = 'data--40-bottom-top="' . $animation_type . '"data--60-bottom-bottom="' . $animation_type_end . '"';
                        break;
                }
                if (isset($ctl_options_arr['user_avatar']['id'])) {
                    $user_avatar = wp_get_attachment_image_src($ctl_options_arr['user_avatar']['id'], 'ctl_avatar');
                }

                /*
                 * content settings
                 */

                $default_icon = isset($ctl_options_arr['default_icon'])?$ctl_options_arr['default_icon']:'';
                $ctl_post_per_page = $ctl_options_arr['post_per_page'];
                $story_desc_type = $ctl_options_arr['desc_type'];
                // $ctl_no_posts = isset($ctl_options_arr['no_posts']) ? $ctl_options_arr['no_posts'] : "No timeline story found";
                $ctl_content_length = $ctl_options_arr['content_length'];
                $ctl_posts_orders = $ctl_options_arr['posts_orders'] ? $ctl_options_arr['posts_orders'] : "DESC";
                $disable_months = $ctl_options_arr['disable_months'] ? $ctl_options_arr['disable_months'] : "no";
                $title_alignment = $ctl_options_arr['title_alignment'] ? $ctl_options_arr['title_alignment'] : "center";

                $title_visibilty = $ctl_options_arr['display_title'] ? $ctl_options_arr['display_title'] : "yes";

                $slider_animation = $ctl_options_arr['slider_animation'] ? $ctl_options_arr['slider_animation'] : "slide";
                $ctl_slideshow = $ctl_options_arr['ctl_slideshow'] ? $ctl_options_arr['ctl_slideshow'] : true;
                $animation_speed = isset($ctl_options_arr['animation_speed']) ? $ctl_options_arr['animation_speed'] : 7000;
                //$ctl_posts_order='date';

                $enable_navigation = $ctl_options_arr['enable_navigation'] ? $ctl_options_arr['enable_navigation'] : 'yes';
                $navigation_position = $ctl_options_arr['navigation_position'] ? $ctl_options_arr['navigation_position'] : 'right';

                $enable_pagination = $ctl_options_arr['enable_pagination'] ? $ctl_options_arr['enable_pagination'] : 'no';

                /*
                 * images sizes
                 */
                $ctl_post_per_page = $ctl_post_per_page ? $ctl_post_per_page : 10;
                $ctl_avtar_html = '';
                $timeline_id = '';
                    $clt_icons='';

                if (isset($attribute['icons']) && $attribute['icons']=="YES"){
                    $clt_icons='icons_yes';
                }else{
                    $clt_icons='icons_no';
                }

                if ($attribute['category']) {
                    $ctl_term = get_term_by('id', $attribute['category'], 'ctl-stories');
                    if ($ctl_term->name == "Timeline Stories") {
                        $ctl_title_text = $ctl_title_text;
                    } else {
                        $ctl_title_text = $ctl_term->name;
                    }
                    $catId = $attribute['category'];
                    $timeline_id = "timeline-$catId";
                } else {
                    $ctl_title_text = $ctl_title_text ? $ctl_title_text : 'Timeline';
                    $timeline_id = "timeline-1";
                }
                if ($attribute['category']) {
                    if (ctl_taxonomy_image($attribute['category'], 'ctl_avatar') !== FALSE) {
                        $ctl_avtar_html .= '<div class="avatar_container row"><span title="' . $ctl_title_text . '">';
                        $ctl_avtar_html .= ctl_taxonomy_image($attribute['category'], 'ctl_avatar', array('alt' => $ctl_title_text, 'class' => 'center-block img-responsive img-circle'));
                        $ctl_avtar_html .= '</span></div> ';
                    } else {
                        if (isset($user_avatar[0]) && !empty($user_avatar[0])) {
                            $ctl_avtar_html .= '<div class="avatar_container row"><span title="' . $ctl_title_text . '"><img  class=" center-block img-responsive img-circle" alt="' . $ctl_title_text . '" src="' . $user_avatar[0] . '"></span></div> ';
                        }
                    }
                } else {

                    if (isset($user_avatar[0]) && !empty($user_avatar[0])) {
                        $ctl_avtar_html .= '<div class="avatar_container row"><span title="' . $ctl_title_text . '"><img  class=" center-block img-responsive img-circle" alt="' . $ctl_title_text . '" src="' . $user_avatar[0] . '"></span></div> ';
                    }
                }
                $ctl_html_no_cont = '';

                $ctl_title_tag = $ctl_title_tag ? $ctl_title_tag : 'H2';
                //$ctl_title_pos = $ctl_title_pos ? $ctl_title_pos : 'left';
                $ctl_content_length ? $ctl_content_length : 100;
                if (isset($ctl_options_arr['custom_date_formats']) && !empty($ctl_options_arr['custom_date_formats'])) {
                    $date_formats = $ctl_options_arr['custom_date_formats'] ? $ctl_options_arr['custom_date_formats'] : "M d";
                } else {
                    $date_formats = $ctl_options_arr['ctl_date_formats'] ? $ctl_options_arr['ctl_date_formats'] : "M d";
                }
                $layout_wrp = '';
                if ($title_visibilty == "yes") {

                }
                if ($attribute['type']=="compact") {
                    require("views/compact-timeline.php");
                }else{
                    require("views/default.php");
                }


    $output .= '
  <!-- Cool timeline
  ================================================== -->';
    if ($attribute['type']=="compact") {
        $output .= '<div class="cool_timeline cool-timeline-wrapper  cool-compact-timeline ' . $layout_wrp . ' ' . $wrapper_cls . '" data-pagination="' . $enable_navigation . '"  data-pagination-position="' . $navigation_position . '">';
    }else {
     $output .= '<div class="cool_timeline cool-timeline-wrapper  ' . $layout_wrp . ' ' . $wrapper_cls . '" data-pagination="' . $enable_navigation . '"  data-pagination-position="' . $navigation_position . '">';
    }
                $output .= $ctl_avtar_html;
                if ($title_visibilty == "yes") {
                    $output .= sprintf(__('<%s class="timeline-main-title center-block">%s</%s>', 'cool-timeline'), $ctl_title_tag, $ctl_title_text, $ctl_title_tag);
                }
                if ($attribute['type']=="compact") {
                    $output .= '<div class="cool_timeline js-isotope-grid ' . $layout_cls . ' ' . $wrp_cls . '">';
                    $output .= '<div id="' . $timeline_id . '" class="cool-compact-loop '.$clt_icons.'"><div class="th-timeline-line"></div>';
                }else {
                    $output .= '<div class="cool-timeline ultimate-style ' . $layout_cls . ' ' . $wrp_cls . '">';
                    $output .= '<div id="' . $timeline_id . '" class="cooltimeline_cont  clearfix '.$clt_icons.'">';
                }
                //

                $output .= $ctl_html;
                $output .= $ctl_html_no_cont;
                $output .= '</div>
			</div>

    </div>  <!-- end
 ================================================== -->';
                return $output;

            }

        }

        /*
         * Include this plugin's public JS & CSS files on posts.
         */

        function ctl_load_scripts_styles() {

            wp_register_script('ctl_skrollr', COOL_TIMELINE_PLUGIN_URL . 'js/skrollr.min.js', array(), null, true);
            wp_register_script('ctl_prettyPhoto', COOL_TIMELINE_PLUGIN_URL . 'js/jquery.prettyPhoto.js', array('jquery'), null, true);
            wp_register_script('ctl_scripts', COOL_TIMELINE_PLUGIN_URL . 'js/ctl_scripts.js', array('jquery'), null, true);
            wp_register_script('ctl_jquery_flexslider', COOL_TIMELINE_PLUGIN_URL . 'js/jquery.flexslider-min.js', array('jquery'), null, true);
            wp_register_script('section-scroll-js', COOL_TIMELINE_PLUGIN_URL . 'js/jquery.section-scroll.js', array('jquery'), null, true);
            wp_register_style('ctl_styles', COOL_TIMELINE_PLUGIN_URL . 'css/ctl_styles.css', null, null, 'all');
            wp_register_style('ctl_prettyPhoto', COOL_TIMELINE_PLUGIN_URL . 'css/prettyPhoto.css', null, null, 'all');
            wp_register_style('section-scroll', COOL_TIMELINE_PLUGIN_URL . 'css/section-scroll.css', null, null, 'all');
            wp_register_style('ctl_flexslider_style', COOL_TIMELINE_PLUGIN_URL . 'css/flexslider.css', null, null, 'all');


            /*
             * Horizontal timeline
             */

            wp_register_script('ctl_horizontal_scripts', COOL_TIMELINE_PLUGIN_URL . 'js/ctl_horizontal_scripts.js', array('jquery'), null, true);
            wp_register_script('ctl-slick-js', COOL_TIMELINE_PLUGIN_URL . 'js/slick.js', array('jquery'), null, true);
            wp_register_style('ctl-styles-horizontal', COOL_TIMELINE_PLUGIN_URL . 'css/ctl-styles-horizontal.css', null, null, 'all');
            wp_register_style('ctl-styles-slick', COOL_TIMELINE_PLUGIN_URL . 'css/slick.css', null, null, 'all');

             /*
             * social timeline
             */
            wp_register_script('ctl-social-timeline-mod', COOL_TIMELINE_PLUGIN_URL . 'js/modernizr.js', array('jquery'), null, true);
            wp_register_script('ctl-social-timeline', COOL_TIMELINE_PLUGIN_URL . 'js/social-main.js', array('jquery'), null, true);
            wp_register_style('ctl-social-timeline-style', COOL_TIMELINE_PLUGIN_URL . 'css/social-timeline-style.css', null, null, 'all');



            $ctl_options_arr = get_option('cool_timeline_options');
            $post_content_face = $ctl_options_arr['post_content_typo']['face'];
            $post_title = $ctl_options_arr['post_title_typo']['face'];
            $main_title = $ctl_options_arr['main_title_typo']['face'];
            $date_typo = $ctl_options_arr['ctl_date_typo']['face'];
            $selected_fonts = array($post_content_face, $post_title, $main_title,$date_typo);

            /*
            * google fonts
            */
            // Remove any duplicates in the list
            $selected_fonts = array_unique($selected_fonts);
            // If it is a Google font, go ahead and call the function to enqueue it
            foreach ($selected_fonts as $font) {
                if ($font != 'inherit') {

                    // Certain Google fonts need slight tweaks in order to load properly
                    // Like our friend "Raleway"
                    if ($font == 'Raleway')
                        $font = 'Raleway:100';
                    $font = str_replace(" ", "+", $font);
                    if ($font) {
                        wp_enqueue_style("ctl_gfonts$font", "http://fonts.googleapis.com/css?family=$font", false, null, 'all');
                    }
                    wp_enqueue_style("ctl_default_fonts", "http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800", false, null, 'all');

                    /*
                     * End
                     * 
                     */
                }
            }


            if (is_singular('cool_timeline')) {
                wp_enqueue_style('ctl_styles');
                wp_enqueue_style('ctl_prettyPhoto');
                wp_enqueue_style('ctl_flexslider_style');
                wp_enqueue_script('ctl_prettyPhoto');
                wp_enqueue_script('ctl_scripts');
                wp_enqueue_script('ctl_jquery_flexslider');
                wp_enqueue_style('font-awesome', 'https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
            }

        }



        function safe_strtotime($string, $format) {
            if ($string) {
                $date = date_create($string);
                if (!$date) {
                    $e = date_get_last_errors();
                    foreach ($e['errors'] as $error) {
                        return "$error\n";
                    }
                    exit(1);
                }
               return date_format($date, __("$format", 'cool-timeline'));

            } else {
                return false;
            }
        }

        public function ctl_single_page_template($single_template) {
            global $post;
			$ctl_options_arr = get_option('cool_timeline_options');
			$custom_single=$ctl_options_arr['single_custom_template'];			
		
            if (!isset($post->post_type) || 'cool_timeline' !== $post->post_type) {
                return $single_template;
            } else {
				if($custom_single=="yes"){
               
                if (file_exists(get_stylesheet_directory() . '/cool-timeline/single-cool-timeline.php')) {
                    $single_template = get_stylesheet_directory() . '/cool-timeline/single-cool-timeline.php';
                } else {
                    $single_template = COOL_TIMELINE_PLUGIN_DIR . 'templates/single-cool-timeline.php';
                }
				return $single_template;
				}else{
					return $single_template;
				}

                
            }
        }

    }

} // end class


