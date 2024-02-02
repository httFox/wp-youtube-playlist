<?php

require_once(HTTFOX_WYP_DIR . '/includes/helps/generate-custom-css-root.php');
require_once(HTTFOX_WYP_DIR . '/includes/helps/get-playlist-data.php');

function httfox_wyp_create_shortcode_load($atts) {
  // Default itens
  $short_atts = shortcode_atts(
    [
      'max_return' => 50,
      'columns' => 2,
      'display' => 'grid',
      'gap' => '20px',
      'title_tag' => 'p',
      'video_prop_height' => 9,
      'video_prop_width' => 16
    ],
    $atts
  );

  // Format items
  $root_id = 'httfox-wyp-root';
  
  $playlist_id = !empty($atts['playlist_id']) ? sanitize_text_field($atts['playlist_id']) : null;
  $itens_per_page = absint($short_atts['max_return']);

  $video_prop_height = absint($short_atts['video_prop_height']);
  $video_prop_width = absint($short_atts['video_prop_width']);
  $title_tag = sanitize_text_field($short_atts['title_tag']);

  $display = sanitize_text_field($short_atts['display']);
  $gap = sanitize_text_field($short_atts['gap']);
  $columns = absint($short_atts['columns']);


  // Validate items
  if (!$playlist_id) return;

  // Adiciona a folha de estilo ao WordPress
  $style_general = 'httfox-wyp-style';
  wp_enqueue_style($style_general, HTTFOX_WYP_DIR_URL . 'public/style.css');

  $custom_css_vars = array(
    'display' => $display,
    'columns' => $columns,
    'gap' => $gap
  );

  wp_add_inline_style($style_general, httfox_wyp_generate_custom_css($custom_css_vars, 'httfox-wyp'));
  
  
  // Enqueue scripts
  $script_youtube_api = 'httfox-wyp-youtube-api';
  wp_enqueue_script('youtube-api', 'https://www.youtube.com/iframe_api', array(), null, true);

  wp_enqueue_script('httfox-wyp-script-resize', HTTFOX_WYP_DIR_URL . 'public/js/helps/resize-props-elements.js', array(), '1.1', true);
  wp_enqueue_script('httfox-wyp-script-gerency-popups', HTTFOX_WYP_DIR_URL . 'public/js/helps/gerency-popups.js', array(), '1.1', true);

  // $script_resize_use_name = 'httfox-wyp-script-resize-use';
  // wp_enqueue_script($script_resize_use_name, HTTFOX_WYP_DIR_URL . 'public/js/resize-props-elements-use-shortcode.js', array(), '1.2', false);

  $script_general = 'httfox-wyp-script';
  wp_enqueue_script($script_general, HTTFOX_WYP_DIR_URL . 'public/js/httfox-wyp.js', array(), '2.0', false);
  
  $localization_params = array(
    'root' => $root_id,
    'url_fetch' => rest_url() . HTTFOX_WYP_API_VERSION_V1 . '/youtube/playlist',
    'hash_fetch' => array('playlist_id' => $playlist_id, 'itens_per_page' => $itens_per_page),
    'prop_width' => $video_prop_width,
    'prop_height' => $video_prop_height,
    'title_tag' => $title_tag,
  );

  // Localize o script com os parâmetros
  wp_localize_script($script_general, 'httfox_wyp_params', $localization_params);
  
  
  return "<div id='$root_id'></div>";
}

add_shortcode('httFox_wtp_load', 'httfox_wyp_create_shortcode_load');

?>