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

  $(".cepMask").mask("99999-999");
  $(".cpfMask").mask("999.999.999-99");
  $(".cnpjMask").mask("99.999.999/9999-99");
  $(".phoneMask").mask("(99) ?999999999");
  $(".dateMask:visible").mask("99/99/9999");
  $(".timeMask:visible").mask("99:99");
  $(".timeFullMask:visible").mask("99:99:99");

  $(".datepicker").datepicker({
    dateFormat: 'dd/mm/yy',
    showOn: 'focus'
  });

  $('.daterange').daterangepicker({
    opens: 'right',
    format: 'dd/MM/yyyy'
  });

  $(".sortable").sortable();

  $(".multisorter").multiselect({});

  $(".color_basic").spectrum({
    color: "#f00",
    preferredFormat: "hex",
    showInput: true
  });

  $('.limit_text').each(function() {
    var e = $(this);
    var maxlength = parseInt(e.attr('maxlength'));
    var e_target = e.next();

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

  $('.youtube_link').bind('change keyup keypress', function() {
    $(this).val(getIdFromYoutube($(this).val()));
  });

  $('.vimeo_link').bind('change keyup keypress', function() {
    var result = getIdFromVimeo($(this).val());
    if (result) {
      $(this).val(result);
    }
  });

  $('.ajaxli').each(function() {
    var search = $(this).next('.ui-multiselect').find('.search');
    search.unbind();
    search.bind('keyup', ajaxli);
  });

  $('.tokenInput').each(function() {
    var url = '/admin/' + $(this).attr('id') + '/' + $(this).attr('name') + '/';
    tokenInputAjax(url);
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

function tokenInputAjax(url) {
  $(".tokenInput").tokenInput(url, {
    method: 'POST',
    minChars: 3
  });
}

function ajaxli() {

  var str = $(this).val();
  var post = {};

  var select = $(this).parents('.ui-multiselect').prev('select');
  var avaliable = $(this).parents('.ui-multiselect').children('.available').find('.connected-list');

  post['term'] = str;

  var url = $('#link_prefix').val();

  $.ajax({
    type: "POST",
    url: url + select.attr('id') + '/',
    data: post,
    success: ajaxliCallback,
    dataType: 'json',
    select: select,
    avaliable: avaliable
  });
}

function ajaxliCallback(data) {

  var select = this.select;
  var avaliable = this.avaliable;

  avaliable.html('');
  select.children('option').not(':selected').remove();

  $.each(data, function(i, o) {
    if (select.children('option[value="' + o.id + '"]').length == 0) {
      select.append('<option value=' + o.id + '>' + o.label + '</option>');
    }
  });

  select.multiselect('reload');
  avaliable.children('li').show();

}