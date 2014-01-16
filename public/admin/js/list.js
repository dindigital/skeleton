$(document).ready(function() {

  $('.limpar_busca').click(function() {
    $('.form_busca input[type="text"]').each(function() {
      $(this).val('');
    });
    $('.form_busca select').each(function() {
      $(this).val('0');
      $(this).parent().find('span').html($(this).children('option').eq(0).text());
    });
  });

  $("#excluir_all").click(function() {
    if ($("#excluir_all").is(':checked')) {
      $('.excluir').prop("checked", true);
    } else {
      $('.excluir').prop("checked", false);
    }
  });

  $('a.excluir_shortcut').click(function() {
    $('.excluir').prop("checked", false);
    $(this).parents('tr').find('.excluir').prop("checked", true);
    $('.lixeira_ex').click();
  });

});