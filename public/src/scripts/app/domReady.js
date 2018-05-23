define(['jquery', 'rangeSlider', 'app/formHelper'], function($, rangeSlider, formHelper) {
  $(function() {
    formHelper.initialize();
    /* URL check to add active class to correct navlink */
    var current = location.pathname;
    $('.navbar-nav .nav-item a').each(function(){
      var $this = $(this);
      // if the current path is like this link, make it active
      if($this.attr('href').indexOf(current) !== -1 && location.pathname != "/"){
        $this.parent().addClass('active');
      } else if (location.pathname == "/") {
        $('.home-link').addClass('active');
      }
    });

    // Loan landing form functions for laptops and desktops start here
    var $loanAmountSlider = $('.landing-component #loanAmountSlider');
    var $loanAmount = $('.landing-component #loanAmount');
    var $loanDurationSlider = $('.landing-component #loanTermSlider');
    var $loanDuration = $('.landing-component #loanTerm');
    $loanAmountSlider.rangeslider({
      polyfill: false
    }).on('input', function() {
      $loanAmount.val(this.value);
      formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
    });
    $loanAmount.on('change', function() {
      $loanAmountSlider.val(this.value).change();
    });
    $loanDurationSlider.rangeslider({
      polyfill: false
    }).on('input', function() {
      $loanDuration.val(this.value);
      formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
    });
    $loanDuration.on('change', function() {
      $loanDurationSlider.val(this.value).change();
    });
    $('.landing-component').css('opacity', 1);
    $('.landing-form-upper .bouncing-loader, .loan-form-upper .bouncing-loader').css('opacity', 0);
  });

});
