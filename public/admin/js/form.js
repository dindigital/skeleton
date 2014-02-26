$(document).ready(function() {

  // // PLUPLOAD
  // armazena o total de campos plupload para que possamos enviar todos
  var totalPluploadFields = $('.pupload').length;
  $('.ajaxform').submit(function() {

    // se o total de campos pluploa for 0, então libera o envio do formulário.
    if (totalPluploadFields == 0) {
      return true;
    }

    var r = false;
    $('.pupload').each(function() {
      var uploader = $(this).pluploadQueue();

      // há ítens na fila de upload, então envie todos
      if (uploader.files.length > (uploader.total.uploaded + uploader.total.failed)) {
        // ao completar o envio da fila
        uploader.bind('UploadComplete', function() {
          // chama o evento submit novamnete.
          $('.ajaxform').submit();
        });

        // envia o upload
        uploader.start();
        showLoadingOverlay();
        r = false;
        return false;
      } else {
        // não houve upload, nem há nada na fila
        r = true;
        return true;
      }

    });

    return r;

    // se o código chegou aqui, o retorno é false
    return false;
  });

  $('.submit_list').click(function() {
    $("input[name='redirect']").val('list');
  });

  $('.submit_previous').click(function() {
    $("input[name='redirect']").val('previous');
  });

  //_# EVENTO DE DOUBLE CLICK AO CLICAR EM FOTO DE GALERIA -> REMOVER ELEMENTO
  $('.img_gallery').dblclick(function() {
    $(this).parents('li').remove();
  });

  $(".tags").select2({tags: 0, width: '100%'});

  $(".select2").select2({
    width: '100%'
  });

  $(".postcodeMask").mask("99999-999");
  $(".stateMask").mask("aa");
  $(".cpfMask").mask("999.999.999-99");
  $(".cnpjMask").mask("99.999.999/9999-99");
  $(".phoneFullMask").mask("(99) ?999999999");
  $(".phoneMask").mask("?999999999");
  $(".phoneDDDMask").mask("99");
  $(".dateMask:visible").mask("99/99/9999");
  $(".timeMask:visible").mask("99:99");
  $(".timeFullMask:visible").mask("99:99:99");
  $(".moneyMask").maskMoney({prefix: 'R$ ', allowNegative: false, thousands: '.', decimal: ',', affixesStay: false});

  $(".datepicker").datepicker({
    dateFormat: 'dd/mm/yy',
    showOn: 'focus'
  });

  $('.daterange').daterangepicker({
    opens: 'right',
    format: 'dd/MM/yyyy'
  });

  $(".sortable").sortable();

  $(".color_basic").spectrum({
    color: "#f00",
    preferredFormat: "hex",
    showInput: true
  });

  function max_length(selector) {
    selector.each(function() {
      var e = $(this);
      var maxlength = parseInt(e.attr('maxlength'));
      var e_target = $('<div class="limit_info"></div>');

      //delete holder element if exists
      e.parent().find('.limit_info').remove();
      //create holder element after this
      e.after(e_target);

      var current = $(e).val().length;
      e_target.html(current + ' de ' + maxlength);

      $(e).textareaCount({
        'maxCharacterSize': maxlength,
        'originalStyle': '',
        'warningStyle': '',
        'displayFormat': ''
      }, function(data) {
        var txt = data.input + ' de ' + data.max;
        e_target.html(txt);
      });
    });

  }

  max_length($('.limit_text'));

  $('.youtube_link').bind('change keyup keypress', function() {
    $(this).val(getIdFromYoutube($(this).val()));
  });

  $('.vimeo_link').bind('change keyup keypress', function() {
    var result = getIdFromVimeo($(this).val());
    if (result) {
      $(this).val(result);
    }
  });

  //_# SISTEMA DE ADD/DEL
  $('.duplication_container .add').click(function() {
    var last_duplication = $(this).parents('.duplication_container').find('.duplicate_part').last();
    var clone = last_duplication.clone();
    last_duplication.after(clone);
    max_length(clone.find('.limit_text'));

    $('.duplication_container .del').show();
  });

  $('.duplication_container .del').click(function() {
    var duplications = $(this).parents('.duplication_container').find('.duplicate_part');
    var last_duplication = duplications.last();
    last_duplication.remove();

    if (duplications.length == 2) {
      $(this).hide();
    }
  });

  $('.jumpField').on('keyup', function() {
    var $this = $(this);
    if ($this.val().length == $this.attr('maxlength')) {
      $("input[tabindex='" + (parseInt($this.attr('tabindex')) + 1) + "']").focus();
    }
  });

  $('.formPostCode').postcode({
    hideSubmit: true
  });

});

function getIdFromYoutube(text) {
  return text.replace(/(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=))([\w\-]{10,12})\b[?=&\w]*(?!['"][^<>]*>|<\/a>)/ig, '$1');
}

function getIdFromVimeo(url)
{
  var regExp = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
  var parseUrl = regExp.exec(url);
  if (parseUrl != null)
    return parseUrl[5];
}