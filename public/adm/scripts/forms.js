function sadmForms() {

  // Form inputs

  $("fieldset > div > input[type=text]").addClass("text");
  $("fieldset > div > input[type=email]").addClass("text");
  $("fieldset > div > input[type=number]").addClass("text");
  $("fieldset > div > input[type=password]").addClass("text");
  $("fieldset > div > textarea").addClass("textarea");
  $("fieldset > div > input[type=checkbox]").addClass("checkbox");
  $("fieldset > div > input[type=radio]").addClass("radio");
  $("fieldset > div > input[type=checkbox].indeterminate").prop("indeterminate", true);

  // Form Validation

  if ($.fn.validate) {
    $(".validate_form").validate();
  }

  // Textxarea Autogrow

  if ($.fn.autoGrow) {
    $('textarea.autogrow').autoGrow();
  }

  // Input Datepicker Config
  if ($.fn.datepicker) {
    $(".datepicker").datepicker({
      dateFormat: 'dd/mm/yy',
      showOn: 'focus'
    });
  }

  if ($.fn.buttonset) {
    $(".jqui_checkbox").buttonset();

    $(".jqui_radios").buttonset();
    $(".jqui_radios > label").on("click", function() {
      $(this).siblings().removeClass("ui-state-active");
    }); // jQuery UI radio buttonset fix
  }


  if ($.fn.uniform) {
    $(".uniform input, .uniform, .uniform a, .time_picker_holder select").uniform();
    //setTimeout('$(".uniform input, .uniform, .uniform a, .time_picker_holder select").uniform();',10);
  }

  if ($.fn.multiselect) {
    $(".multisorter").multiselect({});
  }


  if ($.fn.sortable) {
    $(".sortable").sortable();
    //$( ".sortable" ).disableSelection();
  }

  // Colour Picker

  if ($.fn.ColorPicker) {

    $('#colorpicker_inline').ColorPicker({
      flat: true
    });

    $('.colorpicker_popup').ColorPicker({
      onSubmit: function(hsb, hex, rgb, el) {
        $(el).val(hex);
        $(el).ColorPickerHide();
      },
      onBeforeShow: function() {
        $(this).ColorPickerSetColor(this.value);
      }
    })
            .on('keyup', function() {
      $(this).ColorPickerSetColor(this.value);
    });
  }


  // Autocomplete

  var autoCompleteList = [
    "ActionScript",
  ];
  $(".autocomplete").autocomplete({
    source: autoCompleteList
  });


  if ($.fn.dialog) {
    $(".dialog_content").dialog({
      autoOpen: false,
      resizable: false,
      show: "fade",
      hide: "fade",
      modal: true,
      width: "500",
      show:{
        effect: "fade",
        duration: 500
      },
      hide:{
        effect: "fade",
        duration: 500
      },
      create: function() {
        $('.dialog_content.no_dialog_titlebar').dialog('option', 'dialogClass', 'no_dialog_titlebar');
      },
      open: function() {
        setTimeout(columnHeight, 100);
      }
    });

    $(".dialog_button").live("click", function() {
      var theDialog = $(this).attr('data-dialog');
      $("#" + theDialog).dialog("open"); // the #dialog element activates the modal box specified above
      return false;
    });

    $(".close_dialog").live("click", function() {
      $(".dialog_content").dialog("close"); // the #dialog element activates the modal box specified above
      return false;
    });

    $(".link_button").live("click", function() {
      var x = $(this).attr("data-link");

      window.location.href = x;

      return false;
    });

    $(".dialog_content.very_narrow").dialog("option", "width", 300);
    $(".dialog_content.narrow").dialog("option", "width", 450);
    $(".dialog_content.wide").dialog("option", "width", 650);
    $(".dialog_content.medium_height").dialog("option", "height", 315);

    $(".dialog_content.no_modal").dialog("option", "modal", false);
    $(".dialog_content.no_modal").dialog("option", "draggable", false);

    $(".ui-widget-overlay").live("click", function() {
      $(".dialog_content").dialog("close");
      return false;
    });

  }

  $(".cepMask").mask("99999-999");
  $(".cpfMask").mask("999.999.999-99");
  $(".cnpjMask").mask("99.999.999/9999-99");
  $(".telefoneMask").mask("(99) ?999999999");
  $(".dataMask:visible").mask("99/99/9999");
  $(".horaMask:visible").mask("99:99");
  $(".horafullMask:visible").mask("99:99:99");


  //============================================================================
  //_______________________________# PLUPLOAD #_________________________________
  //============================================================================
  //
  // armazena o total de campos plupload para que possamos enviar todos
  var totalPluploadFields = $('.pupload').length;
  $('#main_form').submit(function() {

    // se o total de campos pluploa for 0, então libera o envio do formulário.
    if (totalPluploadFields == 0) {
      return true;
    }

    $('.pupload').each(function() {
      var uploader = $(this).pluploadQueue();

      // há ítens na fila de upload, então envie todos
      if (uploader.files.length > (uploader.total.uploaded + uploader.total.failed)) {
        // ao completar o envio da fila
        uploader.bind('UploadComplete', function() {
          // subtrai 1 no total de campos plupload
          totalPluploadFields -= 1;
          // chama o evento submit novamnete.
          $('#main_form').submit();
        });

        // envia o upload
        uploader.start();
        showLoadingOverlay();
        return false;
        // não houve upload, nem há nada na fila
      } else if ((uploader.total.uploaded + uploader.total.failed) == 0) {
        if ($(this).hasClass('obg')) {
          var fieldname = $(this).attr('id');
          showError('Por favor selecionar um arquivo no campo de upload ' + fieldname);
          return false;
        } else {
          totalPluploadFields -= 1;

          if (totalPluploadFields == 0) {
            $('#main_form').submit();
          }
        }
      }

    });

    // se o código chegou aqui, o retorno é false
    return false;
  });

//  //============================================================================
//  //______________________________# UPLOADIFY #_________________________________
//  //============================================================================
//  //
//  // armazena o total de campos uploadify para que possamos enviar todos
//  var totalUploadifyFields = $('.uploadify').length;
//
//  // essa variavel guarda o id do uploadify atual. É necessária para poder
//  // acessar o objeto dom de dentro dos eventos. O correto seria acessar com
//  // $(this), mas o Uploadify não reconhece este tipo de acesso.
//  var UploadifyActualId = '';
//
//  // armazena os nomes e valores dos campos input hidden
//  var arrInputs = {};
//
//  $('#main_form').submit(function() {
//
//    // se o total de campos uploadify for 0, então libera o envio do formulário.
//    if (totalUploadifyFields == 0) {
//      return true;
//    }
//    //neste ponto, existem uploads a serem executados.
//
//    // iterando sobre os objetos uploadify
//    $('.uploadify').each(function() {
//      var uploader = $(this);
//
//      // imitando funcionamento do pupload criando input hidden
//      // escreve o input hidden que carega o total de arquivos de um uploadify
//      $('#main_form').append('<input type="hidden" name="' + uploader.attr('id') + '_count" />');
//
//      // evento chamado a cada arquivo subido.
//      uploader.uploadify('settings', 'onUploadSuccess', function(file, data, response) {
//        data = $.parseJSON(data);
//
//        if (data.error == '1') {
//          alert('Houve um erro ao subir o arquivo ' + file.name + ': ' + data);
//          console.log(data);
//        } else {
//          // exporta o id do upload atual
//          UploadifyActualId = data.file_id;
//          // nome do arquivo temporário
//          var tmpname = data.tmpname;
//          // nome original do aquivo
//          var name = data.name;
//
//          if (!arrInputs[UploadifyActualId])
//            arrInputs[UploadifyActualId] = [];
//
//          arrInputs[UploadifyActualId].push({
//            tmpname: tmpname,
//            name: name
//          });
//        }
//      });
//
//      // ao completar uma fila de uploads
//      uploader.uploadify('settings', 'onQueueComplete', function(queueData) {
//        // subtrai 1 do total de campos uploadify. Desta forma quando chegarmos
//        // em 0, significa que todos já foram completados.
//        totalUploadifyFields -= 1;
//
//        // altera o valor do campo para carregar o numero total de arquivos
//        var total = queueData.uploadsSuccessful;
//        var fieldname3 = UploadifyActualId + '_count';
//        $('input[name="' + fieldname3 + '"]').val(total);
//
//        // cria 2 input hidden para cada arquivo, contendo tempname e name
//        $.each(arrInputs[UploadifyActualId], function(i, o) {
//          var fieldname1 = UploadifyActualId + '_' + i + '_tmpname';
//          var fieldname2 = UploadifyActualId + '_' + i + '_name';
//          $('#main_form').append('<input type="hidden" name="' + fieldname1 + '" value="' + o.tmpname + '">');
//          $('#main_form').append('<input type="hidden" name="' + fieldname2 + '" value="' + o.name + '">');
//        });
//
//        // chama o evento submit novamnete. Todo este código será executado
//        // novamente, porém com -1 no totalUploadifyFields
//        $('#main_form').submit();
//      });
//
//      // há ítens na fila de upload, então envie todos
//      if (uploader.data('uploadify').queueData.queueLength > 0) {
//        uploader.uploadify('upload', '*');
//        showLoadingOverlay();
//        return false;
//        // não houve upload, nem há nada na fila
//      } else if (uploader.data('uploadify').queueData.uploadsSuccessful == 0) {
//        if (uploader.uploadify('settings', 'required')) {
//          var fieldname = $(this).attr('id');
//          showError('Por favor selecionar um arquivo no campo de upload ' + fieldname);
//          return false;
//        } else {
//          totalUploadifyFields -= 1;
//
//          if (totalUploadifyFields == 0)
//            $('#main_form').submit();
//        }
//      }
//
//    });
//
//    // se o código chegou aqui, o retorno é false
//    return false;
//  });

  $('#main_form').ajaxForm({
    dataType: 'json',
    beforeSubmit: function() {
      showLoadingOverlay();
      $('.alert_red').hide();
      $('.alert_red span').html('');
      $('.alert_green').hide();
      $('.alert_green span').html('');
    },
    success: function(data) {
      // Depois de enviar, removo tela de loading
      hideLoadingOverlay();
      // Faço um switch para verificar o tipo de retorno
      switch (data.type) {
        case 'error':
          // No caso do retorno conter erro, faço o each no objeto adicionando ao DOM
          $.each(data.errorDetail, function(key, value) {
            $('.alert_red span').append('<p>' + value.msg + '</p>');
            if (key === 0) {
              // No caso do primeiro item, dou um focus pelo nome do field
              $('#main_form').find("[name='" + value.field + "']").focus();
            }
          });
          boxError();
          break;
        case 'redirect':
          location.href = data.uri;
          break;
        case 'success':
          $('.alert_green span').append('<p>' + data.message + '</p>');
          $('.alert_green').stop(true, true).slideDown('slow').animate({opacity: 1}, 4000, function() {
            $('.alert_green').slideUp();
          });
          break;
      }
    },
    error: function() {
      hideLoadingOverlay();
      $('.alert_red span').append('<p>Erro no envio do formulário. Por favor entre em contato com o suporte</p>');
      boxError();
    }
  });

  //_# EVENTO DE DOUBLE CLICK AO CLICAR EM FOTO DE GALERIA -> REMOVER ELEMENTO
  $('.img_galeria').dblclick(function() {
    $(this).parents('li').remove();
  });

  $('.link').click(function() {
    var target = $(this).attr('target');
    var href = $(this).attr('href');

    if (target) {
      window.open(href, '_newtab');
    } else {
      location.href = href;
    }
  });

  //_# LIMITE / CONTADOR DE CARACTERES
  $('.limit_text').each(function() {
    var e = $(this);
    var maxlength = parseInt(e.attr('maxlength'));
    var e_target = $(this).prev('.limit_info');

    var total_atual = $(e).val().length;
    e_target.html(total_atual + ' de ' + maxlength);

    $(e).textareaCount({
      'maxCharacterSize': maxlength,
      'originalStyle': '',
      'warningStyle': '',
      'displayFormat': ''
    }, function(data) {
      var texto = data.input + ' de ' + data.max;
      e_target.html(texto);
    });
  });

  //_# LIST BOX AJAX
  $('.ajax_list').each(function() {
    var busca = $(this).next('.ui-multiselect').find('.search');
    busca.unbind();
    busca.bind('keyup', li_busca_bind);
  });

  //_# DROPDOWNS
  $('.ambito').change(function() {
    var id = $(this).val();

    if (id == '0') {
      $('.sub').html('');
      $('.sub').parents('fieldset').hide();

    } else {
      $('.sub').html('<img id="loading_drop" src="/adm/plugins/ui/css/smoothness/images/ui-anim_basic_16x16.gif" />');
      $('.sub').parents('fieldset').show();

      $.get('/adm005/conselho_cat/dropdown_by_ambito/', {
        id_ambito: id,
      }, function(data) {
        if (data != '') {
          $('.sub').html(data);
          $('.sub').parents('fieldset').show();

        } else {
          $('.sub').html('');
          $('.sub').parents('fieldset').hide();
        }

        busca_drop_down_conselho_cat();
      });
    }
    $('.sub2').html('');
    $('.sub2').parents('fieldset').hide();
  });

  busca_drop_down_conselho_cat();


}

var busca_drop_down_conselho_cat = function()
{
  $('.conselho_cat').change(function() {
    var id = $(this).val();

    if (id == '0') {
      $('.sub2').html('');
      $('.sub2').parents('fieldset').hide();
    } else {
      $('.sub2').html('<img id="loading_drop" src="/adm/plugins/ui/css/smoothness/images/ui-anim_basic_16x16.gif" />');
      $('.sub2').parents('fieldset').show();

      $.get('/adm005/conselho/dropdown_by_conselho_cat/', {
        id_conselho_cat: id,
      }, function(data) {
        if (data != '') {
          $('.sub2').html(data);
          $('.sub2').parents('fieldset').show();

        } else {
          $('.sub2').html('');
          $('.sub2').parents('fieldset').hide();
        }
      });
    }
  });

}

var li_busca_bind = function() {
  var str = $(this).val();
  var post = {};

  if (str.length >= 3) {

    var busca = $(this);
    var select = $(this).parents('.ui-multiselect').prev('select');
    var id = select.attr('id');
    var avaliable = $(this).parents('.ui-multiselect').children('.available').find('.connected-list');

    post['term'] = str;
    post['exclude'] = new Array();
    select.children('option:selected').each(function() {
      post['exclude'].push($(this).val());
    });

    busca.addClass('ui-autocomplete-loading');

    var url = $('#main_form').attr('action');

    var regExp = /^\/adm005\/([a-zA-Z0-9_-]+)\//;
    var parseUrl = regExp.exec(url);

    $.ajax({
      type: "POST",
      url: '/adm005/' + parseUrl[1] + '/atualiza_listbox/' + id + '/',
      data: post,
      success: li_post_callback,
      dataType: 'json',
      busca: busca,
      select: select,
      avaliable: avaliable
    });
  }
}

var li_post_callback = function(data) {
  var select = this.select;
  var busca = this.busca;
  var avaliable = this.avaliable;

  avaliable.html(''); //clear
  select.children('option').not(':selected').remove();

  $.each(data, function(i, o) {

    if (select.children('option[value="' + o.id + '"]').length == 0) {
      select.append('<option value="' + o.id + '">"' + o.label + '"</option>');
    }
  });

  select.multiselect('reload');
  //select.multiselect('destroy').multiselect();
  busca.removeClass('ui-autocomplete-loading');
  avaliable.children('li').show();

}

function hide_save_msg()
{
  setTimeout(function() {
    $('#alert_salvo').trigger('click');
  }, 3000);
}

function boxError() {
  $('.alert_red').stop(true, true).slideDown('slow').animate({opacity: 1}, 4000, function() {
    $('.alert_red').slideUp();
  });
}