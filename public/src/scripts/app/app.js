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
  $('.contact-form .field input, .contact-form .field textarea, .landing-form .field input').each(function(){
    if($(this).val()){
      $(this).parent().addClass("filled");
    } else {
      $(this).parent().removeClass("filled");
    }
  });
  $('.landing-form #email').on('input change', function() {
    var val = $(this).val();
    if(val){
      var validated = validateEmail(val);
      if(!validated){
        $('.landing-form .btn').prop('disabled', true);
        $('.landing-form .field.email .rules').css('display', 'block');
      } else {
        $('.landing-form .btn').prop('disabled', false);
        $('.landing-form .field.email .rules').css('display', 'none');
      }
    } else {
      $('.landing-form .btn').prop('disabled', false);
      $('.landing-form .field.email .rules').css('display', 'none');
    }
  });
  function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }
  $('.contact-form').on('submit', function() {
    $(this).find('button').attr('disabled', 'disabled');
  });
  $(".landingCheck input").change(function(){
    if ($('.landingCheck input:checked').length == $('.landingCheck input').length) {
      $('.landingCheck #sendSubmit').removeClass('broker-btn-disabled');
    } else {
      $('.landingCheck #sendSubmit').addClass('broker-btn-disabled');
    }
  });
  $('.landing-inputs .phone input').focusin(function() {
    if($(this).val() === '' || $(this).val() === '+48') {
      $(this).val('+48');
    }
  });
  $('.landing-inputs .phone input').focusout(function() {
    if($(this).val() === '+48'){
      $(this).val('');
      $(this).parent().removeClass("filled");
    }
  });
  return app;
});
