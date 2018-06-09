<?php
$story_format = get_post_meta(get_the_ID(), 'story_format', true);
$ctl_story_date = get_post_meta(get_the_ID(), 'ctl_story_date', true);
$ctl_options_arr = get_option('cool_timeline_options');
$slider_animation = $ctl_options_arr['slider_animation'] ? $ctl_options_arr['slider_animation'] : "slide";
$ctl_slideshow = $ctl_options_arr['ctl_slideshow'] ? $ctl_options_arr['ctl_slideshow'] : true;
$animation_speed = $ctl_options_arr['animation_speed'] ? $ctl_options_arr['animation_speed'] : 7000;


//$ctl_posts_order='date';
//video format
if ($story_format == "video") {

    $ctl_video = get_post_meta(get_the_ID(), 'ctl_video', true);
    if ($ctl_video) {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $ctl_video, $matches);
        $id = $matches[1];
        if ($id) {
            $width = '100%';
            $height = 'auto';
            $iframe = '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $id . '" frameborder="0" allowfullscreen></iframe>';
            echo'<div class="ctl-single-video">' . $iframe . '</div>';
        }
    }
} else if ($story_format == "slideshow") {
    //$ctl_slides=rwmb_meta('ctl_slides');
    $d = get_post_meta(get_the_ID(), 're_', false);
    $ctl_slides = array();
    if ($d && is_array($d[0])) {
        foreach ($d[0] as $key => $images) {

            $ctl_slides[] = $images['ctl_slide']['id'];
        }
    }
   
    $slides_html = '';
    $ctl_format_html = '';
    $ctl_format_html.='<div class="cool_timeline"><div class="full-width  ctl_slideshow">';

    if (array_filter($ctl_slides)) {
        $ctl_format_html.='<div data-animationSpeed="' . $animation_speed . '"  data-slideshow="' . $ctl_slideshow . '" data-animation="' . $slider_animation . '" class="ctl_flexslider"><ul class="slides">';
        foreach ($ctl_slides as $key => $att_index) {

            $slides = wp_get_attachment_image_src($att_index, 'large');
            if ($slides[0]) {
                $sld = $slides[0];
                $slides_html .='<li><img src="' . $sld . '"></li>';
            }
        }
        $ctl_format_html.=$slides_html . '</ul></div>';
    }
   
     $ctl_format_html.='</div></div>';
     echo $ctl_format_html;
} else {
    $ctl_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');
    $ctl_thumb_url = $ctl_thumb['0'];
    $ctl_thumb_width = $ctl_thumb['1'];
    $ctl_thumb_height = $ctl_thumb['2'];

    if (isset($ctl_thumb_url) && !empty($ctl_thumb_url)) {
        echo'<div class="ctl-single-img"><img  class="ctl-img" src="' . $ctl_thumb_url . '"></div>';
    }
}
?>
			
