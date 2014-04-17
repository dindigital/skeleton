(function($) {

  $.fn.selectajax = function(options) {

    var settings = $.extend({
      loading: true,
      before: function() {
      },
      onComplete: function() {
      },
      onError: function() {
      }
    }, options);

    this.each(function(index, container) {

      $(container).find('select').change(function(e) {
        e.preventDefault();
        selectAjax($(container));
      });

    });

    function selectAjax(container) {

      var select = container.find('select');
      var id = select.val();
      //
      var uri = container.attr('data-uri');
      var response = $(container.attr('data-response'));
      //
      if (uri === undefined || response === undefined)
        return false;

      $.ajax({
        url: uri,
        type: 'get',
        dataType: 'json',
        data: {
          id: id
        },
        beforeSend: function() {
          settings.before.call(this);
          showLoading(response);
        },
        success: function(data) {
          response.html(data);
          settings.onComplete.call(this);
        },
        error: function() {
          settings.onError.call(this);
        }
      }
      );
    }

    function showLoading(response) {
      if (settings.loading === true) {
        response.html('<span class="selectajax-loading"></span>');
      }
    }

  };
}(jQuery));