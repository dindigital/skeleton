$(document).ready(function() {
  sadmMenu();
  sadmForms();
  sadmList()
});

$(window).load(function() {

  hideLoadingOverlay();

});

$(window).resize(function() {
  positionMenu();
});

function hideLoadingOverlay() {
  $("#loading_overlay .loading_message").delay(200).fadeOut(function() {
  });
  $("#loading_overlay").delay(300).fadeOut();
}

function showLoadingOverlay() {
  $("#loading_overlay .loading_message").show();
  $("#loading_overlay").show();
}

//function showError(msg)
//{
//  $('#alert_salvo').hide();
//  $('#alert_erro span').text(msg);
//  $('#alert_erro').css('opacity', '1');
//  $('html, body').animate({
//    scrollTop: 0
//  }, 'slow');
//  $('#alert_erro').fadeIn(200);
//
//}
//
//function hideError()
//{
//  $('#alert_salvo').hide();
//  $('#alert_erro').hide();
//
//}