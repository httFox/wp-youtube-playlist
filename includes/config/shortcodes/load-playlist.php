<?php

function httfox_wyp_create_shortcode_load($atts) {
  // Default itens
  $short_atts = shortcode_atts(
    ['max_return' => 9],
    $atts
  );

  // Format items
  $itens_per_page = absint($short_atts['max_return']);
  $playlist_id = !empty($atts['playlist_id']) ? sanitize_text_field($atts['playlist_id']) : null;

  // Validate items
  if (!$playlist_id) return '<p>Erro: ID da playlist não adicionado!</p>';

  // Enqueue script
  $script_name = 'httfox-wyp-add-list';
  wp_enqueue_script($script_name, HTTFOX_WYP_DIR_URL . 'public/script.js', array(), '1.0', true);

  // Define os parâmetros que você deseja passar para o script
  $script_params = array(
    'playlist_id' => $playlist_id,
    'itens_per_page' => $itens_per_page,
  );

  // Define IDs and Classes html
  $html_id_box = 'httfox-wyp-box';
  $html_class_container = 'httfox-wyp-container';

  // Localize o script com os parâmetros
  wp_localize_script($script_name, 'httfox_wyp_id_box', $html_id_box);
  wp_localize_script($script_name, 'httfox_wyp_class_container', $html_class_container);
  
  wp_localize_script($script_name, 'httfox_wyp_url_fetch', rest_url() . HTTFOX_WYP_API_VERSION_V1 . '/youtube/playlist');
  wp_localize_script($script_name, 'httfox_wyp_data', $script_params);

  // Output html
  return "<div id='$html_id_box'><ul class='$html_class_container'></ul></div>";
}

add_shortcode('httFox_wtp_load', 'httfox_wyp_create_shortcode_load');

?>