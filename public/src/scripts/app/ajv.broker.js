define('ajv.broker', ['ajv', 'gettext', 'app/app'], function (Ajv, gettext, app) {
  var ajv = {};
  var i18n = gettext();

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

  function english(errors)
  {
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
          out = i18n.gettext('Should not be shorter than %1 characters', n);
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

  function polish(errors)
  {
    for (var i = 0; i < errors.length; i++) {
      var e = errors[i];
      var out;
      switch (e.keyword) {
        case '$ref':
          out = 'nie można znaleść schematu ' + (e.params.ref);
          break;
        case 'additionalItems':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien mieć więcej niż ' + (n) + ' element';
          if (n == 1) {
            out += 'u';
          } else {
            out += 'ów';
          }
          break;
        case 'additionalProperties':
          out = 'nie powinien zawierać dodatkowych pól';
          break;
        case 'anyOf':
          out = 'powinien pasować do wzoru z sekcji "anyOf"';
          break;
        case 'const':
          out = 'powinien być równy stałej';
          break;
        case 'contains':
          out = 'should contain a valid item';
          break;
        case 'custom':
          out = 'powinien przejść walidację "' + (e.keyword) + '"';
          break;
        case 'dependencies':
          out = '';
          var n = e.params.depsCount;
          out += 'powinien zawierać pol';
          if (n == 1) {
            out += 'e';
          } else {
            out += 'a';
          }
          out += ' ' + (e.params.deps) + ' kiedy pole ' + (e.params.property) + ' jest obecne';
          break;
        case 'enum':
          out = 'powinien być równy jednej z predefiniowanych wartości';
          break;
        case 'exclusiveMaximum':
          out = '';
          var cond = e.params.comparison + " " + e.params.limit;
          out += 'powinien być ' + (cond);
          break;
        case 'exclusiveMinimum':
          out = '';
          var cond = e.params.comparison + " " + e.params.limit;
          out += 'powinien być ' + (cond);
          break;
        case 'false schema':
          out = 'boolean schema is false';
          break;
        case 'format':
          out = 'powinien zgadzać się z formatem "' + (e.params.format) + '"';
          break;
        case 'formatExclusiveMaximum':
          out = 'formatExclusiveMaximum powinien być boolean';
          break;
        case 'formatExclusiveMinimum':
          out = 'formatExclusiveMinimum powinień być boolean';
          break;
        case 'formatMaximum':
          out = '';
          var cond = e.params.comparison + " " + e.params.limit;
          out += 'powinien być ' + (cond);
          break;
        case 'formatMinimum':
          out = '';
          var cond = e.params.comparison + " " + e.params.limit;
          out += 'powinien być ' + (cond);
          break;
        case 'if':
          out = 'should match "' + (e.params.failingKeyword) + '" schema';
          break;
        case 'maximum':
          out = '';
          var cond = e.params.comparison + " " + e.params.limit;
          out += 'powinien być ' + (cond);
          break;
        case 'maxItems':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien mieć więcej niż ' + (n) + ' element';
          if (n == 1) {
            out += 'u';
          } else {
            out += 'ów';
          }
          break;
        case 'maxLength':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien być dłuższy niż ' + (n) + ' znak';
          if (n != 1) {
            out += 'ów';
          }
          break;
        case 'maxProperties':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien zawierać więcej niż ' + (n) + ' ';
          if (n == 1) {
            out += 'pole';
          } else {
            out += 'pól';
          }
          break;
        case 'minimum':
          out = '';
          var cond = e.params.comparison + " " + e.params.limit;
          out += 'powinien być ' + (cond);
          break;
        case 'minItems':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien mieć mniej niż ' + (n) + ' element';
          if (n == 1) {
            out += 'u';
          } else {
            out += 'ów';
          }
          break;
        case 'minLength':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien być krótszy niż ' + (n) + ' znak';
          if (n != 1) {
            out += 'ów';
          }
          break;
        case 'minProperties':
          out = '';
          var n = e.params.limit;
          out += 'nie powinien zawierać mniej niż ' + (n) + ' ';
          if (n == 1) {
            out += 'pole';
          } else {
            out += 'pól';
          }
          break;
        case 'multipleOf':
          out = 'powinien być wielokrotnością ' + (e.params.multipleOf);
          break;
        case 'not':
          out = 'nie powinien pasować do wzoru z sekcji "not"';
          break;
        case 'oneOf':
          out = 'powinien pasować do jednego wzoru z sekcji "oneOf"';
          break;
        case 'pattern':
          out = 'powinien zgadzać się ze wzorem "' + (e.params.pattern) + '"';
          break;
        case 'patternRequired':
          out = 'powinien mieć pole pasujące do wzorca "' + (e.params.missingPattern) + '"';
          break;
        case 'propertyNames':
          out = 'property name \'' + (e.params.propertyName) + '\' is invalid';
          break;
        case 'required':
          out = 'powinien zawierać wymagane pole ' + (e.params.missingProperty);
          break;
        case 'switch':
          out = 'powinien przejść walidacje pola "switch", przypadek ' + (e.params.caseIndex) + ' zawiódł';
          break;
        case 'type':
          out = 'powinien być ' + (e.params.type);
          break;
        case 'uniqueItems':
          out = 'nie powinien zawierać elementów które się powtarzają (elementy ' + (e.params.j) + ' i ' + (e.params.i) + ' są identyczne)';
          break;
        default:
          continue;
      }
      e.message = out;
    }
  }

  return ajv;
});