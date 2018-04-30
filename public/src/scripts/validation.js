// Variables for translations
var empty_field = 'This field is required';
var not_a_number = 'Not a number';
var not_ID = 'Incorrect personal ID';
var not_docNr = 'Not a correct document number';
var not_email = 'Not a correct email';
var not_phone = 'Not a correct phone number';
var not_correct_year = 'Year has to be between 1990 and 2018';
var not_zip = 'Incorrect ZIP. Expected format XX-XXX';
var not_iban = 'Not a correct IBAN';

$(document).ready(function() {
  if($('.checkbox.emarketing input[type=checkbox]').is(':checked') && $('.loan-form').hasClass('loan-form-errors')) {
    $('.step-2').css('display','block');
  }
  // Checkboxes
  $('.checkbox.emarketing input[type=checkbox]').change(
    function(){
        if (this.checked) {
          $('.step-1 .broker-btn').removeClass('broker-btn-disabled');
        } else {
          $('.step-1 .broker-btn').addClass('broker-btn-disabled');
        }
    });
    // Step-1 button functionality
    $('.step-1 .broker-btn').on('click', function(){
      $(this).css('display','none');
      $('.step-2').css('display','block');
    })

  $('.field input, .field select').each(
    function(){
        var val = $(this).val().trim();
        if (val != ''){
            // do stuff with the non-valued element
            $(this).parent().addClass("filled");
            console.log($(this));
        }
    });
  // Temporary input field focused and filled classes
  $(".field input").focusin(function() {
    if(!$(this).next('.rules').length) {
      $(this).parent().append("<p class='rules'></p>");
    }
    $(this).parent().addClass("focused");
  });
  $(".field input").focusout(function() {
    $(this).parent().removeClass("focused");
    if($(this).parent().hasClass('number') && !$(this).parent().hasClass('phone') && !$(this).parent().hasClass('yearSince') && !$(this).parent().hasClass('pin') && !$(this).parent().hasClass('zip')) {
      checkIfNumber($(this));
    } else if ($(this).parent().hasClass('email')) {
      checkIfEmail($(this));
    } else if($(this).parent().hasClass('number') && $(this).parent().hasClass('phone')) {
      checkIfPhone($(this));
    } else if($(this).parent().hasClass('yearSince')) {
      checkIfCorrectYear($(this));
    } else if($(this).parent().hasClass('pin')) {
      checkIfPesel($(this));
    } else if($(this).parent().hasClass('documentNr')) {
      checkIfId($(this));
    } else if($(this).parent().hasClass('accountNr')) {
      checkAccountNumber($(this));
    } else if($(this).parent().hasClass('zip')) {
      checkZip($(this));
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

//Functions to validate fields
function checkIfInput(a) {
  var $parent = a.parent();
  var value = a.val();
  if(value == '') {
    $parent.addClass('error empty');
    $parent.removeClass('notnumber notemail');
    $parent.find('.rules').text(empty_field);
  } else if(value != '' && $parent.hasClass('notnumber')) {
    $parent.removeClass('empty');
  } else {
    $parent.removeClass('error empty');
    $parent.find('.rules').text('');
  }
}

function checkIfNumber(a) {
  var $parent = a.parent();
  var value = a.val();
  if (!value.match(/^[0-9]+$/) && value) {
    $parent.addClass('error notnumber');
    $parent.find('.rules').text(not_a_number);
  } else if(!value) {
    $parent.addClass('error empty').removeClass('notnumber');
    $parent.find('.rules').text(empty_field);
  } else {
    $parent.removeClass('error notnumber');
    $parent.find('.rules').text('');
  }
}

function checkIfEmail(a) {
  var $parent = a.parent();
  var value = a.val();
  var re = /\S+@\S+\.\S+/;
  if($parent.hasClass('required')){
    if(!re.test(value) && value) {
      $parent.addClass('error notemail');
      $parent.find('.rules').text(not_email);
    } else if(!value) {
      $parent.addClass('error empty').removeClass('notemail');
      $parent.find('.rules').text(empty_field);
    } else {
      $parent.removeClass('error notemail');
      $parent.find('.rules').text('');
    }
  }
}

function checkIfPhone(a) {
  var $parent = a.parent();
  var value = a.val();
  var yes = /^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/im.test(value);
  if(!yes && value) {
    $parent.addClass('error notphone');
    $parent.find('.rules').text(not_phone);
  } else if(!value) {
    $parent.addClass('error empty').removeClass('notphone');
    $parent.find('.rules').text(empty_field);
  } else {
    $parent.removeClass('error notphone');
    $parent.find('.rules').text('');
  }
}

function checkIfCorrectYear(a) {
  var $parent = a.parent();
  var value = a.val();
  var year = parseInt(value);
  if(year <= 1989 || year >= 2019) {
    $parent.addClass('error wrongyear');
    $parent.find('.rules').text(not_correct_year);
  } else if(!value) {
    $parent.addClass('error empty').removeClass('wrongyear');
    $parent.find('.rules').text(empty_field);
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
      $parent.find('.rules').text(not_ID);
        return false;
    }
    var equal = ((1 * parseInt(value[0])) + (3 * parseInt(value[1])) + (7 * parseInt(value[2])) + (9 * parseInt(value[3])) + (1 * parseInt(value[4])) + (3 * parseInt(value[5])) + (7 * parseInt(value[6])) + (9 * parseInt(value[7])) + (1 * parseInt(value[8])) + (3 * parseInt(value[9]))) % 10;
    if (0 == equal) {
        equal = 10;
    }
    equal = (10 - equal);
    if (equal != value[10]) {
      $parent.addClass('error wrongpin');
      $parent.find('.rules').text(not_ID);
    } else {
      $parent.removeClass('error wrongyear');
      $parent.find('.rules').text('');
    }
  } else {
    $parent.addClass('error empty').removeClass('wrongyear');
    $parent.find('.rules').text(empty_field);
  }
}

function checkIfId(a) {
  var $parent = a.parent();
  var value = a.val();
  if(value) {
    if(!checkIdNum(value)) {
      $parent.remove('empty').addClass('error wrongdocNr');
      $parent.find('.rules').text(not_docNr);
    } else {
      $parent.removeClass('wrongdocNr error empty');
      $parent.find('.rules').text('');
    }
  } else {
    $parent.addClass('error empty').removeClass('wrongdocNr');
    $parent.find('.rules').text(empty_field);
  }
}

function checkZip(a) {
  var $parent = a.parent();
  var value = a.val();
  var yes = /\d{2}-\d{3}/.test(a.val());
  if(!yes && value) {
    $parent.addClass('error wrongzip');
    $parent.find('.rules').text(not_zip);
  } else {
    $parent.removeClass('error wrongzip');
    $parent.find('.rules').text('');
  }
}

function checkAccountNumber(a) {
  var $parent = a.parent();
  var value = a.val();
  var yes = /^PL\d{2}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{4}[ ]\d{2}|PL\d{20}$/im.test(a.val());
  if(!yes && value) {
    $parent.addClass('error wrongAccountNo');
    $parent.find('.rules').text(not_iban);
  } else if(!value) {
    $parent.addClass('error empty').removeClass('wrongAccountNo');
    $parent.find('.rules').text(empty_field);
  } else {
    $parent.removeClass('error wrongAccountNo');
    $parent.find('.rules').text('');
  }
}


// Helper function to check Polish ID card number
function checkIdNum(id) {
	var x, sum_nb=0, check;
	var steps = new Array(7,3,1,7,3,1,7,3);
	id = id.toUpperCase();
	if (id.split("").length != 9) {return false;}
	for (x = 0; x < 9; x++) {
		if (x == 0 || x == 1 || x == 2) {
			sum_nb += steps[x] * parseInt(id.charCodeAt(x)-55);
		}
		if (x > 3) {
			sum_nb += steps[x-1] * parseInt(id.charAt(x));
		}
  }
	check = sum_nb % 10;
    if (check == id.charAt(3)){
      return true;
    }
    else {
      return false;
    }
}
