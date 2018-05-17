define('app/form', ['jquery', 'app/app', 'ajv', 'ajv.broker'], function($, app, Ajv, brokerAjv) {
  var schema = '';
  var ajv = new Ajv({ allErrors: true, verbose: true, coerceTypes: true });

  ajv.addKeyword('documentNr', {
    validate: function documentNr(schema,data)
    {
      documentNr.errors = [];
      if (!brokerAjv.validatePolandDocument(data))
      {
        var messages = app.getMessages();
        var message = 'Invalid format';
        if (messages.format !== undefined)
        {
          message = messages.format;
        }
        documentNr.errors.push({
          "keyword": "documentNr",
          "dataPath": ".documentNr",
          "params": {},
          "message": message
        });

        return false;
      }

      return true;
    },
    errors: true
  });

  $.ajax({
    'url': '/application/schema',
    'method': 'GET'
  }).done(function(response) {
    schema = response.schema;
    app.setMessages(response.messages);
  }).fail(function(response) {
    console.log('Unable to fetch schema!');
  });

  $('button[type="submit"]').click(function(e) {
    var valid = ajv.validate(schema, app.getFormData());

    if (!valid) {
      e.preventDefault();
      brokerAjv.localize(ajv.errors);
      console.log(ajv.errors);
      console.log(ajv.errorsText(ajv.errors, { separator: '\n'}));
      var error_list = ajv.errors;
      for(var i = 0; i < error_list.length; i++) {
        var err_target = $('.field'+error_list[i].dataPath);
        var err_msg = error_list[i].message;
        if(err_msg){
          if(!err_target.find('.rules').length){
            err_target.addClass('error');
            err_target.append('<p class="rules">'+err_msg+'</p>');
          } else {
            err_target.find('.rules').text(err_msg);
          }
        }
      }
    }
  });

  $('input').on('change', function(e) {
    var attrId = $(this)[0].id;
    var formValues = app.getFormData();
    var parent = $(this).parent();
    runSchemaLive(attrId, formValues, parent);
  });
  $('select').on('change', function(e) {
    var attrId = $(this)[0].id;
    var formValues = app.getFormData();
    var parent = $(this).parent();
    runSchemaLive(attrId, formValues, parent);
  });

  $('.step-1 .broker-btn').on('click', function() {
    var formValues = app.getFormData();
    var errors_list = []
    $('.step-1').find('div.field').each(function(){
      var parent = $(this);
      var attrId = parent.find('input').attr('id');
      var formValues = app.getFormData();
      return errors_list.push(runSchemaLive(attrId, formValues, parent));
    });
    if(errors_list[0] || errors_list[1] || errors_list[2] ) { // IF one of the three is validated
      // Show rest of the form & save data
      $('.step-1 .broker-btn').css('display','none');
      $('.step-2').css('display','block');
      if($('#marketingConsent').is(':checked')) {
        sendpreData(formValues);
      }
    } else if(!errors_list[0] && !errors_list[1] && !errors_list[2]) {  // IF all are empty
      // show form but don't send data yet
      $('.step-1 .broker-btn').css('display','none');
      $('.step-2').css('display','block');
    }
    console.log("error status: " + errors_list)
  });

  function runSchemaLive(attrId,formValues,parent) {
    ajv.validate(schema, formValues);
    brokerAjv.localize(ajv.errors);
    var err_obj = brokerAjv.searchError(attrId, ajv.errors);
    if(err_obj) {
      parent.addClass('error');
      if(!parent.find('.rules').length){
        parent.append('<p class="rules">'+err_obj.message+'</p>');
        console.log(err_obj);
      } else {
        parent.find('.rules').text(err_obj.message);
      }
      return false;
    } else {
      parent.removeClass('error');
      parent.find('p.rules').text('');
      return true;
    }
  }

  function sendpreData(values) {
    $.ajax({
      method: "POST",
      url: "/application",
      data: values
    })
      .done(function( data ) {
        $('input#applicationHash').val(data.applicationHash);
      });
  }
});
