define('app/optionsPopulator', ['jquery'], function($) {
  var populateOptions = function(slider, unit)
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

  return {
    populate: populateOptions
  };
});