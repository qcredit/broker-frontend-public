define(['jquery', 'jquery.bootstrap', 'lib/formData.polyfill', 'lib/weakmap.polyfill'], function($, bootstrap) {
  var app = {
    csrf: {
      keys: {
        name: null,
        value: null
      },
      values: {
        name: '',
        value: ''
      }
    },
    language: 'en_EN',
    messages: []
  };

  app.setCsrfName = function(keyName, value)
  {
    this.csrf.keys.name = keyName;
    this.csrf.values.name = value;
  };

  app.setCsrfValue = function(valueName, value)
  {
    this.csrf.keys.value = valueName;
    this.csrf.values.value = value;
  };

  app.getCsrfFields = function()
  {
    return '<input type="hidden" name="' + this.csrf.keys.name + '" value="' + this.csrf.values.name + '">' +
      '<input type="hidden" name="' + this.csrf.keys.value + '" value="' + this.csrf.values.value + '">';
  };

  app.getLanguage = function()
  {
    return this.language;
  };

  app.setLanguage = function(language)
  {
    this.language = language;
  };

  app.getMessages = function()
  {
    return this.messages;
  };

  app.setMessages = function(messages)
  {
    this.messages = messages;
  };

  app.getFormData = function() {
    var formData = new FormData(document.querySelector('form'));
    var formValues = {};
    var formDataEntries = formData.entries(), formDataEntry = formDataEntries.next(), pair;
    while (!formDataEntry.done) {
      pair = formDataEntry.value;
      formValues[pair[0]] = pair[1];
      formDataEntry = formDataEntries.next();
    }
    return formValues;
  };
  // Input field focused and filled classes
  $(".field input, .field textarea").focusin(function() {
    $(this).parent().addClass("focused");
  });
  $(".field input, .field textarea").focusout(function() {
    $(this).parent().removeClass("focused");
  });
  $(".field input, .field textarea, .field select").on("input change", function() {
    if($(this).val()){
      $(this).parent().addClass("filled");
    } else {
      $(this).parent().removeClass("filled");
    }
  });
  $('.contact-form .field input, .contact-form .field textarea').each(function(){
    if($(this).val()){
      $(this).parent().addClass("filled");
    } else {
      $(this).parent().removeClass("filled");
    }
  });
  return app;
});
