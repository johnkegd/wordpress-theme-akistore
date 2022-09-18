(function ($) {
  "use strict";

  $(document).on("akistoreShopPageInit", function () {
    akistoreThemeModule.productHoverSlider();
  });

  akistoreThemeModule.productHoverSlider = function () {
    hoverSlider.init({});
    hoverSlider.prepareMarkup();
  };

  $(document).ready(function () {
    akistoreThemeModule.productHoverSlider();
  });
})(jQuery);
