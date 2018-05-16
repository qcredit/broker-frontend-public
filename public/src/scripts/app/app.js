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

  return app;
});