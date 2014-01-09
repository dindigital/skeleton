$(document).ready(function() {

  var link_prefix = $('#link_prefix').val();

  $('.btn_lista').click(function() {
    var action = link_prefix + 'lista/';
    location.href = action;
    return false;
  });

  $('.btn_cadastro').click(function() {
    var action = link_prefix + 'cadastro/';
    location.href = action;
    return false;
  });

  $('.setAtivo').change(function() {
    var ativo = ($(this).is(':checked')) ? '1' : '0';
    var action = $('#link_prefix').val() + 'ativo/';
    var id = $(this).attr('id');

    $.post(action, {
      ativo: ativo,
      id: id
    });
  });

  $('.lixeira_re').click(function() {

    if ($('.excluir').is(':checked') !== true) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    var form = newForm();

    $('.excluir:checked').each(function() {
      var id = $(this).attr('id').replace('exc_', '');
      form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
    });

    var action = link_prefix + 'restaurar/';

    form.attr('action', action);
    form.submit();
  });


  $('.lixeira_ex').click(function() {

    if ($('.excluir').is(':checked') !== true) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    var msg = 'Deseja realmente excluir os ítens selecionados?';
    if ($('.excluir:checked').length === 1) {
      msg = 'Deseja realmente excluir o item selecionado?';
    }

    var c = confirm(msg);

    if (c) {
      var form = newForm();

      $('.excluir:checked').each(function() {
        var id = $(this).attr('id').replace('exc_', '');
        form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
      });

      var action = link_prefix + 'excluir/';

      form.attr('action', action);
      form.submit();
    }
  });

});

function newForm()
{
  return $('<form method="POST"></form>').appendTo('body');
}