$(document).ready(function(){
  $(function() {
    var $loanAmountSlider = $('#loanAmount');
    var $loanAmount = $('#loanAmountOutput');
    var $loanDurationSlider = $('#loanDurationSlider');
    var $loanDuration = $('#loanDurationOutput');

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
  });


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
});
