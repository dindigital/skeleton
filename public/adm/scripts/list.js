function sadmList() {

  $(".datatable tbody tr:even").addClass("even");
  $(".datatable tbody tr:odd").addClass("odd");

  $(".datatable tr").hover(function() {
    $(this).find("ul").addClass("visible");
  },
          function() {
            $(this).find("ul").removeClass("visible");
          });


  $('.drop_ordem').change(function() {
    var ordem = $(this).val();
    var id = $(this).attr('id');
    var link = $('#link_ordem').val();

    $('#main_form').append('<input type="hidden" name="ordem" value="' + ordem + '" />');
    $('#main_form').append('<input type="hidden" name="id" value="' + id + '" />');
    $('#main_form').attr('action', link);
    $('#main_form').attr('method', 'post');

    $('#main_form').submit();

    /*$.post(link,{
     id:id,
     ordem:ordem
     },function(data){
     console.log(data);
     if (data.redirect){
     _alljax(data.redirect);
     }
     },'json');*/
  });

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

  //_# ===================== LIXERIA ===========================================
  $('.lixeira_go').unbind();
  $('.lixeira_go').click(function() {
    if ($('.excluir:checked').length == 0) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    $('#magicfields').html('');

    $('.excluir:checked').each(function() {
      var id = $(this).attr('id').replace('exc_', '');
      $('#magicfields').append('<input type="hidden" name="itens[]" value="' + id + '" />');
    });

    var link_prefix = $('#link_prefix').val();
    var action = link_prefix + 'lixeira/';

    $('#main_form').attr('action', action);
    $('#main_form').attr('method', 'post');
    $('#main_form').submit();
  });

  $('.lixeira_re').unbind();
  $('.lixeira_re').click(function() {

    if ($('.excluir:checked').length == 0) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    $('#magicfields').html('');

    $('.excluir:checked').each(function() {
      var id = $(this).attr('id').replace('exc_', '');
      $('#magicfields').append('<input type="hidden" name="itens[]" value="' + id + '" />');
    });

    var link_app = $('#link_app').val();
    var action = link_app + 'lixeira/restaurar/';

    $('#main_form').attr('action', action);
    $('#main_form').attr('method', 'post');
    $('#main_form').submit();
  });

  $('.lixeira_ex').unbind();
  $('.lixeira_ex').click(function() {

    if ($('.excluir:checked').length == 0) {
      alert('Não há nenhum ítem selecionado.');
      return;
    }

    var c = confirm('Deseja realmente excluir os ítens selecionados?');

    if (c) {
      var form = $('<form method="POST"></form>').appendTo('body');

      $('.excluir:checked').each(function() {
        var id = $(this).attr('id').replace('exc_', '');
        form.append('<input type="hidden" name="itens[]" value="' + id + '" />');
      });

      var link_prefix = $('#link_prefix').val();
      var action = link_prefix + 'excluir/';

      form.attr('action', action);
      form.submit();
    }
  });

  $('a.lixeira_shortcut').unbind();
  $('a.lixeira_shortcut').click(function() {
    $(this).parents('tr').find('input.excluir').attr('checked', 'checked');
    $('.lixeira_go').click();
    return;
  });

  $('a.excluir_shortcut').unbind();
  $('a.excluir_shortcut').click(function() {
    $('input.excluir').removeAttr('checked');
    $(this).parents('tr').find('input.excluir').attr('checked', 'checked');
    $('.lixeira_ex').click();
    return;
  });

  //_# ATIVO
  $('.setAtivo').change(function() {
    var ativo = ($(this).is(':checked')) ? '1' : '0';
    var action = $('#link_prefix').val() + 'ativo/';
    var id = $(this).attr('id');

    $.post(action, {
      ativo: ativo,
      id: id
    });
  });

  //_# =========================================================================

  $('.limpar_busca').click(function() {
    $('#main_form input').each(function() {
      $(this).val('');
    });
    $('#main_form select').each(function() {
      $(this).val('0');
      $(this).parent().find('span').html($(this).children('option').eq(0).text());
    });
  });

  //_# BUSCAR
  $('#buscar').click(function() {
    $('#main_form').attr('method', 'get');
    $('#main_form').attr('action', $(this).attr('action'));
  });

  $('.lista_log tr:odd').addClass('lista_log_odd');
  $('.lista_log tr:even').addClass('lista_log_even');
}