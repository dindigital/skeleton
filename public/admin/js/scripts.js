$(window).load(function() {

  hideLoadingOverlay();

});

$(document).ready(function() {

// // PLUPLOAD
// armazena o total de campos plupload para que possamos enviar todos
  var totalPluploadFields = $('.pupload').length;
  $('#main_form').submit(function() {

    // se o total de campos pluploa for 0, então libera o envio do formulário.
    if (totalPluploadFields == 0) {
      return true;
    }

    var retorno = false;
    $('.pupload').each(function() {
      var uploader = $(this).pluploadQueue();

      // há ítens na fila de upload, então envie todos
      if (uploader.files.length > (uploader.total.uploaded + uploader.total.failed)) {
        // ao completar o envio da fila
        uploader.bind('UploadComplete', function() {
          // chama o evento submit novamnete.
          $('#main_form').submit();
        });

        // envia o upload
        uploader.start();
        showLoadingOverlay();
        retorno = false;
        return false;
      } else {
        // não houve upload, nem há nada na fila
        retorno = true;
        return true;
      }

    });

    return retorno;

    // se o código chegou aqui, o retorno é false
    return false;
  });

  $('#main_form').ajaxForm({
    dataType: 'json',
    beforeSubmit: function() {
      showLoadingOverlay();
      $('.alert-danger').hide();
      $('.alert-danger div').html('');
      $('.alert-success').hide();
      $('.alert-success div').html('');
    },
    success: function(data) {
      // Depois de enviar, removo tela de loading
      hideLoadingOverlay();
      // Faço um switch para verificar o tipo de retorno
      switch (data.type) {
        case 'error_message':
          $('.alert-danger div').append('<p>' + data.message + '</p>');
          boxError();
          break;
        case 'error_object':
          // No caso do retorno conter erro, faço o each no objeto adicionando ao DOM
          $.each(data.objects, function(key, value) {
            for (k in value) {
              var field = k;
              var msg = value[k];
            }
            $('.alert-danger div').append('<p>' + msg + '</p>');
            if (key === 0) {
              // No caso do primeiro item, dou um focus pelo nome do field
              $('#main_form').find("[name='" + field + "']").focus();
            }
          });
          boxError();
          break;
        case 'redirect':
          location.href = data.uri;
          break;
        case 'success':
          $('.alert-success div').append('<p>' + data.message + '</p>');
          $('.alert-success').stop(true, true).slideDown('slow').animate({opacity: 1}, 4000, function() {
            $('.alert-success').slideUp();
          });
          break;
      }
    },
    error: function() {
      hideLoadingOverlay();
      $('.alert-danger div').append('<p>Erro no envio do formulário. Por favor entre em contato com o suporte</p>');
      boxError();
    }
  });

  $('#excluir_all').change(function() {
    var checked = $(this).is(':checked');
    $('.excluir').each(function() {
      if (checked) {
        $(this).attr('checked', true);
        $(this).parent('td').addClass('checked');
      } else {
        $(this).attr('checked', false);
        $(this).parent('td').removeClass('checked');
      }
    });
  });

  var link_prefix = $('#link_prefix').val();

  $('.setAtivo').change(function() {
    var ativo = ($(this).is(':checked')) ? '1' : '0';
    var action = $('#link_prefix').val() + 'ativo/';
    var id = $(this).attr('id');

    $.post(action, {
      ativo: ativo,
      id: id
    });
  });

  $('.limpar_busca').click(function() {
    $('.form_busca input[type="text"]').each(function() {
      $(this).val('');
    });
    $('.form_busca select').each(function() {
      $(this).val('0');
      $(this).parent().find('span').html($(this).children('option').eq(0).text());
    });
  });

  $('.lixeira_ex').click(function() {

    if ($('.excluir').is(':checked')) {
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

  $('a.excluir_shortcut').click(function() {
    $('input.excluir').attr('checked', false);
    $(this).parents('tr').find('input.excluir').attr('checked', true);
    $('.lixeira_ex').click();
    return;
  });

});

function hideLoadingOverlay() {
  $("#loading_overlay .loading_message").delay(200).fadeOut();
  $("#loading_overlay").delay(300).fadeOut();
}

function showLoadingOverlay() {
  $("#loading_overlay .loading_message").show();
  $("#loading_overlay").show();
}

function boxError() {
  $('.alert-danger').stop(true, true).slideDown('slow').animate({opacity: 1}, 4000, function() {
    $('.alert-danger').slideUp();
  });
}

function newForm()
{
  return $('<form method="POST"></form>').appendTo('body');
}