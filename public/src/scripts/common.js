requirejs.config({
  'baseUrl': '/src/scripts',
  'paths': {
    'jquery': '//code.jquery.com/jquery-3.3.1.min',
    'jquery.bootstrap': '//stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min',
    'rangeSlider': 'rangeslider.min',
    'ajv': '//cdnjs.cloudflare.com/ajax/libs/ajv/6.4.0/ajv.min',
    'ajv.broker': 'app/ajv.broker',
    'broker': './app/app',
    'domReady': './app/domReady'
  },
  'shim': {
    'jquery.bootstrap': ['jquery']
  }
});

String.prototype.formatUnicorn = String.prototype.formatUnicorn ||
  function () {
    "use strict";
    var str = this.toString();
    if (arguments.length) {
      var t = typeof arguments[0];
      var key;
      var args = ("string" === t || "number" === t) ?
        Array.prototype.slice.call(arguments)
        : arguments[0];

      for (key in args) {
        str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
      }
    }

    return str;
  };