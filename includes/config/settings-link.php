<?php

add_filter( 'plugin_action_links_' . HTTFOX_WYP_BASENAME, 'httfox_wyp_add_settings_link' );

function httfox_wyp_add_settings_link( $links ){
  $settings_link = '<a href="admin.php?page=' . HTTFOX_WYP_SLUG . '">' . __( 'Settings', HTTFOX_WYP_SLUG ) . '</a>';
  array_unshift( $links, $settings_link );
  return $links;
}