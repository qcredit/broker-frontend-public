$(document).ready(function(){
  populateOptions($('.landing-form-amount'), 'PLN');  // Call function to populate loanAmount slider options dynamically. Second var is for units
  populateOptions($('.landing-form-duration'), 'M');  // Call function to populate loanTerm slider options dynamically. Second var is for units
  calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());

  /* URL check to add active class to correct navlink */
  $(function(){
    var current = location.pathname;
    $('.navbar-nav .nav-item a').each(function(){
      var $this = $(this);
      // if the current path is like this link, make it active
      if($this.attr('href').indexOf(current) !== -1 && location.pathname != "/"){
          $this.parent().addClass('active');
      } else if (location.pathname == "/") {
        $('.home-link').addClass('active');
      }
    })
  });
 function populateOptions(slider, unit) {
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
 }
  // Loan landing form functions for laptops and desktops start here
  $(function() {
    var $loanAmountSlider = $('.landing-component #loanAmountSlider');
    var $loanAmount = $('.landing-component #loanAmount');
    var $loanDurationSlider = $('.landing-component #loanTermSlider');
    var $loanDuration = $('.landing-component #loanTerm');
    $loanAmountSlider.rangeslider({
        polyfill: false
      }).on('input', function() {
          $loanAmount.val(this.value);
          calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
      });
    $loanAmount.on('change', function() {
      $loanAmountSlider.val(this.value).change();
    });
    $loanDurationSlider.rangeslider({
        polyfill: false
      }).on('input', function() {
          $loanDuration.val(this.value);
          calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
      });
    $loanDuration.on('change', function() {
      $loanDurationSlider.val(this.value).change();
    });
    $('.landing-component').css('opacity', 1);
    $('.landing-form-upper .bouncing-loader, .loan-form-upper .bouncing-loader').css('opacity', 0);
  }); // Loan landing form functions for laptops and desktops end here

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
    calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
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
    calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
  });
  $('.landing-component select').change(function() {
    var $selectedOpt = $(this).find('option:selected');
    $(this).parent().find('.landing-form-adjust').removeClass('disabled');
    $(this).children('option').removeProp('selected');
    $selectedOpt.prop('selected', true);
    calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
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
});

// Calculate approximate monthly installment
function calculateMonthly(amount,months) {
  var monthlyInstallment1 = calculateInstallment(amount, months, 1.3); // Monthly fee calculation logic here
  var monthlyInstallment2 = calculateInstallment(amount, months, 1.5); // Monthly fee calculation logic here
  $('.monthly-installment').text(monthlyInstallment1 +" - "+ monthlyInstallment2 +" PLN");
}
function calculateInstallment(amount, months, a) {
  return Math.round((amount/months)*a);
}
  // Input field focused and filled classes
$(".field input, .field textarea").focusin(function() {
  $(this).parent().addClass("focused");
});
$(".field input, .field textarea").focusout(function() {
  $(this).parent().removeClass("focused");
});
$(".field input, .field textarea").on("input", function() {
  if($(this).val()){
    $(this).parent().addClass("filled");
  } else {
    $(this).parent().removeClass("filled");
  }
});
