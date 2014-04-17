//

$(document).ready(function() {
  $('.mailing_export').click(function() {
    var querystring = $(this).attr('querystring');
    var url = '/admin/mailing_export/?' + querystring;

    window.open(url);
  });

  $('.ajaxcat').selectajax({
    onComplete: function(element) {
      $(".select2").select2("destroy");
      $(".select2").select2({
        width: '100%'
      });
    }
  });
});