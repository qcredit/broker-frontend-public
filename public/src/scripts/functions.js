$(document).ready(function(){
  populateOptions($('.landing-form-amount'), 'PLN');
  populateOptions($('.landing-form-duration'), 'M');
  checkSize();
  $(window).resize(checkSize);
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
    var $loanAmountSlider = $('.landing-component-lg #loanAmount');
    var $loanAmount = $('.landing-component-lg #loanAmountOutput');
    var $loanDurationSlider = $('.landing-component-lg #loanTerm');
    var $loanDuration = $('.landing-component-lg #loanDurationOutput');
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
  }); // Loan landing form functions for laptops and desktops end here

  // Loan landing form functions for mobile devices and tablets
  $(".landing-component-sm .landing-form-adjust-increase").click(function() {
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
  $(".landing-component-sm .landing-form-adjust-decrease").click(function() {
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
  $('.landing-component-sm select').change(function() {
    var $selectedOpt = $(this).find('option:selected');
    $(this).parent().find('.landing-form-adjust').removeClass('disabled');
    $(this).children('option').removeProp('selected');
    $selectedOpt.prop('selected', true);
    calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
  });
  // To get values of selected options
  //$('.landing-component-sm #loanAmountOutput').val();
  //$('.landing-component-sm #loanDurationOutput').val();
  $('.loan-offer-container:first-child').addClass('featured');
});

// Calculate approximate monthly installment
function calculateMonthly(amount,months) {
  var monthlyInstallment = Math.round((amount/months)*1.3); // Monthly fee calculation logic here
  $('.monthly-installment').text('PLN '+monthlyInstallment);
}

// Check the size of the screen to determine which slider is shown.
function checkSize() {
    if ($(".landing-component-sm").css("display") == "block" && $(".landing-component-lg").css("display") == "none"){
        // your code here
        $('.landing-component-lg .landing-form-amount input').removeAttr('id');
        $('.landing-component-lg .landing-form-duration input').removeAttr('id');
        $('.landing-component-sm .landing-form-amount select').attr('id','loanAmount');
        $('.landing-component-sm .landing-form-duration select').attr('id','loanTerm');

    } else if ($(".landing-component-lg").css("display") == "block" && $(".landing-component-sm").css("display") == "none") {
      $('.landing-component-sm .landing-form-duration select').removeAttr('id');
      $('.landing-component-sm .landing-form-amount select').removeAttr('id');
      $('.landing-component-lg .landing-form-amount input').attr('id','loanAmount');
      $('.landing-component-lg .landing-form-duration input').attr('id','loanTerm');
    }
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
