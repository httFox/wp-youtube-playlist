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
  wp_enqueue_script('httfox-wyp-add-list', HTTFOX_WYP_DIR_URL . 'public/script.js', array(), '1.0', true);

  // Output html
  return '<div id="httfox-wyp-box" data-playlist-id="' . $playlist_id . '" data-max-return="' . $itens_per_page . '"><ul class="httfox-wyp-container"></ul></div>';
}

add_shortcode('httFox_wtp_load', 'httfox_wyp_create_shortcode_load');

?>