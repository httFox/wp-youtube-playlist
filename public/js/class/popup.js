class httfox_wyp_create_popup {
  constructor(content, callback = null) {
    this.content = content;
    this.callback = callback;
    
    this.id_popupBox = 'httfox-popup-box';

    this.class_popupBackground = 'httfox-popup-background';
    this.class_popupContainer = 'httfox-popup-container';
    this.class_popupContent = 'httfox-popup-content';
    this.class_popupClose = 'httfox-popup-close';
  }

  openPopup(contentHTML) {
    this.box.innerHTML = contentHTML;

    const btnClose = this.box.querySelector(`.${this.class_popupClose}`);
    if (btnClose) btnClose.addEventListener('click', this.closePopup);

    if (this.callback) this.callback();
  }

  constructorElementsPopup() {
    const content = `
      <div class="${this.class_popupBackground}">
        <div class="${this.class_popupContainer}">
          <div class="${this.class_popupContent}" style="color: #fff !important;">${this.content}</div>
          <button class="${this.class_popupClose}">X</button>
        </div>
      </div>
    `;

    this.openPopup(content);
  }

  closePopup() {
    this.box.innerHTML = '';
  }
  
  createPopupBox(){
    if (!document.getElementById(this.id_popupBox)) {
      document.body.insertAdjacentHTML('afterbegin', `<div id="${this.id_popupBox}"></div>`);
    }
    
    this.box = document.getElementById(this.id_popupBox);
  }
  
  bind() {
    this.closePopup = this.closePopup.bind(this);
    this.constructorElementsPopup = this.constructorElementsPopup.bind(this);
  }

  init() {
    if (!this.content) return;

    this.bind();
    this.createPopupBox();
    this.constructorElementsPopup();
  }
}