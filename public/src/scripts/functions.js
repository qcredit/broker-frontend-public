$(document).ready(function(){
  checkSize();
  $(window).resize(checkSize);

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
});

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
