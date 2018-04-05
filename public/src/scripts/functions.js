$(document).ready(function(){
  $(function() {
    var $loanAmountSlider = $('#loanAmount');
    var $loanAmount = $('#loanAmountOutput');
    var $loanDurationSlider = $('#loanDurationSlider');
    var $loanDuration = $('#loanDurationOutput');

    $loanAmountSlider.rangeslider({
        polyfill: false
      }).on('input', function() {
        if (this.value == 0) {
            $loanAmount.val("0 PLN");
        } else if (this.value == '') {
          $loanAmount.val("0 PLN");
        }
        else {
            $loanAmount.val(this.value +" PLN");
        }
      });
    $loanAmount.on('input', function() {
      if (this.value === "") {
            this.value = 0;
        }
      $loanAmountSlider.val(this.value).change();
    });

    $loanDurationSlider.rangeslider({
        polyfill: false
      }).on('input', function() {
        if (this.value == 0) {
            $loanDuration.val("0");
        } else if (this.value == '') {
          $loanDuration.val("0");
        }
        else {
            $loanDuration.val(this.value);
        }
      });
    $loanDuration.on('input', function() {
      if (this.value === "") {
            this.value = 0;
        }
      $loanDurationSlider.val(this.value).change();
    });
  });
});
