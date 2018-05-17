define('ajv.broker', ['ajv', 'app/app'], function (Ajv, app) {
  var ajv = {};

  ajv.searchError = function(attribute, errors){
    if (!errors) return;
    for (var i=0; i < errors.length; i++) {
      if (errors[i].dataPath === '.' + attribute) {
        return errors[i];
      }
    }
  };

  ajv.localize = function(errors) {
    if (!(errors && errors.length)) return;

/*    if (app.getLanguage() == 'pl_PL')
    {
      polish(errors);
    }
    else {
      english(errors);
    }*/

    var messages = app.getMessages();
    for (var i = 0; i < errors.length; i++) {
      var e = errors[i];
      var out;
      switch (e.keyword) {
        case '$ref':
          out = messages['$ref'];
          break;
        case 'additionalItems':
          out = messages['additionalItems'].formatUnicorn({items: e.params.limit});
          break;
        case 'additionalProperties':
          out = messages['additionalProperties'];
          break;
        case 'anyOf':
          out = messages['anyOf'];
          break;
        case 'const':
          out = messages['const'];
          break;
        case 'contains':
          out = messages['contains'];
          break;
        case 'custom':
          out = messages['custom'];
          break;
        case 'dependencies':
          out = messages['dependencies'].formatUnicorn({property1: e.params.deps, property2: e.params.property});
          break;
        case 'enum':
          out = messages['enum'];
          break;
        case 'exclusiveMaximum':
          out = messages['exclusiveMaximum'].formatUnicorn({number: e.params.limit});
          break;
        case 'exclusiveMinimum':
          out = messages['exclusiveMinimum'].formatUnicorn({number: e.params.limit});
          break;
        case 'false schema':
          out = messages['false schema'];
          break;
        case 'format':
          out = messages['format'];
          break;
        case 'formatExclusiveMaximum':
          out = messages['formatExclusiveMaximum'];
          break;
        case 'formatExclusiveMinimum':
          out = messages['formatExclusiveMinimum'];
          break;
        case 'formatMaximum':
          out = messages['formatMaximum'].formatUnicorn({maximum: e.params.limit});
          break;
        case 'formatMinimum':
          out = messages['formatMinimum'].formatUnicorn({minimum: e.params.limit});
          break;
        case 'if':
          out = messages['if'].formatUnicorn({schemaName: e.params.failingKeyword});
          break;
        case 'maximum':
          out = messages['maximum'].formatUnicorn({maximum: e.params.limit});
          break;
        case 'maxItems':
          out = messages['maxItems'].formatUnicorn({number: e.params.limit});
          break;
        case 'maxLength':
          out = messages['maxLength'].formatUnicorn({number: e.params.limit});
          break;
        case 'maxProperties':
          out = messages['maxProperties'].formatUnicorn({number: e.params.limit});
          break;
        case 'minimum':
          out = messages['minimum'].formatUnicorn({minimum: e.params.limit});
          break;
        case 'minItems':
          out = messages['minItems'].formatUnicorn({number: e.params.limit});
          break;
        case 'minLength':
          out = messages['minLength'].formatUnicorn({number: e.params.limit});
          break;
        case 'minProperties':
          out = messages['minProperties'].formatUnicorn({number: e.params.limit});
          break;
        case 'multipleOf':
          out = messages['multipleOf'].formatUnicorn({number: e.params.multipleOf});
          break;
        case 'not':
          out = messages['not'];
          break;
        case 'oneOf':
          out = messages['oneOf'];
          break;
        case 'pattern':
          out = messages['pattern'];
          break;
        case 'patternRequired':
          out = messages['patternRequired'];
          break;
        case 'propertyNames':
          out = messages['propertyNames'].formatUnicorn({name: e.params.propertyName});
          break;
        case 'required':
          out = messages['required'].formatUnicorn({name: e.params.missingProperty});
          break;
        case 'switch':
          out = messages['switch'].formatUnicorn({case: e.params.caseIndex});
          break;
        case 'type':
          out = messages['type'].formatUnicorn({type: e.params.type});
          break;
        case 'uniqueItems':
          out = messages['uniqueItems'];
          break;
        default:
          continue;
      }
      e.message = out;
    }
  };

  ajv.validatePolandDocument = function(number) {
    // Check length
    if (!number || number.length !== 9) {
      return false;
    }

    number = number.toUpperCase();
    var letterValues = [
      '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
      'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
      'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    var getLetterValue = function(letter) {
      for (var j = 0, max = letterValues.length; j < max; j++) {
        if (letter === letterValues[j]) {
          return j;
        }
      }
      return -1;
    };

    //Check series
    for (var i = 0; i < 3; i++) {
      if (getLetterValue(number[i]) < 10) {
        return false;
      }
    }
    //Check number
    for (var i = 3; i < 9; i++) {
      if (getLetterValue(number[i]) < 0 || getLetterValue(number[i]) > 9) {
        return false;
      }
    }

    // Checksum
    var sum = 7 * getLetterValue(number[0]) +
      3 * getLetterValue(number[1]) +
      1 * getLetterValue(number[2]) +
      7 * getLetterValue(number[4]) +
      3 * getLetterValue(number[5]) +
      1 * getLetterValue(number[6]) +
      7 * getLetterValue(number[7]) +
      3 * getLetterValue(number[8]);

    sum %= 10;

    if (sum !== getLetterValue(number[3])) {
      return false;
    }

    return true;
  }

  return ajv;
});