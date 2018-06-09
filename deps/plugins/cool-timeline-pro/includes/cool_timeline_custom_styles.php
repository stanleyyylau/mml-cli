<?php

if (!class_exists('Cooltimeline_Styles')) {

    class Cooltimeline_Styles {

        public function __construct() {
         //    add_action('wp_enqueue_scripts', array(&$this, 'ctl_custom_style_new'));

            // 2. Enqueue the stylesheet (using an absolute path, not a URL)
            wp_dynamic_css_enqueue( 'ctle-dynamic_css',COOL_TIMELINE_PLUGIN_DIR.'css/ctle-dynamic.css',false );

            // 3. Set the callback function (used to convert variables to actual values)
            wp_dynamic_css_set_callback( 'ctle-dynamic_css',  array(&$this,'ctle_dynamic_css_callback' ));

            add_action('wp_head', array(&$this, 'ctl_navigation_styles'));
        }


        public function  ctle_dynamic_css_callback( $var_name )
        {

            $clt_vars=array();
            $ctl_options_arr= get_option('cool_timeline_options');

            $clt_vars['disable_months'] = $ctl_options_arr['disable_months'] ? $ctl_options_arr['disable_months'] : "no";
            $clt_vars['title_alignment'] = $ctl_options_arr['title_alignment'] ? $ctl_options_arr['title_alignment'] : "center";

            /*
             * Style options
             */

            $background_type= isset($ctl_options_arr['background']['enabled']) ? $ctl_options_arr['background']['enabled'] : '';

            if ($background_type == 'on') {
                $clt_vars['bg_color'] = $ctl_options_arr['background']['bg_color'] ? $ctl_options_arr['background']['bg_color'] : 'none';
            }
            $clt_vars['first_post_color'] = null !== $ctl_options_arr['first_post'] && $ctl_options_arr['first_post']?$ctl_options_arr['first_post'] : "#02c5be";

            $clt_vars['second_post_color'] = null !== $ctl_options_arr['second_post'] && $ctl_options_arr['second_post']?$ctl_options_arr['second_post'] : "#f12945";


            $clt_vars['content_bg_color'] = null !== $ctl_options_arr['content_bg_color'] && $ctl_options_arr['content_bg_color']?$ctl_options_arr['content_bg_color'] : '#f9f9f9';

            $clt_vars['content_color'] = null !== $ctl_options_arr['content_color'] && $ctl_options_arr['content_color']?$ctl_options_arr['content_color'] : '#666666';

            $clt_vars['title_color'] = null !== $ctl_options_arr['title_color']? $ctl_options_arr['title_color'] : '#fff';

            $clt_vars['circle_border_color'] = null !== $ctl_options_arr['circle_border_color'] && $ctl_options_arr['circle_border_color']?$ctl_options_arr['circle_border_color'] : '#333333';

            $clt_vars['main_title_color'] = isset($ctl_options_arr['main_title_color']) && null !== $ctl_options_arr['main_title_color']?$ctl_options_arr['main_title_color'] : '#000';


            /*
             * Typography options
             */

            $ctl_main_title_typo = $ctl_options_arr['main_title_typo'];
            $ctl_post_title_typo = $ctl_options_arr['post_title_typo'];
            $ctl_post_content_typo = $ctl_options_arr['post_content_typo'];

            $ctl_date_typo = $ctl_options_arr['ctl_date_typo'];
            $custom_date_style = $ctl_options_arr['custom_date_style'];
            $custom_date_color=$clt_vars['custom_date_color'] = $ctl_options_arr['custom_date_color'];

            $clt_vars['post_title_text_style'] = $ctl_options_arr['post_title_text_style'] ? $ctl_options_arr['post_title_text_style'] : 'capitalize';

            $clt_vars['main_title_f'] = $ctl_main_title_typo['face'] ? $ctl_main_title_typo['face'] : 'inherit';
            $clt_vars['main_title_w'] = $ctl_main_title_typo['weight'] ? $ctl_main_title_typo['weight'] : 'inherit';
            $clt_vars['main_title_s'] = $ctl_main_title_typo['size'] ? $ctl_main_title_typo['size'] : '22px';


            $clt_vars['events_body_f'] = $ctl_post_content_typo['face'] ? $ctl_post_content_typo['face'] : 'inherit';
            $clt_vars['events_body_w'] = $ctl_post_content_typo['weight'] ? $ctl_post_content_typo['weight'] : 'inherit';
            $clt_vars['events_body_s'] = $ctl_post_content_typo['size'] ? $ctl_post_content_typo['size'] : 'inherit';

            $clt_vars['post_title_f'] = $ctl_post_title_typo['face'] ? $ctl_post_title_typo['face'] : 'inherit';
            $clt_vars['post_title_w'] = $ctl_post_title_typo['weight'] ? $ctl_post_title_typo['weight'] : 'inherit';
            $clt_vars['post_title_s'] = $ctl_post_title_typo['size'] ? $ctl_post_title_typo['size'] : '20px';

            $clt_vars['post_content_f'] = $ctl_post_content_typo['face'] ? $ctl_post_content_typo['face'] : 'inherit';
            $clt_vars['post_content_w'] = $ctl_post_content_typo['weight'] ? $ctl_post_content_typo['weight'] : 'inherit';
            $clt_vars['post_content_s'] = $ctl_post_content_typo['size'] ? $ctl_post_content_typo['size'] : 'inherit';

            if ($custom_date_style == "yes") {
                $clt_vars['ctl_date_f'] = $ctl_date_typo['face'] ? $ctl_date_typo['face'] : 'inherit';
                $clt_vars['ctl_date_w'] = $ctl_date_typo['weight'] ? $ctl_date_typo['weight'] : 'inherit';
                $clt_vars['ctl_date_s'] = $ctl_date_typo['size'] ? $ctl_date_typo['size'] : 'inherit';

            }

            if ($custom_date_color == "yes") {
                $clt_vars['ctl_date_color'] = null !== $ctl_options_arr['ctl_date_color'] && $ctl_options_arr['ctl_date_color']?$ctl_options_arr['ctl_date_color'] : '#fff';
            }
            /*
             * single page styles
             */
            $clt_vars['ctl_padding_left'] = $ctl_options_arr['ctl-padding-left'] ? $ctl_options_arr['ctl-padding-left'] : '10px';
            $clt_vars['ctl_padding_right'] = $ctl_options_arr['ctl-padding-right'] ? $ctl_options_arr['ctl-padding-right'] : '10px';
            $clt_vars['ctl_padding_top'] = $ctl_options_arr['ctl-padding-top'] ? $ctl_options_arr['ctl-padding-top'] : '10px';
            $clt_vars['ctl_padding_bottom'] = $ctl_options_arr['ctl-padding-bottom'] ? $ctl_options_arr['ctl-padding-bottom'] : '10px';

            $disable_r_stories = $ctl_options_arr['disable_r_stories'] ? $ctl_options_arr['disable_r_stories'] : 'no';

            $clt_vars['line_color'] = null !== $ctl_options_arr['line_color'] && $ctl_options_arr['line_color']  ? $ctl_options_arr['line_color'] : '#000';

            $clt_vars['custom_styles'] =isset($ctl_options_arr['custom_styles']) ? $ctl_options_arr['custom_styles'] : '';

            return $clt_vars[$var_name];

        }

       function ctl_navigation_styles() {
            $ctl_options_arr = get_option('cool_timeline_options');
            //var_dump($ctl_options_arr['navigation_position']);
            $navigation_position = $ctl_options_arr['navigation_position'] ? $ctl_options_arr['navigation_position'] : 'right';
            $output = '<style type="text/css">
                    .bullets-container {
                display: table;
                position: fixed;
                ' . $navigation_position . ': 0;
                height: 100%;
                z-index: 1049;
                font-weight: normal;
            }
	</style>';

            echo $output;
        }  
        
    }

}