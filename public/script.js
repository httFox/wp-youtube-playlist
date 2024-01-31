(() => {
  const urlFetch = httfox_wyp_url_fetch // setado pelo arquivo de shortcode
  const paramsFetch = httfox_wyp_data // setado pelo arquivo de shortcode
  const id_Box = httfox_wyp_id_box // setado pelo arquivo de shortcode
  const class_container = httfox_wyp_class_container // setado pelo arquivo de shortcode
  const tag_title = httfox_wyp_tag_title // setado pelo arquivo de shortcode
  const prop_height = httfox_wyp_prop_height // setado pelo arquivo de shortcode
  const prop_width = httfox_wyp_prop_width // setado pelo arquivo de shortcode
  const class_item = 'httfox-wyp-item';
  const class_itemTitle = 'httfox-wyp-item-title';
  const class_itemImg = 'httfox-wyp-item-image';
  const class_itemPlayerBox = 'httfox-wyp-item-player-box';
  const class_itemPlayer = 'httfox-wyp-item-player';
  const class_itemPlayerArrow = 'httfox-wyp-item-player-arrow';
  
  if (!urlFetch || !paramsFetch) return;
  
  async function getPlaylist() {    
    try {
      const url = addQueryArgs(urlFetch, paramsFetch);
      const response = await fetch(url);
    
      if (!response.ok) {
        throw new Error(`Erro de rede! Código de status: ${response.status}`);
      }

      return await response.json();

    } catch (error) {
      console.error('Erro durante a solicitação:', error.message);
      return null;
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

  function adjustHeightTo16by9(selectorToElementObserver) {
    const containers = document.querySelectorAll(`.${class_item}`);

    containers.forEach(function (container) {
      const iframe = container.querySelector(selectorToElementObserver);

      if (container && iframe) {
        const containerWidth = container.offsetWidth;
        const desiredHeight = (containerWidth * prop_height) / prop_width;

        iframe.style.height = desiredHeight + 'px';
      }
    });
  }

  async function addVideos() {
    const outputBox = document.getElementById(id_Box);
    if (!outputBox) return;
    
    let container = outputBox.querySelector(`.${class_container}`);
    
    if (!container) {
      outputBox.insertAdjacentHTML('beforeend', `<ul class="${class_container}"></ul>`);
      container = outputBox.querySelector(`.${class_container}`);
    }
    

    const data = await getPlaylist();

    data.items.forEach(element => {
      const id = element.snippet.resourceId.videoId || null;
      const title = element.snippet.title || null;
      const thumbDefault = element.snippet.thumbnails.default || null;
      
      if (!id || !title || !thumbDefault) return;

      const thumb = element.snippet.thumbnails || null;

      const content = `
      <li class="${class_item}">
        <div class="${class_itemImg}">
          <img src="${thumb.high.url}" alt="${title}" />
          <div class="${class_itemPlayerBox}">
            <span class="${class_itemPlayer}">
              <span class="${class_itemPlayerArrow}"></span>
            </span>
          </div>
        </div>
        <${tag_title} class="${class_itemTitle}">${title}</${tag_title}>
      </li>
      `;

      container.insertAdjacentHTML('beforeend', content);
    });

    // Chamando a função ao carregar a página
    adjustHeightTo16by9(`.${class_itemImg} img`);

    // Chamando a função ao redimensionar a janela
    window.addEventListener('resize', () => adjustHeightTo16by9(`.${class_itemImg} img`));
  }

  addVideos();
})();