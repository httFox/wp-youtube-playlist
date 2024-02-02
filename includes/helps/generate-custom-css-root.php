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

?>