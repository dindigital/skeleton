(function($) {

  $.fn.selectajax = function(options) {

    var settings = $.extend({
      loading: true,
      defaltValue: '0',
      before: function() {
        console.log('oi');
      },
      onComplete: function(data) {
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

      if (id == settings.defaltValue) {
        response.html('');
        return false;
      }

      $.ajax({
        url: uri,
        type: 'get',
        dataType: 'json',
        data: {
          id: id
        },
        beforeSend: function() {
          settings.before(this);
          showLoading(response);
        },
        success: function(data) {
          var jqueryData = $(data);
          response.html(jqueryData);
          settings.onComplete(jqueryData);
        },
        error: function() {
          settings.onError(this);
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