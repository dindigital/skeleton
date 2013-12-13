$(document).ready(function() {
  sadmMenu();
  initReload();
  alljaxImplement();

});

$(window).load(function() {

  hideLoadingOverlay();

});

$(window).resize(function() {
  positionMenu();
});

function initReload()
{
  sadmForms();
  sadmList();
}

function hideLoadingOverlay() {
  $("#loading_overlay .loading_message").delay(200).fadeOut(function() {
  });
  $("#loading_overlay").delay(300).fadeOut();
}

function showLoadingOverlay() {
  $("#loading_overlay .loading_message").show();
  $("#loading_overlay").show();
}

function showError(msg)
{
  $('#alert_salvo').hide();
  $('#alert_erro span').text(msg);
  $('#alert_erro').css('opacity', '1');
  $('html, body').animate({
    scrollTop: 0
  }, 'slow');
  $('#alert_erro').fadeIn(200);

}

function hideError()
{
  $('#alert_salvo').hide();
  $('#alert_erro').hide();

}

function alljaxImplement()
{
  $('.replace_container').alljax('#main_container', {
    form: '#main_form',
    before: function() {
      hideError();
      showLoadingOverlay();
    },
    error: function(data) {
      hideLoadingOverlay();
      showError(data.error);
    },
    after: function(data) {
      hideLoadingOverlay();
      if (data.redirectOut) {
        redirect_out(data.redirectOut);
      } else {
        if (data.exec) {
          eval(data.exec);
        }

        initReload();
        $('html, body').animate({
          scrollTop: 0
        }, 'fast');
      }
    }
  });
}

function redirect_out(url)
{
  location.href = url;
}