<?php

require_once(HTTFOX_WYP_DIR . '/includes/helps/generate-custom-css-root.php');
require_once(HTTFOX_WYP_DIR . '/includes/helps/get-playlist-data.php');

function httfox_wyp_create_shortcode_load($atts) {
  // Default itens
  $short_atts = shortcode_atts(
    [
      'max_return' => 9,
      'columns' => 3,
      'display' => 'grid',
      'gap' => 20,
      'title_tag' => 'p',
      'video_prop_height' => 9,
      'video_prop_width' => 16,
      'video_volume' => 50,
      'btn_view_more_text' => 'Ver mais'
    ],
    $atts
  );

  // Format items
  $playlist_id = !empty($atts['playlist_id']) ? sanitize_text_field($atts['playlist_id']) : null;
  $itens_per_page = absint($short_atts['max_return']);

  $video_volume = absint($short_atts['video_volume']);
  $video_prop_height = absint($short_atts['video_prop_height']);
  $video_prop_width = absint($short_atts['video_prop_width']);
  $title_tag = sanitize_text_field($short_atts['title_tag']);
  $btn_view_more_text = sanitize_text_field($short_atts['btn_view_more_text']);

  $display = sanitize_text_field($short_atts['display']);
  $gap = absint($short_atts['gap']) . 'px';
  $columns = absint($short_atts['columns']);


  // Validate items
  if (!$playlist_id) return;

  // Add Styles
  $style_general = 'httfox-wyp-style';
  wp_enqueue_style($style_general, HTTFOX_WYP_DIR_URL . 'public/style.css');

  $custom_css_vars = array(
    'display' => $display,
    'columns' => $columns,
    'gap' => $gap
  );

  wp_add_inline_style($style_general, httfox_wyp_generate_custom_css($custom_css_vars, 'httfox-wyp'));

  $html_items = array(
    'ids' => [
      'root' => 'httfox-wyp-root',
      'button_show_more' => 'httfox-wyp-button-show-more'
    ],
    'class' => [
      'container' => 'httfox-wyp-container',
      'item' => 'httfox-wyp-item',
      'item_title' => 'httfox-wyp-item-title',
      'item_img_box' => 'httfox-wyp-item-image-box',
      'item_img' => 'httfox-wyp-item-image',
      'item_player_box' => 'httfox-wyp-item-player-box',
      'item_player' => 'httfox-wyp-item-player',
      'item_player_arrow' => 'httfox-wyp-item-player-arrow',
    ],
    'tags' => [
      'data_set_id' => 'data-id',
      'data_set_volume' => 'data-volume',
      'data_set_next_page' => 'data-page_token',
      'tag_title' => $title_tag,
    ]
  );
  
  // Load first grid
  $data = json_decode(httfox_wyp_get_playlist_data($itens_per_page, $playlist_id));

  $output = '';
  foreach($data->items as $item) {
    $id = isset($item->snippet->resourceId->videoId) ? $item->snippet->resourceId->videoId : null;
    $title = isset($item->snippet->title) ? $item->snippet->title : null;
    $thumb = isset($item->snippet->thumbnails) ? $item->snippet->thumbnails : null;
    $thumb_high = isset($thumb->high) ? $thumb->high : null;

    if ($id && $title && $thumb_high) {      
      $multi_line_string = <<<EOD
        <li class="{$html_items['class']['item']}">
          <div class="{$html_items['class']['item_img_box']}">
            <img src="{$thumb_high->url}" alt="$title" class="{$html_items['class']['item_img']}"/>
            <div class="{$html_items['class']['item_player_box']}">
              <span class="{$html_items['class']['item_player']}" {$html_items['tags']['data_set_id']}="$id" {$html_items['tags']['data_set_volume']}="$video_volume">
                <span class="{$html_items['class']['item_player_arrow']}"></span>
              </span>
            </div>
          </div>
          <p class="{$html_items['class']['item_title']}" {$html_items['tags']['data_set_id']}="$id" {$html_items['tags']['data_set_volume']}="$video_volume">$title</p>
        </li>
      EOD;
    
    $output .= $multi_line_string;
    }
  }

  
  
  // Enqueue scripts
  $script_youtube_api = 'httfox-wyp-youtube-api';
  wp_enqueue_script($script_youtube_api, 'https://www.youtube.com/iframe_api', array(), null, true);

  wp_enqueue_script('httfox-wyp-script-resize', HTTFOX_WYP_DIR_URL . 'public/js/helps/resize-props-elements.js', array(), '1.1', true);
  wp_enqueue_script('httfox-wyp-script-class-popup', HTTFOX_WYP_DIR_URL . 'public/js/class/popup.js', array(), '1.1', true);
  
  $script_init_resize_elements = 'httfox-wyp-script-init-resize-elements';
  wp_enqueue_script($script_init_resize_elements, HTTFOX_WYP_DIR_URL . 'public/js/init.js', array(), '1.2', false);

  $args_init_resize = array(
    'url_fetch' => rest_url() . HTTFOX_WYP_API_VERSION_V1 . '/youtube/playlist',
    'hash_fetch' => ['playlist_id' => $playlist_id, 'itens_per_page' => $itens_per_page],
    'btn_view_more_text' => $btn_view_more_text,
    'prop_width' => $video_prop_width,
    'prop_height' => $video_prop_height,

    'resources' => $html_items,

    // 'id_show_more' => $html_items['ids']['button_show_more'],
    // 'class_box' => $html_items['class']['item'],
    // 'class_element' => $html_items['class']['item_img'],
    // 'data_set_id' => $html_items['tags']['data_set_id'],
    // 'data_set_next_page' => $html_items['tags']['data_set_next_page'],
  );
  
  wp_localize_script($script_init_resize_elements, 'httfox_wyp_params', $args_init_resize);
  
  $next_page_token = isset($data->nextPageToken) ? $data->nextPageToken : null;
  $btn_show_more = !empty($next_page_token) ? "<button id='{$html_items['ids']['button_show_more']}' {$html_items['tags']['data_set_next_page']}=$next_page_token>$btn_view_more_text</button>" : '';

  return "<div id='{$html_items['ids']['root']}'><ul class='{$html_items['class']['container']}'>$output</ul>$btn_show_more</div>";
  
  // $script_resize_use_name = 'httfox-wyp-script-resize-use';
  // wp_enqueue_script($script_resize_use_name, HTTFOX_WYP_DIR_URL . 'public/js/resize-props-elements-use-shortcode.js', array(), '1.2', false);

  // $script_general = 'httfox-wyp-script';
  // wp_enqueue_script($script_general, HTTFOX_WYP_DIR_URL . 'public/js/httfox-wyp.js', array(), '2.0', false);
  
  // $localization_params = array(
  //   'root' => $root_id,
  //   'url_fetch' => rest_url() . HTTFOX_WYP_API_VERSION_V1 . '/youtube/playlist',
  //   'hash_fetch' => array('playlist_id' => $playlist_id, 'itens_per_page' => $itens_per_page),
  //   'prop_width' => $video_prop_width,
  //   'prop_height' => $video_prop_height,
  //   'title_tag' => $title_tag,
  // );

  // // Localize o script com os parâmetros
  // wp_localize_script($script_general, 'httfox_wyp_params', $localization_params);
  
  
  return "<div id='$root_id'></div>";
}

add_shortcode('httFox_wtp_load', 'httfox_wyp_create_shortcode_load');

?>