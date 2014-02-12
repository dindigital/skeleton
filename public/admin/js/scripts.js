//

$(document).ready(function() {
  $('.mailing_export').click(function() {
    var querystring = $(this).attr('querystring');
    var url = '/admin/mailing_export/xls/?' + querystring;

    window.open(url);
  });
});