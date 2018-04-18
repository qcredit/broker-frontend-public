$(document).ready(function(){
  // Loan landing form functions for laptops and desktops start here
  $(function() {
    var $loanAmountSlider = $('.landing-component-lg #loanAmount');
    var $loanAmount = $('.landing-component-lg #loanAmountOutput');
    var $loanDurationSlider = $('.landing-component-lg #loanDurationSlider');
    var $loanDuration = $('.landing-component-lg #loanDurationOutput');

    $loanAmountSlider.rangeslider({
        polyfill: false
      }).on('input', function() {
          $loanAmount.val(this.value);
      });
    $loanAmount.on('change', function() {
      $loanAmountSlider.val(this.value).change();
    });

    $loanDurationSlider.rangeslider({
        polyfill: false
      }).on('input', function() {
          $loanDuration.val(this.value);
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
  });
  $('.landing-component-sm select').change(function() {
    var $selectedOpt = $(this).find('option:selected');
    $(this).parent().find('.landing-form-adjust').removeClass('disabled');
    $(this).children('option').removeProp('selected');
    $selectedOpt.prop('selected', true);
  });
  // To get values of selected options
  //$('.landing-component-sm #loanAmountOutput').val();
  //$('.landing-component-sm #loanDurationOutput').val();


  // Temporary input field focused and filled classes
  $(".field input").focusin(function() {
    $(this).parent().addClass("focused");
  });
  $(".field input").focusout(function() {
    $(this).parent().removeClass("focused");
  });
  $(".field input").on("input", function() {
    if($(this).val()){
      $(this).parent().addClass("filled");
    } else {
      $(this).parent().removeClass("filled");
    }
  });
  $('.loan-offer-container:first-child').addClass('featured');
});
