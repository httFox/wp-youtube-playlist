<?php

function httfox_wyp_get_playlist_data($itens_per_page, $playlist_id) {
  $options = get_option(HTTFOX_WYP_SLUG_DB, array()); // Retorna a tabela de dados em options
  $key = $options[HTTFOX_WYP_API_KEY_SLUG];

  if (empty($key)) return null;

  $config_args = [
    'part' => 'snippet',
    'maxResults' => $itens_per_page,
    'playlistId' => $playlist_id,
    'key' => $key
  ];

  $api_url = add_query_arg($config_args, HTTFOX_WYP_GOOGLE_APIS_YOUTUBE_PLAYLISTS);
    
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
    
    $response = curl_exec($curl);

    // Verifica se ocorreu algum erro na execução da solicitação cURL
    if (curl_errno($curl)) return null;

    curl_close($curl);

    return $response;
}

?>