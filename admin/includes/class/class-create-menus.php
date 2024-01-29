<?php

if( ! class_exists( 'httfox_wyp_create_menus' ) ){
  class httfox_wyp_create_menus{
    private $plugin_name;
    private $plugin_slug;
    private $menu_key;
    private $use_submenu;
    private $callback;

    public function __construct( $name, $slug, $menu_key, $use_submenu, $callback ){
      $this->plugin_name      = $name;
      $this->plugin_slug      = $slug;
      $this->menu_key         = $menu_key;
      $this->use_submenu      = $use_submenu;
      $this->callback         = $callback;

      if (!$this->use_submenu) {
        add_action( 'admin_menu', [ $this, 'httfox_wyp_add_menu_page' ] );
      } else {
        add_action( 'admin_menu', [ $this, 'httfox_wyp_add_submenu_page' ] );
      }
    }

    public function httfox_wyp_add_menu_page(){
      add_menu_page(
        __( $this->plugin_name . ': Settings' ),
        $this->plugin_name,
        'manage_options',
        $this->menu_key,
        $this->callback 
      );
    }
    
    public function httfox_wyp_add_submenu_page(){
      add_submenu_page(
        $this->menu_key,
        $this->plugin_name,
        $this->plugin_name,
        'edit_theme_options',
        $this->plugin_slug,
        $this->callback 
      );
    }
  }
}