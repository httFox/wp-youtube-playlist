const class_box = httfox_wyp_class_item || null;
const class_observer = httfox_wyp_class_item_observer || null;
const prop_width = httfox_wyp_prop_width || null;
const prop_height = httfox_wyp_prop_height || null;
const data_id = httfox_wyp_data_id || null;

if (typeof httfox_wyp_adjustHeightTo16by9WithResize !== 'undefined') {
  if (class_box && class_observer) {
    httfox_wyp_adjustHeightTo16by9WithResize(`.${class_box}`, `.${class_observer}`, prop_width, prop_height);
    window.addEventListener('resize', () => httfox_wyp_adjustHeightTo16by9WithResize(`.${class_box}`, `.${class_observer}`, prop_width, prop_height));
  }
}

if (typeof httfox_wyp_gerencyPopups !== 'undefined') {
  httfox_wyp_gerencyPopups(`[${data_id}]`);
}