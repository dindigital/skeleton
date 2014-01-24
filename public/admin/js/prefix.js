$(document).ready(function() {

  $(".cl-vnavigation li ul").each(function() {
    $(this).parent().addClass("parent");
  });

  $(".cl-vnavigation").delegate(".parent > a", "click", function(e) {
    var ul = $(this).parent().find("ul");
    ul.slideToggle(300, 'swing', function() {
      var p = $(this).parent();
      if (p.hasClass("open")) {
        p.removeClass("open");
      } else {
        p.addClass("open");
      }
    });
    e.preventDefault();
  });

  $(".cl-toggle").click(function(e) {
    var ul = $(".cl-vnavigation");
    ul.slideToggle(300, 'swing', function() {
    });
    e.preventDefault();
  });

  var link_prefix = $('#link_prefix').val();

  $('.btn_list').click(function() {
    var action = link_prefix + 'list/';
    location.href = action;
    return false;
  });

  $('.btn_save').click(function() {
    var action = link_prefix + 'save/';
    location.href = action;
    return false;
  });

  $('.btn_edit').click(function() {
    var id = $(this).attr('href');
    var action = link_prefix + 'save/' + id + '/';
    location.href = action;
    return false;
  });

  $('.setActive').change(function() {
    var active = ($(this).is(':checked')) ? '1' : '0';
    var action = $('#link_prefix').val() + 'active/';
    var id = $(this).attr('id');

    $.post(action, {
      active: active,
      id: id
    });
  });

  $('.trash_re').click(function() {

    if ($('.delete').is(':checked') !== true) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    var form = newForm();

    $('.delete:checked').each(function() {
      var id = $(this).attr('id').replace('exc_', '');
      form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
    });

    var action = link_prefix + 'restore/';

    form.attr('action', action);
    form.submit();
  });


  $('.trash_ex').click(function() {

    if ($('.delete').is(':checked') !== true) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    var msg = 'Deseja realmente excluir os ítens selecionados?';
    if ($('.delete:checked').length === 1) {
      msg = 'Deseja realmente excluir o item selecionado?';
    }

    var c = confirm(msg);

    if (c) {
      var form = newForm();

      $('.delete:checked').each(function() {
        var id = $(this).attr('id').replace('exc_', '');
        form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
      });

      var action = link_prefix + 'delete/';

      form.attr('action', action);
      form.submit();
    }
  });

  $('.drop_sequence').change(function() {
    var sequence = $(this).val();
    var id = $(this).attr('id');
    var link = $('#link_prefix').val() + 'sequence/';

    var form = newForm();

    form.append('<input type="hidden" name="sequence" value="' + sequence + '" />');
    form.append('<input type="hidden" name="id" value="' + id + '" />');
    form.attr('action', link);

    form.submit();
  });

  $('.ajax_intinify_cat').change(function() {
    var id = $(this).val();

    $('.drop_infinity_line.other').remove();
    $('.drop_infinity_line').hide();

    if (id != '0') {
      var url = $('#link_prefix').val() + 'ajax_intinify_cat/' + id + '/';

      $.get(url, function(data) {
        $('.drop_infinity_container').html(data);
        $('.drop_infinity_line').show();
        intinity_event();
      });
    }
  });

  intinity_event();

});

function newForm()
{
  return $('<form method="POST"></form>').appendTo('body');
}

function intinity_event()
{
  $('.ajax_infinity').unbind();
  $('.ajax_infinity').change(function() {
    var id = $(this).val();
    var duplication_container = $(this).parents('.drop_infinity_line');

    duplication_container.nextAll('.drop_infinity_line').remove();
    if (id != '0') {
      var url = $('#link_prefix').val() + 'ajax_infinity/' + id + '/';

      $.get(url, function(data) {
        var clone = duplication_container.clone();
        clone.find('.drop_infinity_container').html(data);
        clone.addClass('other');

        duplication_container.after(clone);

        intinity_event();
      });
    }
  });

}