define(['jquery', 'broker'], function($, app) {
  var reminder = {
    config: {
      timeout: 60
    }
  };

  reminder.initialize = function()
  {
    setTimeout(function() {
      if ($('.field.error').length)
      {
        reminder.displayReminder();
      }
    }, this.config.timeout*1000)
  };

  reminder.displayReminder = function()
  {
    $('.modal').modal('show');
  };

  reminder.initialize();

  return reminder;
});