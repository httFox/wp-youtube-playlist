<?php

function httfox_wyp_generate_custom_css($vars, $slug = null) {
  $css = '';

  $slug = !empty($slug) ? "--$slug-" : '--';

  $css .= ":root {\n";

  foreach ($vars as $property => $value) {
    $css .= " $slug$property: $value;\n";
  }

  $css .= "}\n";

  return $css;
}

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
  $itens_per_page = absint($short_atts['max_return']);
  $columns = absint($short_atts['columns']);
  $video_prop_height = absint($short_atts['video_prop_height']);
  $video_prop_width = absint($short_atts['video_prop_width']);
  $title_tag = sanitize_text_field($short_atts['title_tag']);
  $gap = sanitize_text_field($short_atts['gap']);
  $display = sanitize_text_field($short_atts['display']);
  $playlist_id = !empty($atts['playlist_id']) ? sanitize_text_field($atts['playlist_id']) : null;

  // Validate items
  if (!$playlist_id) return '<p>Erro: ID da playlist não adicionado!</p>';

  // Enqueue script
  $script_name = 'httfox-wyp-add-list';
  wp_enqueue_script($script_name, HTTFOX_WYP_DIR_URL . 'public/script.js', array(), '1.0', true);

  // Define os parâmetros que você deseja passar para o script
  $script_params = array(
    'playlist_id' => $playlist_id,
    'itens_per_page' => $itens_per_page
  );

  // Define IDs and Classes html
  $html_id_box = 'httfox-wyp-box';
  $html_class_container = 'httfox-wyp-container';

  // Localize o script com os parâmetros
  wp_localize_script($script_name, 'httfox_wyp_id_box', $html_id_box);
  wp_localize_script($script_name, 'httfox_wyp_class_container', $html_class_container);
  wp_localize_script($script_name, 'httfox_wyp_tag_title', $title_tag);
  wp_localize_script($script_name, 'httfox_wyp_prop_height', $video_prop_height);
  wp_localize_script($script_name, 'httfox_wyp_prop_width', $video_prop_width);
  
  wp_localize_script($script_name, 'httfox_wyp_url_fetch', rest_url() . HTTFOX_WYP_API_VERSION_V1 . '/youtube/playlist');
  wp_localize_script($script_name, 'httfox_wyp_data', $script_params);

  // Adiciona a folha de estilo ao WordPress
  wp_enqueue_style('httfox-wyp-style', HTTFOX_WYP_DIR_URL . 'public/style.css');

  // Define as variáveis que deseja passar para o CSS
  $custom_css_vars = array(
    'display' => $display,
    'columns' => $columns,
    'gap' => $gap
  );

  // Adiciona as variáveis como estilos CSS personalizados
  wp_add_inline_style('httfox-wyp-style', httfox_wyp_generate_custom_css($custom_css_vars, 'httfox-wyp'));
  // Output html
  return "<div id='$html_id_box'><ul class='$html_class_container'></ul></div>";
}

add_shortcode('httFox_wtp_load', 'httfox_wyp_create_shortcode_load');

?>