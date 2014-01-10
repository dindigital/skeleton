$(window).load(function() {

  hideLoadingOverlay();

  $('.alert-success-fade').delay(3000).fadeOut('fast', function() {
    $(this).remove();
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