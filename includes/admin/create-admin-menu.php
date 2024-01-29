<?php

// Função de callback para renderizar a página de configurações personalizadas
function httfox_wyp_render_settings_page() {
  ?>
    <div class="wrap">
        <h1><?php echo HTTFOX_WYP_NAME ?></h1>
        <form method="post" action="options.php">
          <?php
            settings_fields(HTTFOX_WYP_SLUG_DB);
            do_settings_sections(HTTFOX_WYP_SLUG);
            submit_button();
          ?>
        </form>
    </div>
  <?php
}


// Função para adicionar um item de menu dentro do menu de configurações do WordPress
function httfox_wyp_settings_menu_item() {
  add_options_page(
    HTTFOX_WYP_NAME,   // Título da página
    HTTFOX_WYP_NAME,   // Título do menu
    'manage_options',  // Capacidade necessária para acessar
    HTTFOX_WYP_SLUG,   // Slug da página
    'httfox_wyp_render_settings_page'   // Função de callback para renderizar a página
  );
}
add_action('admin_menu', 'httfox_wyp_settings_menu_item');


function httfox_wyp_render_field() {
  $httfox_wyp_options = get_option(HTTFOX_WYP_SLUG_DB);
  $value = isset($httfox_wyp_options['api_key']) ? esc_attr($httfox_wyp_options['api_key']) : '';

  ?>
    <input
      type="text"
      id="api_key"
      name="<?php echo HTTFOX_WYP_SLUG_DB ?>[api_key]"
      value="<?php echo $value; ?>"
      class="regular-text"
    />
  <?php
}

function httfox_wyp_admin_fields_sanitize( $input ) {
  $new_input = array();

  if (isset($input['api_key'])) {
    $new_input['api_key'] = sanitize_text_field( $input['api_key'] );
  }

  return $new_input;
}


function httfox_wyp_add_fields() {
  register_setting( HTTFOX_WYP_SLUG_DB, HTTFOX_WYP_SLUG_DB, 'httfox_wyp_admin_fields_sanitize');

  add_settings_section(
    HTTFOX_WYP_SLUG_DB . '_sec1', 
    'Seção geral', 
    null, 
    HTTFOX_WYP_SLUG
  );

  add_settings_field(
    'api_key',
    'API Key',
    'httfox_wyp_render_field',
    HTTFOX_WYP_SLUG,
    HTTFOX_WYP_SLUG_DB . '_sec1'
  );
}

add_action('admin_init', 'httfox_wyp_add_fields');





?>