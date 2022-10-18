(function ($) {

  $('document').ready(() => {

    const colorButtons = $("[data-toggle-event-color]");

    colorButtons.each((item, button) => {
      const tag = $(button);
      tag.click(() => {
        tag.parent().toggleClass('teaser-color');
      });
    });
  });


})(jQuery);
