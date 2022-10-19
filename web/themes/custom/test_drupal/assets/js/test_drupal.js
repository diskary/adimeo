(function($){
  $(document).ready(() => {
    $('#block-eventblock').hide();
    $('[data-test]').on("click", () => {
      $('#block-eventblock').dialog({
        resizable: false,
        height: "auto",
        width: 600,
        modal: true,
      });
    });
  });

})(jQuery);
