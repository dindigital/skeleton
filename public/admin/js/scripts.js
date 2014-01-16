$(document).ready(function() {
  $('.ajax_pagina_cat_pagina').change(function() {
    var id = $(this).val();

    $('.drop_pagina_line.other').remove();
    $('.drop_pagina_line').hide();

    if (id != '0') {
      var url = '/admin/pagina/ajax_pagina_cat_pagina/' + id + '/';

      $.get(url, function(data) {
        $('.drop_pagina_container').html(data);
        $('.drop_pagina_line').show();
        intinity_event();
      });
    }
  });

  intinity_event();

});

function intinity_event()
{
  $('.ajax_pagina_infinita').unbind();
  $('.ajax_pagina_infinita').change(function() {
    var id = $(this).val();
    var duplication_container = $(this).parents('.drop_pagina_line');

    duplication_container.nextAll('.drop_pagina_line').remove();
    if (id != '0') {
      var url = '/admin/pagina/ajax_pagina_infinita/' + id + '/';

      $.get(url, function(data) {
        var clone = duplication_container.clone();
        clone.find('.drop_pagina_container').html(data);
        clone.addClass('other');

        duplication_container.after(clone);

        intinity_event();
      });
    }
  });

}