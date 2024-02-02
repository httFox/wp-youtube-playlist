(() => {
  const rootId = httfox_wyp_params.root // setado pelo arquivo de shortcode
  const urlFetch = httfox_wyp_params.url_fetch // setado pelo arquivo de shortcode
  const hashFetch = httfox_wyp_params.hash_fetch // setado pelo arquivo de shortcode
  const propHeight = httfox_wyp_params.prop_height // setado pelo arquivo de shortcode
  const propWidth = httfox_wyp_params.prop_width // setado pelo arquivo de shortcode
  const tagTitle = httfox_wyp_params.title_tag // setado pelo arquivo de shortcode

  const id_popupBox = 'httfox-popup-box';
  const id_containerPlayer = 'httfox-popup-container-player';

  const class_container = 'httfox-wyp-container';

  const class_item = 'httfox-wyp-item';
  const class_itemTitle = 'httfox-wyp-item-title';
  const class_itemImgBox = 'httfox-wyp-item-image-box';
  const class_itemImg = 'httfox-wyp-item-image';

  const class_itemPlayerBox = 'httfox-wyp-item-player-box';
  const class_itemPlayer = 'httfox-wyp-item-player';
  const class_itemPlayerArrow = 'httfox-wyp-item-player-arrow';

  const class_popupBackground = 'httfox-popup-background';
  const class_popupContainer = 'httfox-popup-container';
  const class_popupContent = 'httfox-popup-content';
  const class_popupClose = 'httfox-popup-close';

  const tagDataSelectorId = 'data-id';

  const root = document.getElementById(rootId);
  
  if (!urlFetch || !hashFetch || !root) return;

  const box = createPopupBox();

  async function getPlaylist() {
    root.innerHTML = 'Carregando';

    try {
      const url = addQueryArgs(urlFetch, hashFetch);
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
      root.innerHTML = 'Finalizado';
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
    data.items.forEach(video => {
      const id = video.snippet.resourceId.videoId || null;
      const title = video.snippet.title || null;
      const thumb = video.snippet.thumbnails || null;

      const thumbDefault = thumb.default || null;
      
      if (!id || !title || !thumbDefault) return;

      const content = `
      <li class="${class_item}">
        <div class="${class_itemImgBox}">
          <img src="${thumb.high.url}" alt="${title}" class="${class_itemImg} "/>
          <div class="${class_itemPlayerBox}">
            <span class="${class_itemPlayer}" ${tagDataSelectorId}="${id}">
              <span class="${class_itemPlayerArrow}"></span>
            </span>
          </div>
        </div>
        <${tagTitle} class="${class_itemTitle}" ${tagDataSelectorId}="${id}">${title}</${tagTitle}>
      </li>
      `;

      container.insertAdjacentHTML('beforeend', content);
    });

    httfox_wyp_adjustHeightTo16by9WithResize(`.${class_item}`, `.${class_itemImg}`, propWidth, propHeight);
    addEventOnClickVideos(`[${tagDataSelectorId}]`);
  }

  async function addVideosContainer() {
    const data = await getPlaylist();
    
    root.insertAdjacentHTML('beforeend', `<ul class="${class_container}"></ul>`);
    
    const container = root.querySelector(`.${class_container}`);

    if (!data || !container) return;

    addVideos(container, data);
  }

  function addEventOnClickVideos(selector) {
    const elements = document.querySelectorAll(selector);
  
    if (!elements.length) return;
  
    elements.forEach(element => {
      element.addEventListener('click', openPopup);
    });
  }

  function openPopup(e) {
    e.preventDefault();

    const id = e.currentTarget.dataset.id;
    if (!id) return;

    const content = `
      <div class="${class_popupBackground}">
        <div class="${class_popupContainer}">
          <div class="${class_popupContent}">
            <div id="${id_containerPlayer}"></div>
          </div>
          <button class="${class_popupClose}">X</button>
        </div>
      </div>
    `;

    box.innerHTML = content;

    onYouTubeIframeAPIReady(id);

    
    const btnClose = box.querySelector(`.${class_popupClose}`);
    
    if (btnClose) btnClose.addEventListener('click', closePopup);
  }

  function onYouTubeIframeAPIReady(id) {
    // Crie um novo player no elemento 'player'
    player = new YT.Player(id_containerPlayer, {
      height: 'auto',
      width: '100%',
      videoId: id, // Substitua pelo ID do seu vídeo
      events: {
        'onReady': onPlayerReady
      }
    });

    httfox_wyp_adjustHeightTo16by9WithResize(`.${class_popupContent}`, `iframe`);
  }

  function onPlayerReady(event) {
    // Inicie o vídeo automaticamente com som
    event.target.playVideo();
  }

  function closePopup() {
    box.innerHTML = '';
  }

  function createPopupBox(){
    if (!document.getElementById(id_popupBox)) {
      document.body.insertAdjacentHTML('afterbegin', `<div id="${id_popupBox}"></div>`);
    }

    return document.getElementById(id_popupBox);
  }
  
  addVideosContainer();
})();