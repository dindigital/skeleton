//

$(document).ready(function() {
  $('.mailing_export').click(function() {
    var querystring = $(this).attr('querystring');
    var url = '/admin/mailing_export/?' + querystring;

    window.open(url);
  });

  $('.ajaxcat').selectajax({
    onComplete: function(data) {
      $(data).select2({
        width: '100%'
      });
    }
  });
});