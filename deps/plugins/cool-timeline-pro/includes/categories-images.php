<?php
define('CTL_IMAGE_PLACEHOLDER', COOL_TIMELINE_PLUGIN_URL."/images/placeholder.png");

// l10n
//load_plugin_textdomain('cool-timeline', FALSE, 'cool-timeline/languages');

add_action('admin_init', 'ctl_init');
function ctl_init() {
	$ctl_taxonomies = get_taxonomies();
	if (is_array($ctl_taxonomies)) {
		$zci_options = get_option('zci_options');
		if (isset($ctl_taxonomies['ctl-stories']))
			{	
                            $ctl_taxonomy='ctl-stories';
                         add_action($ctl_taxonomy.'_add_form_fields', 'ctl_add_texonomy_field');
			add_action($ctl_taxonomy.'_edit_form_fields', 'ctl_edit_texonomy_field');
			add_filter( 'manage_edit-' . $ctl_taxonomy . '_columns', 'ctl_taxonomy_columns' );
			add_filter( 'manage_' . $ctl_taxonomy . '_custom_column', 'ctl_taxonomy_column', 10, 3 );
                       }
          
	}
}

function ctl_add_style() {
	echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image {border:1px solid #eee;max-width:300px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb span {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:48px;height:48px;}
	</style>';
}

// add image field in add form
function ctl_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	echo '<div class="form-field">
		<label for="taxonomy_image">' . __('Image', 'cool-timeline') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="ctl_upload_image_button button">' . __('Upload/Add image', 'cool-timeline') . '</button>
	</div>'.ctl_script();
}

// add image field in edit form
function ctl_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	if (ctl_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == CTL_IMAGE_PLACEHOLDER) 
		$image_url = "";
	else
		$image_url = ctl_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('Image', 'cool-timeline') . '</label></th>
		<td><img class="taxonomy-image" src="' . ctl_taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_url.'" /><br />
		<button class="ctl_upload_image_button button">' . __('Upload/Add image', 'cool-timeline') . '</button>
		<button class="ctl_remove_image_button button">' . __('Remove image', 'cool-timeline') . '</button>
		</td>
	</tr>'.ctl_script();
}

// upload using wordpress upload
function ctl_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".ctl_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".ctl_remove_image_button").click(function() {
				$(".taxonomy-image").attr("src", "'.CTL_IMAGE_PLACEHOLDER.'");
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . CTL_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").click(function() {	
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

				if (thumb != "' . CTL_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				
				$(".inline-edit-col .title img").attr("src",thumb);
			});
	    });
	</script>';
}

// save our taxonomy image while edit or save term
add_action('edit_term','ctl_save_taxonomy_image');
add_action('create_term','ctl_save_taxonomy_image');
function ctl_save_taxonomy_image($term_id) {
    if(isset($_POST['taxonomy_image']))
        update_option('ctl_taxonomy_image'.$term_id, $_POST['taxonomy_image'], NULL);
}

// get attachment ID by image url
function ctl_get_attachment_id_by_url($image_src) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}

// get taxonomy image url for the given term_id (Place holder image by default)
function ctl_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	
    $taxonomy_image_url = get_option('ctl_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = ctl_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id)) {
	    	$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		    $taxonomy_image_url = $taxonomy_image_url[0];
	    }
	}

    if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : CTL_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_image_url;
}

function ctl_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="ctl_upload_image_button button">' . __('Upload/Add image', 'cool-timeline') . '</button>
					<button class="ctl_remove_image_button button">' . __('Remove image', 'cool-timeline') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

/**
 * Thumbnail column added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function ctl_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __('Image', 'cool-timeline');

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}

/**
 * Thumbnail column value added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function ctl_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . ctl_taxonomy_image_url($id, 'thumbnail', TRUE) . '" alt="' . __('Thumbnail', 'cool-timeline') . '" class="wp-post-image" /></span>';
	
	return $columns;
}

// Change 'insert into post' to 'use this image'
function ctl_change_insert_button_text($safe_text, $text) {
    return str_replace("Insert into Post", "Use this image", $text);
}

// Style the image in category list
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action( 'admin_head', 'ctl_add_style' );
	add_action('quick_edit_custom_box', 'ctl_quick_edit_custom_box', 10, 3);
	add_filter("attribute_escape", "ctl_change_insert_button_text", 10, 2);
}


// display taxonomy image for the given term_id
function ctl_taxonomy_image($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
    $taxonomy_image_url = get_option('ctl_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = ctl_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id))
	    	$taxonomy_image = wp_get_attachment_image($attachment_id, $size, FALSE, $attr);
	    else {
	    	$image_attr = '';
	    	if(is_array($attr)) {
	    		if(!empty($attr['class']))
	    			$image_attr .= ' class="'.$attr['class'].'" ';
	    		if(!empty($attr['alt']))
	    			$image_attr .= ' alt="'.$attr['alt'].'" ';
	    		if(!empty($attr['width']))
	    			$image_attr .= ' width="'.$attr['width'].'" ';
	    		if(!empty($attr['height']))
	    			$image_attr .= ' height="'.$attr['height'].'" ';
	    		if(!empty($attr['title']))
	    			$image_attr .= ' title="'.$attr['title'].'" ';
	    	}
	    	$taxonomy_image = '<img  src="'.$taxonomy_image_url.'" '.$image_attr.'/>';
	    }
	}

	if ($echo)
			if(!empty($taxonomy_image)){
                            return  $taxonomy_image;
			}else {
				return false;
			}
	else
		return $taxonomy_image;
}