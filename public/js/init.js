function httfox_teste() {
  console.log(httfox_wyp_params);
  
  const url_fetch = httfox_wyp_params.url_fetch || null;
  const hash_fetch = httfox_wyp_params.hash_fetch || null;

  const btn_view_more_text = httfox_wyp_params.btn_view_more_text || null;

  const prop_width = httfox_wyp_params.prop_width || null;
  const prop_height = httfox_wyp_params.prop_height || null;

  const id_button_show_more = httfox_wyp_params.resources.ids.button_show_more || null;

  const class_box = httfox_wyp_params.resources.class.container || null;
  const class_item = httfox_wyp_params.resources.class.item || null;
  const class_item_title = httfox_wyp_params.resources.class.item_title || null;
  const class_item_img_box = httfox_wyp_params.resources.class.item_img_box || null;
  const class_item_img = httfox_wyp_params.resources.class.item_img || null;
  const class_item_player_box = httfox_wyp_params.resources.class.item_player_box || null;
  const class_item_player = httfox_wyp_params.resources.class.item_player || null;
  const class_item_player_arrow = httfox_wyp_params.resources.class.item_player_arrow || null;
  
  const data_set_id = httfox_wyp_params.resources.tags.data_set_id || null;
  const tag_title = httfox_wyp_params.resources.tags.tag_title || null;

  const LOCAL_STORAGE_KEY = 'httfox-wyp-page-token';

  const id_containerPlayer = 'httfox-wyp-youtube-player-iframe';
  
  if (typeof httfox_wyp_heightByProportion !== 'undefined') {
    if (class_box && class_item_img) {
      httfox_wyp_heightByProportion(`.${class_item_img}`, `.${class_box}`, prop_width, prop_height);
    }
  }

  addLifePopups();

  const nextButton = document.getElementById(id_button_show_more);
  if (!nextButton) return;

  nextButton.addEventListener('click', loadMoreVideos);

  function addLifePopups() {
    if (typeof httfox_wyp_create_popup !== 'undefined') {
      const listenerObjs = document.querySelectorAll(`[${data_set_id}]`);
    
      if (listenerObjs.length) {
        listenerObjs.forEach(element => {
          element.addEventListener('click', startPopup);
        });
        
        function startPopup(e) {
          e.preventDefault();
          
          const volume = +e.currentTarget.dataset.volume;
          console.log(volume);
  
          const id = e.currentTarget.dataset.id;
          if (!id) return;
    
          const callback = () => {
            player = new YT.Player(id_containerPlayer, {
              height: 'auto',
              width: '100%',
              videoId: id, // Substitua pelo ID do seu vídeo
              playerVars: {
                autoplay: 1,
                controls: 0,
                enablejsapi: 1,
                muted: 1 // Inicia o vídeo silenciado
              },
              events: {
                'onReady': onPlayerReady
              }
            });
        
            function onPlayerReady(event) {
              // Ajustar o volume para x%
              // event.target.setVolume(volume);
    
              // Inicie o vídeo automaticamente com som
              event.target.playVideo();
            }
    
            if (typeof httfox_wyp_heightByProportion !== 'undefined') {
              httfox_wyp_heightByProportion(`#${id_containerPlayer}`);
            }
          };
    
          const popup = new httfox_wyp_create_popup(`<div id="${id_containerPlayer}"></div>`, callback);
          popup.init();
    
        }
      }
    }
  }

  async function getPlaylist() {
    nextButton.innerText = 'Carregando';

    try {
      const url = addQueryArgs(url_fetch, hash_fetch);
      console.log(hash_fetch);
      const response = await fetch(url);
    
      if (!response.ok) {
        throw new Error(`Erro de rede! Código de status: ${response.status}`);
      }

      const json = await response.json();
      console.log(json);
      return json;

    } catch (error) {
      console.error('Erro durante a solicitação:', error.message);
      return null;
    } finally {
      nextButton.innerText = btn_view_more_text;
    }
  }

  function addQueryArg(url, key, value) {
    const separator = url.includes('?') ? '&' : '?';
    const newUrl = `${url}${separator}${encodeURIComponent(key)}=${encodeURIComponent(value)}`;

    return newUrl;
  }

  function addQueryArgs(url, obj_params) {
    let newUrl = url;

    for (const key in obj_params) {
      if (obj_params.hasOwnProperty(key)) {
        const prop = obj_params[key];
        newUrl = addQueryArg(newUrl, key, prop);
      }
    }

    return newUrl;
  }

  function addVideos(container, data) {
    if (data.nextPageToken) nextButton.dataset.page_token = data.nextPageToken;
    else nextButton.remove();

    console.log(nextButton);

    data.items.forEach(video => {
      const id = video.snippet.resourceId.videoId || null;
      const title = video.snippet.title || null;
      const thumb = video.snippet.thumbnails || null;

      const thumb_high = thumb.high || null;
      
      if (!id || !title || !thumb_high) return;

      const content = `
      <li class="${class_item}">
        <div class="${class_item_img_box}">
          <img src="${thumb_high.url}" alt="${title}" class="${class_item_img} "/>
          <div class="${class_item_player_box}">
            <span class="${class_item_player}" ${data_set_id}="${id}">
              <span class="${class_item_player_arrow}"></span>
            </span>
          </div>
        </div>
        <${tag_title} class="${class_item_title}" ${data_set_id}="${id}">${title}</${tag_title}>
      </li>
      `;

      container.insertAdjacentHTML('beforeend', content);
    });

    if (typeof httfox_wyp_heightByProportion !== 'undefined') {
      if (class_box && class_item_img) {
        httfox_wyp_heightByProportion(`.${class_item_img}`, `.${class_box}`, prop_width, prop_height);
      }
    }

    addLifePopups();
  }

  async function loadMoreVideos(e) {
    e.preventDefault();

    const pageToken = e.currentTarget.dataset.page_token;
    hash_fetch.page_token = pageToken;

    const json = await getPlaylist();
    const container = document.querySelector(`.${class_box}`);

    if (json && container && pageToken) addVideos(container, json);
  }
}

httfox_teste();
