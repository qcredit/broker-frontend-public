'use strict';
function localize_en(errors) {
  if (!(errors && errors.length)) return;
  for (var i = 0; i < errors.length; i++) {
    var e = errors[i];
    var out;
    switch (e.keyword) {
      case '$ref':
        out = 'Can\\\'t resolve reference ' + (e.params.ref);
        break;
      case 'additionalItems':
        out = '';
        var n = e.params.limit;
        out += 'Should not have more than ' + (n) + ' item';
        if (n != 1) {
          out += 's';
        }
        break;
      case 'additionalProperties':
        out = 'Should not have additional properties';
        break;
      case 'anyOf':
        out = 'Should match some schema in "anyOf"';
        break;
      case 'const':
        out = 'Should be equal to constant';
        break;
      case 'contains':
        out = 'Should contain a valid item';
        break;
      case 'custom':
        //out = 'Should pass "' + (e.keyword) + '" keyword validation';
        out = 'Invalid format provided';
        break;
      case 'dependencies':
        out = '';
        var n = e.params.depsCount;
        out += 'Should have propert';
        if (n == 1) {
          out += 'y';
        } else {
          out += 'ies';
        }
        out += ' ' + (e.params.deps) + ' when property ' + (e.params.property) + ' is present';
        break;
      case 'enum':
        out = 'Invalid value provided';
        break;
      case 'exclusiveMaximum':
        out = '';
        var cond = e.params.comparison + " " + e.params.limit;
        out += 'should be ' + (cond);
        break;
      case 'exclusiveMinimum':
        out = '';
        var cond = e.params.comparison + " " + e.params.limit;
        out += 'should be ' + (cond);
        break;
      case 'false schema':
        out = 'Boolean schema is false';
        break;
      case 'format':
        out = 'Should match format "' + (e.params.format) + '"';
        break;
      case 'formatExclusiveMaximum':
        out = 'formatExclusiveMaximum should be boolean';
        break;
      case 'formatExclusiveMinimum':
        out = 'formatExclusiveMinimum should be boolean';
        break;
      case 'formatMaximum':
        out = '';
        var cond = e.params.comparison + " " + e.params.limit;
        out += 'should be ' + (cond);
        break;
      case 'formatMinimum':
        out = '';
        var cond = e.params.comparison + " " + e.params.limit;
        out += 'should be ' + (cond);
        break;
      case 'if':
        out = 'should match "' + (e.params.failingKeyword) + '" schema';
        break;
      case 'maximum':
        out = '';
        var cond = e.params.comparison + " " + e.params.limit;
        out += 'Should be ' + (cond);
        break;
      case 'maxItems':
        out = '';
        var n = e.params.limit;
        out += 'Should not have more than ' + (n) + ' item';
        if (n != 1) {
          out += 's';
        }
        break;
      case 'maxLength':
        out = '';
        var n = e.params.limit;
        out += 'Must not exceed ' + (n) + ' characters';
        if (n != 1) {
          out += 's';
        }
        break;
      case 'maxProperties':
        out = '';
        var n = e.params.limit;
        out += 'Should not have more than ' + (n) + ' propert';
        if (n == 1) {
          out += 'y';
        } else {
          out += 'ies';
        }
        break;
      case 'minimum':
        out = '';
        var cond = e.params.comparison + " " + e.params.limit;
        out += 'Should be ' + (cond);
        break;
      case 'minItems':
        out = '';
        var n = e.params.limit;
        out += 'Should not have less than ' + (n) + ' item';
        if (n != 1) {
          out += 's';
        }
        break;
      case 'minLength':
        out = '';
        var n = e.params.limit;
        out += 'Should not be shorter than ' + (n) + ' character';
        if (n != 1) {
          out += 's';
        }
        break;
      case 'minProperties':
        out = '';
        var n = e.params.limit;
        out += 'Should not have less than ' + (n) + ' propert';
        if (n == 1) {
          out += 'y';
        } else {
          out += 'ies';
        }
        break;
      case 'multipleOf':
        out = 'Should be a multiple of ' + (e.params.multipleOf);
        break;
      case 'not':
        out = 'Should not be valid according to schema in "not"';
        break;
      case 'oneOf':
        out = 'Should match exactly one schema in "oneOf"';
        break;
      case 'pattern':
        //out = 'Should match pattern "' + (e.params.pattern) + '"';
        out = 'Invalid format';
        break;
      case 'patternRequired':
        out = 'Should have property matching pattern "' + (e.params.missingPattern) + '"';
        break;
      case 'propertyNames':
        out = 'Property name \'' + (e.params.propertyName) + '\' is invalid';
        break;
      case 'required':
        out = 'Should have required property ' + (e.params.missingProperty);
        break;
      case 'switch':
        out = 'Should pass "switch" keyword validation, case ' + (e.params.caseIndex) + ' fails';
        break;
      case 'type':
        out = 'Should be ' + (e.params.type);
        break;
      case 'uniqueItems':
        out = 'Should not have duplicate items (items ## ' + (e.params.j) + ' and ' + (e.params.i) + ' are identical)';
        break;
      default:
        continue;
    }
    e.message = out;
  }
}

function validatePolandDocument(number) {
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

function searchError(attribute, errors){
  for (var i=0; i < errors.length; i++) {
    if (errors[i].dataPath === '.' + attribute) {
      return errors[i];
    }
  }
}