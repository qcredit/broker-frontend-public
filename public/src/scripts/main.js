require(['jquery.bootstrap', 'app/domReady', 'app/formHelper'], function (bs, domReady, formHelper)
{
  formHelper.populateOptions($('.landing-form-amount'), 'PLN');
  formHelper.populateOptions($('.landing-form-duration'), 'M');

  formHelper.calculateMonthly($('#loanAmount').val(), $('#loanTerm').val());
});