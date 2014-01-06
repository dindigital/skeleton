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

    if ($(this).is(':checked')) {
      $('.excluir').attr('checked', true);
    } else {
      $('.excluir').attr('checked', false);
    }

    /*var checked = $(this).is(':checked');
     $('.excluir').each(function() {
     if (checked) {
     $(this).attr('checked', true);
     $(this).parent('td').addClass('checked');
     } else {
     $(this).attr('checked', false);
     $(this).parent('td').removeClass('checked');
     }
     });*/

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
    $('.excluir').attr('checked', false);
    $(this).parents('tr').find('.excluir').attr('checked', true);
    $('.lixeira_ex').click();
    return;
  });

  $(".tags").select2({tags: 0, width: '100%'});

  $(".select2").select2({
    width: '100%'
  });

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

  $(".cepMask").mask("99999-999");
  $(".cpfMask").mask("999.999.999-99");
  $(".cnpjMask").mask("99.999.999/9999-99");
  $(".telefoneMask").mask("(99) ?999999999");
  $(".dataMask:visible").mask("99/99/9999");
  $(".horaMask:visible").mask("99:99");
  $(".horafullMask:visible").mask("99:99:99");

  $(".datepicker").datepicker({
    dateFormat: 'dd/mm/yy',
    showOn: 'focus'
  });

  $(".sortable").sortable();

  $(".color_basic").spectrum({
    color: "#f00",
    preferredFormat: "hex",
    showInput: true
  });

  $(".color_full").spectrum({
    color: "#ECC",
    showInput: true,
    className: "full-spectrum",
    showInitial: true,
    showPalette: true,
    showSelectionPalette: true,
    maxPaletteSize: 10,
    preferredFormat: "hex",
    localStorageKey: "spectrum.demo",
    move: function(color) {

    },
    show: function() {

    },
    beforeShow: function() {

    },
    hide: function() {

    },
    change: function() {

    },
    palette: [
      ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
      ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
      ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
        "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
        "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
        "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
        "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
        "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
        "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
        "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
        "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
        "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
    ]
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