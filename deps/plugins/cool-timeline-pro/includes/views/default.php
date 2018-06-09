<?php$format = __('d/M/Y', 'cool-timeline');$output = '';$year_position = 2;$display_year ='';$args = array();$cat_timeline = array();if ($attribute['category']) {    $category = $attribute['category'];    $args['tax_query'] = array(        array(            'taxonomy' => 'ctl-stories',            'field' => 'term_id',            'terms' => $attribute['category'],        ),    );}$args['post_type'] = 'cool_timeline';if ($attribute['show-posts']) {    $args['posts_per_page'] = $attribute['show-posts'];} else {    $args['posts_per_page'] = $ctl_post_per_page;}$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;$args['post_status'] = array('publish', 'future');$args['post_type'] = 'cool_timeline';if ($enable_pagination == "yes") {    $args['paged'] = $paged;}$stories_order = '';if ($attribute['order']) {    $args['order'] = $attribute['order'];    $stories_order = $attribute['order'];} else {    $args['order'] = $ctl_posts_orders;    $stories_order = $ctl_posts_orders;}$args['meta_query'] = array(    'relation' => 'AND',    'ctl_story_year' => array(        'key' => 'ctl_story_year',        'compare' => 'EXISTS',    ),    'ctl_story_date' => array(        'key' => 'ctl_story_date',        'compare' => 'EXISTS',    ),);$args['orderby'] = array(    'ctl_story_year' => $stories_order,    'ctl_story_date' => $stories_order,);$spy_ele = '';$i = 0;$row = 1;$ctl_html_no_cont = '';if ($attribute['layout'] == "one-side") {    $layout_cls = 'one-sided';    $layout_wrp = 'one-sided-wrapper';} else {    $layout_cls = '';    $layout_wrp = 'both-sided-wrapper';}$ctl_loop = new WP_Query($args);if ($ctl_loop->have_posts()) {    while ($ctl_loop->have_posts()) : $ctl_loop->the_post();        global $post;        $story_format = get_post_meta($post->ID, 'story_format', true);        $img_cont_size = get_post_meta($post->ID, 'img_cont_size', true);        $ctl_story_date = get_post_meta($post->ID, 'ctl_story_date', true);        switch ($img_cont_size) {            case'Full':                $cont_size_cls = 'full';                break;            case'small':                $cont_size_cls = 'small';                break;            default;                $cont_size_cls = 'full';                break;        }        if (isset($cont_size_cls) && !empty($cont_size_cls)) {            $container_cls = $cont_size_cls;        } else {            $container_cls = 'full';        }        /*        * Display By date        */// $post_date = explode('/', get_the_date($format));//    $post_year = $post_date[$year_position];        $post_date = explode('/', get_the_date($ctl_story_date));        $post_year = (int)$post_date[$year_position];        if ($story_desc_type == 'full') {            $story_cont = apply_filters('the_content', $post->post_content);        } else {            $story_cont = "<p>" . get_the_excerpt() . "</p>";        }        if ('' != $story_cont) {            $post_content = $story_cont;        }        if (preg_match("/\d{4}/", $ctl_story_date, $match)) {            $year = intval($match[0]); //converting the year to integer            if ($year >= 1970) {                $posted_date = date_i18n(__("$date_formats", 'cool-timeline'), strtotime("$ctl_story_date"));            } else {                $posted_date = $this->safe_strtotime($ctl_story_date, "$date_formats");            }        }//$posted_date=get_the_date(__("$date_formats",'cool-timeline'));        if ($cont_size_cls == "full") {            $ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');        } else {            $ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');        }        $ctl_thumb_url = $ctl_thumb['0'];        $ctl_thumb_width = $ctl_thumb['1'];        $ctl_thumb_height = $ctl_thumb['2'];        $s_l_close='';        if ($stories_images_link =="popup") {            $img_f_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));            $story_img_link = '<a title="' . get_the_title() . '"  href="' . $img_f_url . '" class="ctl_prettyPhoto">';            $s_l_close='</a>';        } else if ($stories_images_link == "single") {            $story_img_link = '<a title="' . get_the_title() . '"  href="' . get_the_permalink() . '" class="single-page-link">';            $s_l_close='</a>';        } else if ($stories_images_link == "disable_links") {             $story_img_link = '';              $s_l_close='';        }        else {            $s_l_close='';            $story_img_link = '<a title="' . get_the_title() . '"  href="' . get_the_permalink() . '" class="">';        }//video format        if ($story_format == "video") {            //$ctl_video=rwmb_meta('ctl_video' ,'type=oembed' );            $ctl_video = get_post_meta($post->ID, 'ctl_video', true);            if ($ctl_video) {                // $url = 'https://www.youtube.com/watch?v=u9-kU7gfuFA'                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $ctl_video, $matches);                $id = $matches[1];                if ($id) {                    $width = '100%';                    $height = '100%';                    $iframe = '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe>';                    $ctl_format_html .= '<div class="full-width">' . $iframe . '</div>';                }            }        } else if ($story_format == "slideshow") {            //$ctl_slides=rwmb_meta('ctl_slides');            $d = get_post_meta($post->ID, 're_', false);            $ctl_slides = array();            if ($d && is_array($d[0])) {                foreach ($d[0] as $key => $images) {                    $ctl_slides[] = $images['ctl_slide']['id'];                }            }            $slides_html = '';            $ctl_format_html .= '<div class="full-width  ctl_slideshow">';            if (array_filter($ctl_slides)) {                $ctl_format_html .= '<div data-animationSpeed="' . $animation_speed . '"  data-slideshow="' . $ctl_slideshow . '" data-animation="' . $slider_animation . '" class="ctl_flexslider"><ul class="slides">';                foreach ($ctl_slides as $key => $att_index) {                    $slides = wp_get_attachment_image_src($att_index, 'large');                    if ($slides[0]) {                        $sld = $slides[0];                        $slides_html .= '<li><img src="' . $sld . '"></li>';                    }                }                $ctl_format_html .= $slides_html . '</ul></div>';            }            $ctl_format_html .= '</div>';        } else {            if (isset($ctl_thumb_url) && !empty($ctl_thumb_url)) {                if ($cont_size_cls == "full") {                    $ctl_format_html .= '<div class="full-width">' . $story_img_link . '<img  class="events-object" src="' . $ctl_thumb_url . '">'.$s_l_close.'</div>';                } else {                    $s_img_w = $ctl_thumb_width / 2;                    $s_img_h = $ctl_thumb_height / 2;                    $ctl_format_html .= '<div class="pull-left">' . $story_img_link . '<img width="' . $s_img_w . '" height="' . $s_img_h . '" class="events-object left_small" src="' . $ctl_thumb_url . '">'.$s_l_close.'</div>';                }            }        }        if ($i % 2 == 0) {            $even_odd = "even";        } else {            $even_odd = "odd";        }        if ($post_year != $display_year) {            $display_year = $post_year;            $ctle_year_lbl = sprintf('<span class="ctl-timeline-date">%s</span>', $post_year);            if (isset($attribute['icons']) && $attribute['icons'] == "YES") {                $ctl_html .= '<div class="timeline-year  scrollable-section" data-section-title="' . $post_year . '" id="clt-' . $post_year . '">            <div class="icon-placeholder">' . $ctle_year_lbl . '</div>            <div class="timeline-bar"></div>        </div>';            } else {            $ctl_html .= '<div class="timeline-year scrollable-section"     data-section-title="' . $post_year . '" id="clt-' . $post_year . '">            <div class="icon-placeholder">' . $ctle_year_lbl . '</div>            <div class="timeline-bar"></div>        </div>';        }        }        $ctl_html .= '<!-- .timeline-post-start-->';        $ctl_html .= '<div class="timeline-post ' . $even_odd . ' ' . $post_skin_cls .' '.$clt_icons . '">            <div class="timeline-meta">';        if ($disable_months == "no") {            $ctl_html .= '<div class="meta-details">' . $posted_date . '</div>';        }        $ctl_html .= '</div>';		if(function_exists('get_fa')){        $post_icon=get_fa(true);		}        if(isset($post_icon)){            $icon=$post_icon;        }else{            if(isset($default_icon)&& !empty($default_icon)){                $icon='<i class="fa '.$default_icon.'" aria-hidden="true"></i>';            }else {                $icon = '<i class="fa fa-clock-o" aria-hidden="true"></i>';            }        }  if (isset($attribute['icons']) && $attribute['icons'] == "YES") {      $ctl_html .='<div class="timeline-icon icon-larger iconbg-turqoise icon-color-white">                    	<div class="icon-placeholder">'.$icon.'</div>                        <div class="timeline-bar"></div>                    </div>';  }else {      $ctl_html .= '<div class="timeline-icon icon-dot-full">                        <div class="timeline-bar"></div>                    </div>';  }        $ctl_html .= '<div ' . $ctl_animation . '  " id="' . $row . '" class="timeline-content  clearfix ' . $even_odd . '  ' . $container_cls . '">';        $ctl_html .= '<h2 class="content-title">' . get_the_title() . '</h2>';        $ctl_html .= '<div class="ctl_info event-description ' . $cont_size_cls . '">';        $ctl_html .= $ctl_format_html;        $ctl_html .= '<div class="content-details">              ' . $post_content . '            </div>';        $ctl_html .= '</div>';//$ctl_html .=clt_social_links();        $ctl_html .= '</div><!-- timeline content --></div><!-- .timeline-post-end -->';        if ($row >= 3) {            $row = 0;        }        $row++;        $i++;        $ctl_format_html = '';        $post_content = '';    endwhile;    wp_reset_postdata();} else {    $ctl_html_no_cont .= '<div class="no-content"><h4>';    //$ctl_html_no_cont.=$ctl_no_posts;    $ctl_html_no_cont .= __('Sorry,You have not added any story yet', 'cool-timeline');    $ctl_html_no_cont .= '</h4></div>';}$ctl_html .= '<div class="clearfix"></div>';if ($enable_pagination == "yes") {    if (function_exists('custom_pagination')) {        $ctl_html .= custom_pagination($ctl_loop->max_num_pages, "", $paged);    }}