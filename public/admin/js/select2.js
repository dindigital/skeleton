$(document).ready(function() {

  $('.select2-ajax').each(function() {
    var currentSection = $(this).attr('id');
    var relationshipSection = $(this).attr('name');
    select2_ajax($(this), currentSection, relationshipSection);
  });

  $('.select2-static').each(function() {
    var currentSection = $(this).attr('id');
    var relationshipSection = $(this).attr('name');
    select2_static($(this), currentSection, relationshipSection);
  });

});

function select2_ajax(element, currentSection, relationshipSection) {

  var url = '/admin/relashionship/ajax/';

  $(element).select2({
    placeholder: "Selecionar",
    minimumInputLength: 2,
    multiple: true,
    tokenSeparators: [","],
    ajax: {
      url: url,
      dataType: 'json',
      data: function(term) {
        return {
          q: term,
          currentSection: currentSection,
          relationshipSection: relationshipSection
        };
      },
      results: function(data) {
        return {results: data};
      }
    },
    initSelection: function(element, callback) {
      $.ajax(url, {
        type: 'POST',
        dataType: "json",
        data: {
          id: element.val(),
          currentSection: currentSection,
          relationshipSection: relationshipSection
        }
      }).done(function(data) {
        element.removeAttr('value');
        callback(data);
      });
    },
    createSearchChoice: function(term, data) {
      if ($(data).filter(function() {
        return this.text.localeCompare(term) === 0;
      }).length === 0) {
        return {id: term, text: term};
      }
    }
  });

  select2_drag(element, currentSection, relationshipSection, url);

}

function select2_static(element, currentSection, relationshipSection, url) {

  var url = '/admin/relashionship/ajax/';

  $.ajax({
    url: url,
    dataType: 'json',
    data: {
      q: '',
      currentSection: currentSection,
      relationshipSection: relationshipSection
    }
  }).done(function(data) {

    $(element).select2({
      placeholder: "Selecionar",
      multiple: true,
      tokenSeparators: [","],
      data: data,
      initSelection: function(element, callback) {
        $.ajax(url, {
          type: 'POST',
          dataType: "json",
          data: {
            id: element.val(),
            currentSection: currentSection,
            relationshipSection: relationshipSection
          }
        }).done(function(data) {
          element.removeAttr('value');
          callback(data);
        });
      }
    });

    select2_drag(element, currentSection, relationshipSection, url);

  });

}

function select2_drag(element, currentSection, relationshipSection, url) {

  $(element).select2("container").find("ul.select2-choices").sortable({
    containment: 'parent',
    start: function() {
      $(element).select2("onSortStart");
    },
    update: function() {
      $(element).select2("onSortEnd");
    }
  });

  $(element).next().find('.select2-btn-clear').click(function() {
    $(element).select2("val", "");
  });

  $(element).next().find('.select2-btn-all').click(function() {
    $.ajax({
      url: url,
      dataType: 'json',
      data: {
        q: '',
        currentSection: currentSection,
        relationshipSection: relationshipSection
      }
    }).done(function(data) {
      $(element).select2('data', data);
    });
  });

}