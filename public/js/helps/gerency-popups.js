function httfox_wyp_gerencyPopups(selector) {
  const elements = document.querySelectorAll(selector);

  if (!elements.length) return;

  const id_popupBox = 'httfox-popup-box';
  const id_containerPlayer = 'httfox-popup-container-player';
  const class_popupBackground = 'httfox-popup-background';
  const class_popupContainer = 'httfox-popup-container';
  const class_popupContent = 'httfox-popup-content';
  const class_popupClose = 'httfox-popup-close';
  
  const box = createPopupBox();

  elements.forEach(element => {
    element.addEventListener('click', openPopup);
  });

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

  // Função chamada quando o player estiver pronto
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
}