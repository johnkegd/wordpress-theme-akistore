(function ($) {
  "use strict";

  $(document).on("akistoreShopPageInit", function () {
    akistoreThemeModule.sitescroll();
  });

  akistoreThemeModule.sitescroll = function () {
    const container = document.querySelectorAll(".site-scroll");

    for (var i = 0; i < container.length; i++) {
      const ps = new PerfectScrollbar(container[i], {
        suppressScrollX: true,
      });
    }
  };

  $(document).ready(function () {
    akistoreThemeModule.sitescroll();
  });
})(jQuery);
