function newForm()
{
  return $('<form method="POST"></form>').appendTo('body');
}

function sadmList() {

  var link_prefix = $('#link_prefix').val(); //ex: /adm/usuario/

  /**
   * Layout
   */
  $(".datatable tbody tr:even").addClass("even");
  $(".datatable tbody tr:odd").addClass("odd");
  $(".datatable tr").hover(function() {
    $(this).find("ul").addClass("visible");
  }, function() {
    $(this).find("ul").removeClass("visible");
  });

  //_# ===================== AÇÕES =============================================

  /**
   * Checkbox Excluir Todos
   */
  $('#excluir_all').change(function() {
    var checked = $(this).is(':checked');
    $('.excluir').each(function() {
      if (checked) {
        $(this).attr('checked', 'checked');
        $(this).parent('span').addClass('checked');
      } else {
        $(this).removeAttr('checked');
        $(this).parent('span').removeClass('checked');
      }
    });
  });

  /**
   * Excluir permanente
   */
  $('.lixeira_ex').click(function() {
    if ($('.excluir:checked').length == 0) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    var c = confirm('Deseja realmente excluir os ítens selecionados?');

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

  /**
   * Excluir permanente [Atalho]
   */
  $('a.excluir_shortcut').click(function() {
    $('input.excluir').removeAttr('checked');
    $(this).parents('tr').find('input.excluir').attr('checked', 'checked');
    $('.lixeira_ex').click();
    return;
  });

  /**
   * Ativo
   */
  $('.setAtivo').change(function() {
    var ativo = ($(this).is(':checked')) ? '1' : '0';
    var action = $('#link_prefix').val() + 'ativo/';
    var id = $(this).attr('id');

    $.post(action, {
      ativo: ativo,
      id: id
    });
  });

  /**
   * Limpar Busca
   */
  $('.limpar_busca').click(function() {
    $('.form_busca input').each(function() {
      $(this).val('');
    });
    $('.form_busca select').each(function() {
      $(this).val('0');
      $(this).parent().find('span').html($(this).children('option').eq(0).text());
    });
  });
}

//==============================================================================
//==============================================================================
//==============================================================================
//==============================================================================
//==============================================================================
//================= NÃO TA USANDO AINDA MAS VAI USAR ===========================
/**
 * Dropdown Ordem
 */
$('.drop_ordem').change(function() {
  var ordem = $(this).val();
  var id = $(this).attr('id');
  var link = $('#link_ordem').val();

  $('#main_form').append('<input type="hidden" name="ordem" value="' + ordem + '" />');
  $('#main_form').append('<input type="hidden" name="id" value="' + id + '" />');
  $('#main_form').attr('action', link);
  $('#main_form').attr('method', 'post');

  $('#main_form').submit();

});

/**
 * Enviar para Lixeira
 */
$('.lixeira_go').click(function() {
  if ($('.excluir:checked').length == 0) {
    alert('Não há nenhum ítem selecionado.');
    return;
  }

  var form = newForm();

  $('.excluir:checked').each(function() {
    var id = $(this).attr('id').replace('exc_', '');
    form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
  });

  var action = link_prefix + 'lixeira/';

  form.attr('action', action);
  form.submit();
});

/**
 * Enviar para Lixeira [Atalho]
 */
$('a.lixeira_shortcut').click(function() {
  $(this).parents('tr').find('input.excluir').attr('checked', 'checked');
  $('.lixeira_go').click();
  return;
});

/**
 * Lixeira Restaurar
 */
$('.lixeira_re').click(function() {

  if ($('.excluir:checked').length == 0) {
    alert('Não há nenhum ítem selecionado.');
    return;
  }

  var form = newForm();

  $('.excluir:checked').each(function() {
    var id = $(this).attr('id').replace('exc_', '');
    form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
  });

  var action = link_app + 'lixeira/restaurar/';

  form.attr('action', action);
  form.submit();
});
