(function ($) {

  $('document').ready(() => {

    const colorButtons = $(".t-background-color");

    colorButtons.each((item, button) => {
      const tag = $(button);
      tag.click(() => {
        tag.parent().toggleClass('teaser-color');
      });
    });
  });

})(jQuery);
