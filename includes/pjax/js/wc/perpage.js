/* global akistore_settings */
(function ($) {
  akistoreThemeModule.$document.on("akistoreShopPageInit", function () {
    akistoreThemeModule.perpage();
  });

  akistoreThemeModule.perpage = function () {
    var $wcperpage = $(".products-per-page");

    $wcperpage.on("change", "select.perpage", function () {
      var $form = $(this).closest("form");
      $form.find('[name="_pjax"]').remove();

      $.pjax({
        container: ".site-content",
        timeout: akistore_settings.pjax_timeout,
        url: "?" + $form.serialize(),
        scrollTo: false,
        renderCallback: function (context, html, afterRender) {
          context.html(html);
          afterRender();
        },
      });
    });

    $wcperpage.on("submit", function (e) {
      e.preventDefault(e);
    });
  };

  $(document).ready(function () {
    akistoreThemeModule.perpage();
  });
})(jQuery);
