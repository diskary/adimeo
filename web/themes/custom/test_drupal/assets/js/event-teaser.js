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

  const otherEvent = $('[data-test]');

  otherEvent.click(() => {
    $('#block-eventblock').toggleClass('b-hide');
  });

  /*
  div#block-eventblock {
    width: 300px;
    background: #522ae8;
    height: 300px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
   */


})(jQuery);
