$(document).ready(function() {
  // Temporary input field focused and filled classes
  $(".field input").focusin(function() {
    if(!$(this).next('.rules').length) {
      $(this).parent().append("<p class='rules'></p>");
    }
    $(this).parent().addClass("focused");
  });
  $(".field input").focusout(function() {
    $(this).parent().removeClass("focused");
    if($(this).parent().hasClass('number') && !$(this).parent().hasClass('phone') && !$(this).parent().hasClass('yearSince') && !$(this).parent().hasClass('pin')) {
      checkIfNumber($(this));
    } else if ($(this).parent().hasClass('email')) {
      checkIfEmail($(this));
    } else if($(this).parent().hasClass('number') && $(this).parent().hasClass('phone')) {
      checkIfPhone($(this));
    } else if($(this).parent().hasClass('yearSince')) {
      checkIfCorrectYear($(this));
    } else if($(this).parent().hasClass('pin')) {
      checkIfPesel($(this));
    } else if($(this).parent().hasClass('accountNr')) {
      checkAccountNumber($(this));
    } else {
      if ($(this).parent().hasClass('required')) {
      checkIfInput($(this));
      }
    }
  });
  $(".field select").focusin(function() {
    if(!$(this).next('.rules').length) {
      $(this).parent().append("<p class='rules'></p>");
    }
    $(this).parent().addClass("focused");
  });
  $(".field select").focusout(function() {
    $(this).parent().removeClass("focused");
    if($(this).parent().hasClass('required')) {
      checkIfInput($(this));
    }
  });
  $(".field input").on("input", function() {
    if($(this).val()){
      $(this).parent().addClass("filled");
    } else {
      $(this).parent().removeClass("filled");
    }
  });
  $(".field select").on("change", function() {
    if ($(this).val() != '') { //if not empty
      $(this).parent().addClass("filled");
    } else {
      $(this).parent().removeClass("filled");
    }
  });
});

function checkIfInput(a) {
  var $parent = a.parent();
  if(a.val() == '') {
    $parent.addClass('error empty');
    $parent.removeClass('notnumber notemail');
    $parent.find('.rules').text('This field is required');
  } else if(a.val() != '' && $parent.hasClass('notnumber')) {
    $parent.removeClass('empty');
  } else {
    $parent.removeClass('error empty');
    $parent.find('.rules').text('');
  }
}

function checkIfNumber(a) {
  var $parent = a.parent();
  if (!a.val().match(/^[0-9]+$/) && a.val()) {
    console.log('number func');
    $parent.addClass('error notnumber');
    $parent.find('.rules').text('Not a number');
  } else if(!a.val()) {
    $parent.addClass('error empty').removeClass('notnumber');
    $parent.find('.rules').text('This field is required');
  } else {
    $parent.removeClass('error notnumber');
    $parent.find('.rules').text('');
  }
}

function checkIfEmail(a) {
  var $parent = a.parent();
  var re = /\S+@\S+\.\S+/;
  console.log(re.test(a.val()));
  if(!re.test(a.val()) && a.val()) {
    $parent.addClass('error notemail');
    $parent.find('.rules').text('Not a correct email');
  } else if(!a.val()) {
    $parent.addClass('error empty').removeClass('notemail');
    $parent.find('.rules').text('This field is required');
  } else {
    $parent.removeClass('error notemail');
    $parent.find('.rules').text('');
  }
}

function checkIfPhone(a) {
  var $parent = a.parent();
  var yes = /^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/im.test(a.val());
  if(!yes && a.val()) {
    $parent.addClass('error notphone');
    $parent.find('.rules').text('Not a correct phone number');
  } else if(!a.val()) {
    $parent.addClass('error empty').removeClass('notphone');
    $parent.find('.rules').text('This field is required');
  } else {
    $parent.removeClass('error notphone');
    $parent.find('.rules').text('');
  }
}

function checkIfCorrectYear(a) {
  var $parent = a.parent();
  var year = parseInt(a.val());
  if(year <= 1989 || year >= 2019) {
    $parent.addClass('error wrongyear');
    $parent.find('.rules').text('Year has to be between 1990 and 2018');
  } else if(!a.val()) {
    $parent.addClass('error empty').removeClass('wrongyear');
    $parent.find('.rules').text('This field is required');
  } else {
    $parent.removeClass('error wrongyear');
    $parent.find('.rules').text('');
  }
}

function checkIfPesel(a) {
  var $parent = a.parent();
  var value = a.val();
  if(value){
    if (value.length != 11) {
      $parent.addClass('error wrongpin');
      $parent.find('.rules').text('Incorrect personal ID length');
        return false;
    }
    var equal = ((1 * parseInt(value[0])) + (3 * parseInt(value[1])) + (7 * parseInt(value[2])) + (9 * parseInt(value[3])) + (1 * parseInt(value[4])) + (3 * parseInt(value[5])) + (7 * parseInt(value[6])) + (9 * parseInt(value[7])) + (1 * parseInt(value[8])) + (3 * parseInt(value[9]))) % 10;
    if (0 == equal) {
        equal = 10;
    }
    equal = (10 - equal);
    if (equal != value[10]) {
      $parent.addClass('error wrongpin');
      $parent.find('.rules').text('Incorrect personal ID');
    } else {
      $parent.removeClass('error wrongyear');
      $parent.find('.rules').text('');
    }
  } else {
    $parent.addClass('error empty').removeClass('wrongyear');
    $parent.find('.rules').text('This field is required');
  }
}

function checkAccountNumber(a) {
  var $parent = a.parent();
  var value = a.val();
  var yes = /^PL\d{2}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{2}|PL\d{20}$/im.test(a.val());
  console.log(yes);
  if(!yes) {
    $parent.addClass('error wrongAccountNo');
    $parent.find('.rules').text('Not a correct IBAN');
  } else if(!a.val()) {
    $parent.addClass('error empty').removeClass('wrongAccountNo');
    $parent.find('.rules').text('This field is required');
  } else {
    $parent.removeClass('error wrongAccountNo');
    $parent.find('.rules').text('');
  }
}
