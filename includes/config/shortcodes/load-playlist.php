<?php

function httfox_wyp_create_shortcode_load($atts) {
  
  $atributo_valor = shortcode_atts(
    array(
      'max_return' => 10,
      'paged' => 1,
    ),
    $atts,
    'meu_shortcode'
  );

  // Lógica do shortcode com o valor do atributo
  return 'Conteúdo do shortcode com parâmetro: ' . $atributo_valor['parametro'];
}

add_shortcode('httFox_wtp_load', 'httfox_wyp_create_shortcode_load');


?>