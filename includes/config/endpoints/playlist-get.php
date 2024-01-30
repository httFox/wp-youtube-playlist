<?php

function httfox_wyp_api_google_youtube_playlist_get($request) {
  $playlist_id = sanitize_text_field($request['playlist_id']);
  $itens_per_page = !empty($request['itens_per_page']) ? absint($request['itens_per_page']) : 9;
  $key = get_option(HTTFOX_WYP_SLUG_DB)[HTTFOX_WYP_API_KEY_SLUG];

  if (empty($key)) {
    return new WP_Error('error', 'API Key is undefined', ['status' => 401]);
  }

  if (empty($playlist_id)) {
    return new WP_Error('error', 'Playlist id is empty', ['status' => 406]);
  }

  // Construa a URL da API usando a função http_build_query
  $api_url = add_query_arg(
    [
      'maxResults' => $itens_per_page,
      'playlistId' => $playlist_id,
      'key' => $key
    ],
    HTTFOX_WYP_GOOGLE_APIS_YOUTUBE_PLAYLISTS
  );

  $curl = curl_init();

  $curl_args = [
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "Accept: application/json",
    ],
  ];

  curl_setopt_array($curl, $curl_args);

  $response = json_decode(curl_exec($curl));
  
  // Verifica se ocorreu algum erro na execução da solicitação cURL
  if (curl_errno($curl)) {
    return new WP_Error('error', curl_error($curl), ['status' => 500]);
  }

  curl_close($curl);

  return rest_ensure_response($response);
}


function httfox_wyp_register_api_google_youtube_playlist_get() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'httfox_wyp_api_google_youtube_playlist_get'
  ];

  register_rest_route(HTTFOX_WYP_API_VERSION_V1, '/youtube/playlist', $configRoutes);
}

add_action('rest_api_init', 'httfox_wyp_register_api_google_youtube_playlist_get');

?>