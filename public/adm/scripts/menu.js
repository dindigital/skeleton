function sadmMenu()
{
  if($.fn.accordion){
    $( "#nav_side" ).accordion({
      changestart: function(event, ui) {
        setTimeout(function() {
          positionMenu();
        }, 500);
      },
      collapsible: true,
      active:true,
      header: 'a.drop', // this is the element that will be clicked to activate the accordion
      autoHeight:false,
      event: 'mousedown',
      icons:false,
      animated: true
    });
  }  
  positionMenu();
}

function positionMenu() {
  if (($("#sidebar").height() + 30) >= $("body").height()) {
    $("#sidebar").css('position','absolute');
  } else {
    $("#sidebar").css('position','fixed');
  }
}