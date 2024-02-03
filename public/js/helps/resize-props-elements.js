// function httfox_wyp_heightByProportion(selectorBox, selectorToElementObserver, prop_width = 16, prop_height = 9) {
//   const containers = document.querySelectorAll(selectorBox);
//   if (!containers.length) return;

//   containers.forEach(function (container) {
//     const item = container.querySelector(selectorToElementObserver);
    
//     if (container && item) {
//       const containerWidth = container.offsetWidth;
//       const desiredHeight = (containerWidth * prop_height) / prop_width;

//       item.style.height = desiredHeight + 'px';
//     }
//   });
// }


function httfox_wyp_heightByProportion(selectorElementResize, selectorToElementBase = null, prop_width = 16, prop_height = 9) {

  function resize() {
    const search = selectorToElementBase ? `${selectorToElementBase} ${selectorElementResize}` : selectorElementResize;
    const elements = document.querySelectorAll(search);
  
    elements.forEach((element) => {
      if (element) {
        const containerWidth = element.offsetWidth;
        const desiredHeight = (containerWidth * prop_height) / prop_width;
  
        element.style.height = desiredHeight + 'px';
      }
    });
  }

  resize();
  window.addEventListener('resize', resize);
}
