(function($) {
  
  //set the default event handlers
  function fn_error(){}
  function fn_before(){}
  function fn_after(){}
  
  var _url;

  var _options  = {
    usePushState: 'safe', //safe, always, never
    recursiveEvent: 'true', //true, false
    form: '',
    before: fn_before,
    error: fn_error,
    after: fn_after
  };
  
  var _container_selector = '';
  var _link_selector = '';
  var _states = new Object();
  var _initialURL = _getCurrentUrl();
  var _form_selector = '';
  
  // the jQuery function
  $.fn.alljax = function(container, options){
    // export container element selector
    _container_selector = container;
    
    // export link element selector
    _link_selector = $(this).selector;
    
    // bind click event
    _bindClickEvent();
    
    if (options.form){
      _form_selector = options.form;
      _bindSubmitEvent();
    }
    
    // export options overriding defaults
    $.each(_options,function(i,o){
      if (options[i])
        _options[i] = options[i];
    });
    
    if (_isHtml5()){
      // initialize popstate event listener
      _popStateListener();
    }
  }
  
  // define a var for the submit event so we can play of bind/unbind with no worries
  var alljax_form = function(){
    var action = $(this).attr('action') ? $(this).attr('action') : window.location.href ;
    
    var arrPost = null;
    var arrPost = {};
    $(this).find('input, select, textarea').each(function(){
      if ($(this).attr('name')){
        var name = $(this).attr('name');
        if ($(this).is('input[type="checkbox"]:checked'))
        {
          if (name.indexOf('[]') > -1){
            if ($.isArray(arrPost[name])){
              arrPost[name].push($(this).val());
            }else {
              arrPost[name] = new Array($(this).val());
            }
          }else{
            arrPost[name] = 'on';
          }
        }
        else if ($(this).is(':text, :password, :hidden, select, textarea'))
        {
          if (name.indexOf('[]') > -1){
            if ($(this).is('select[multiple="multiple"]')){
              if ($(this).val())
                arrPost[name] = $(this).val();
              
            }else{
              if ($.isArray(arrPost[name])){
                arrPost[name].push($(this).val());
              }else {
                arrPost[name] = new Array($(this).val());
              }
            }
          }else{
            arrPost[name] = $(this).val();
          }
        }
      }
    });
    
    _alljax(action, arrPost);
    return false;
  };
  
  // create a function to auto unbind/bind the form submit
  function _bindSubmitEvent()
  {
    $( document ).delegate( _form_selector, 'submit', alljax_form);    
  }
  
  // define a var for the click event so we can play of bind/unbind with no worries
  var alljax_click = function(){
    // get the uri from the href attribute of the element
    var href = $(this).attr('href');
    // call the main function
    _alljax(href);
    return false;
  };
  
  // create a function to auto unbind/bind the click event so we can call it later
  function _bindClickEvent()
  {
    $(_link_selector).unbind('click', alljax_click);
    $(_link_selector).bind('click', alljax_click);
  }
  
  // the main function
  function _alljax(uri, post)
  {
    _callEvent('before');
    
    //var url = _call_ajax(uri, post);
    _url = _call_ajax(uri, post);
    
  }
  
  // all the plugin events is called through this function
  function _callEvent(eventname, data)
  {
    // if recursiveEvent is true, rebind the click event
    if (eventname == 'after'){
      if(_options.recursiveEvent == 'true')
        _bindClickEvent();
      
      if (data.redirect){
        _alljax(data.redirect);
        return;
      }
    }
      
    switch (eventname){
      case 'before':
        _options.before();
        break;
      case 'after':
        _options.after(data);
        if (_isHtml5())
          _pushState(_url);

        break;
      case 'error':
        _options.error(data);
        break;
      default:
        console.log('Wrong event name');
    }
  }
  
  // creates a ajax get or post
  function _call_ajax(url, post)
  {
    //console.log('ALLJAX!');
    var data = {};
    var type = "GET";
    
    if (post){
      type = $(_form_selector).attr('method');
      data = post;
    }
    
    $.ajax(url,{
      type: type,
      data: data,
      dataType: 'json',
      beforeSend: function(jqXHR, settings){
        // we want to send a custom request heder to identify our server script
        // what kind of result we want.
        jqXHR.setRequestHeader('ALLJAX', 'true')
        if (type.toLowerCase() == 'get'){
          url = this.url;
        }
      },
      complete: function(jqXHR, textStatus){
        // if we don't receive a json, let's just take off our ass of the aim
        if (textStatus == 'parsererror'){
          location.href = _url;
        }
      }
    }).done(_parsedata);
    
    // export the url so we can use it after
    _url = url;
    
    return url;
  }
  
  // parses the ajax data triggering the events chronologically
  function _parsedata(data)
  {
    if (data.error){
      _callEvent('error', data);
      return;
    }
    
    if (data.content){
      $(_container_selector).html(data.content);
    }
    
    _callEvent('after', data);
  }
  
  // check if browser support html5 and use with usePushState option
  function _isHtml5()
  {
    return _options.usePushState == 'always' || 
    (window.history && window.history.pushState &&
      window.history.replaceState && _options.usePushState != 'never');
  }
  
  // cache the url
  function _setState(url)
  {
    _states[url] = url;
  }
  
  // restaure cached url
  function _getState(url)
  {
    return _states[url];
  }
  
  // get the complete url given a uri
  function _getUrlFromUri(uri)
  {
    var url = document.createElement('a');
    url.href = uri;
    url = url.href;
    
    return url;
  }
  
  // get the current url
  function _getCurrentUrl()
  {
    return document.location.href;
  }
  
  // implements pushtate event listener and interpreter
  function _popStateListener()
  {
    window.onpopstate = function(event) {
      var state = event.state;
      
      if (state == null){
        var uri = _getCurrentUrl();
        _setState(uri);
      
        window.history.replaceState(_states,document.title,uri);
        state = _states;
      }
      
      // if we have this url cached, alljax it!
      if (state[_getCurrentUrl()]){
        _alljax(_getCurrentUrl());
        
      // else we're not caring about it, so throw the user there
      }else{
        window.location.href = _getCurrentUrl();
      }
    }
  }
  
  // save the url and pushes it to the history
  function _pushState(uri)
  {
    uri = _getUrlFromUri(uri);
    // we will only pushState if the url is different from the current
    // otherwise, we're talking about normal requests and we don't care about 'em
    if (_getUrlFromUri(uri) != _getCurrentUrl()){
      _setState(uri);
      _setState(_getCurrentUrl());
      
      history.pushState(_states,document.title,uri);
    }
  }

})(jQuery);