$(document).ready(function() {

  $('.clean_search').click(function() {
    $('.form_search input[type="text"]').each(function() {
      $(this).val('');
    });
    $('.form_search select').each(function() {
      $(this).val('0');
      $(this).parent().find('.select2-chosen').html($(this).children('option').eq(0).text());
    });
  });

  $("#delete_all").click(function() {
    if ($("#delete_all").is(':checked')) {
      $('.delete').prop("checked", true);
    } else {
      $('.delete').prop("checked", false);
    }
  });

  $('a.delete_shortcut').click(function() {
    $('.delete').prop("checked", false);
    $(this).parents('tr').find('.delete').prop("checked", true);
    $('.trash_ex').click();
  });

});