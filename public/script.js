(() => {
  const urlFetch = httfox_wyp_url_fetch // setado pelo arquivo de shortcode
  const paramsFetch = httfox_wyp_data // setado pelo arquivo de shortcode
  const id_Box = httfox_wyp_id_box // setado pelo arquivo de shortcode
  const class_container = httfox_wyp_class_container // setado pelo arquivo de shortcode
  
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

  async function addVideos() {
    
    const outputBox = document.getElementById(id_Box);
    if (!outputBox) return;
    
    let container = outputBox.querySelector(`.${class_container}`);
    
    if (!container) {
      outputBox.insertAdjacentHTML('afterbegin', `<ul class="${class_container}">Testee</ul>`);
      container = outputBox.querySelector(`.${class_container}`);
    }
    

    const data = await getPlaylist();
    console.log(data);

    data.items.forEach(element => {
      const id = element.snippet.resourceId.videoId || null;

      if (id) {
        const content = `
          <iframe 
            id="player" 
            type="text/html" 
            width="640" 
            height="360"
            src="https://www.youtube.com/embed/${id}"
            frameborder="0">
          </iframe>
        `;
  
        container.insertAdjacentHTML('afterbegin', content);
      }
    });
  }

  addVideos();
})();