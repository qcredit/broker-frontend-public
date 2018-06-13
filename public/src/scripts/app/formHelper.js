define(['jquery', 'broker'], function($, app) {
  var formHelper = {
    schemaLoaded: false
  };

  formHelper.populateOptions = function(slider, unit)
  {
    var inputTarget = slider.find('input');
    var min = parseInt(inputTarget.attr('min'));
    var max = parseInt(inputTarget.attr('max'));
    var step = parseInt(inputTarget.attr('step'));
    var value = parseInt(inputTarget.attr('value'));
    var target = slider.find('.range-slider-value');

    for(var i = min; i <= max; i += step) {
      if(i == value) {
        target.append($("<option></option>")
          .attr({'min': min, 'max': max, 'step': step, 'value': i, 'selected': true})
          .text(i + " " + unit));
      } else {
        target.append($("<option></option>")
          .attr({'min': min, 'max': max, 'step': step, 'value': i})
          .text(i + " " + unit));
      }
    }
  };

  formHelper.calculateMonthly = function(amount,months) {
    var monthlyInstallment1 = this.calculateInstallment(amount, months, 1.3); // Monthly fee calculation logic here
    var monthlyInstallment2 = this.calculateInstallment(amount, months, 1.5); // Monthly fee calculation logic here
    $('.monthly-installment').text(monthlyInstallment1 +" - "+ monthlyInstallment2 +" PLN");
  };

  formHelper.calculateInstallment = function(amount, months, a) {
    return Math.round((amount/months)*a);
  };

  formHelper.initialize = function() {
    if ($('form').length)
    {
      populateOptions();
      addCsrf();
    }
  };

  function addCsrf()
  {
    $('form').append(app.getCsrfFields());
    if (formHelper.schemaLoaded)
    {
      $('form .broker-btn:disabled').prop('disabled', false);
    }
  }

  function populateOptions()
  {
    formHelper.populateOptions($('.landing-form-amount'), 'PLN');
    formHelper.populateOptions($('.landing-form-duration'), 'M');

    formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
  }

  // Loan landing form functions for mobile devices and tablets
  $(".landing-component .landing-form-adjust-increase").click(function() {
    var $curOpt = $(this).parent().find('select');  // Get the correct select
    var $targetOpt = $curOpt.children('option:selected'); // Get the current selected option
    $(this).parent().find('.landing-form-adjust-decrease').removeClass('disabled'); // If decrease button is disabled, enable it
    if($curOpt.children('option:last-child').is(':selected')) { // if the last element is already selected end the function with return false
      $(this).addClass('disabled');
      return false;
    }
    $curOpt.children('option').removeProp('selected'); // Remove seleced from all the options
    $targetOpt.next().prop('selected', true); // Add selected to next element
    formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
  });
  $(".landing-component .landing-form-adjust-decrease").click(function() {
    var $curOpt = $(this).parent().find('select');
    var $targetOpt = $curOpt.children('option:selected'); // Get the current selected option
    $(this).parent().find('.landing-form-adjust-increase').removeClass('disabled');
    if($curOpt.children('option:first-child').is(':selected')) {
      $(this).addClass('disabled');
      return false;
    }
    $curOpt.children('option').removeProp('selected'); // Remove seleced from all the options
    $targetOpt.prev().prop('selected', true);
    formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
  });
  $('.landing-component select').change(function() {
    var $selectedOpt = $(this).find('option:selected');
    $(this).parent().find('.landing-form-adjust').removeClass('disabled');
    $(this).children('option').removeProp('selected');
    $selectedOpt.prop('selected', true);
    formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
  });
  $('.loan-form .broker-btn-send').on('click', function() {
    if($('.loan-form .field').hasClass('error')) {
      var first_el = $('.loan-form .field.error').first();
      $('html, body').animate({
        scrollTop: first_el.offset().top - 200
      }, 300);
      console.log($('.loan-form .field.error'));
    }
  });
  $('.loan-form input, .loan-form select').each(function() {
    if($(this).val()) {
      $(this).parent().addClass("filled");
    }  else {
      $(this).parent().removeClass("filled");
    }
  })

  return formHelper;
});
