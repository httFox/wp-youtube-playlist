function httfox_wyp_adjustHeightTo16by9(selectorBox, selectorToElementObserver, prop_width = 16, prop_height = 9) {
  const containers = document.querySelectorAll(selectorBox);
  if (!containers.length) return;

  containers.forEach(function (container) {
    const item = container.querySelector(selectorToElementObserver);

    if (container && item) {
      const containerWidth = container.offsetWidth;
      const desiredHeight = (containerWidth * prop_height) / prop_width;

      item.style.height = desiredHeight + 'px';
    }
  });
}

function httfox_wyp_adjustHeightTo16by9WithResize(selectorBox, selectorToElementObserver, prop_width = 16, prop_height = 9) {
  httfox_wyp_adjustHeightTo16by9(selectorBox, selectorToElementObserver, prop_width, prop_height);
  window.addEventListener('resize', () => httfox_wyp_adjustHeightTo16by9(selectorBox, selectorToElementObserver, prop_width, prop_height));
}
