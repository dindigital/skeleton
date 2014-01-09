$(document).ready(function() {

  $('.ajaxform').ajaxForm({
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
              $('.ajaxform').find("[name='" + field + "']").focus();
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