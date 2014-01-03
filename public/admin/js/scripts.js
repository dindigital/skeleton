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