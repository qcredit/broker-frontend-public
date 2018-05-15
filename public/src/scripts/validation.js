$(document).ready(function() {
  // Checkboxes
  $('.checkbox.marketingConsent input[type=checkbox]').change(
    function(){
        if (this.checked) {
          $('.step-1 .broker-btn').removeClass('broker-btn-disabled');
        } else {
          $('.step-1 .broker-btn').addClass('broker-btn-disabled');
        }
    });
// check if input/select option has value
  $('.field input, .field select').each(
    function(){
        var val = $(this).val().trim();
        if (val != ''){
            $(this).parent().addClass("filled");
        }
    });
  // Temporary input field focused and filled classes
  $(".field input, .field select").focusin(function() {
    $(this).parent().addClass("focused");
  });
  $(".field input, .field select").focusout(function() {
    $(this).parent().removeClass("focused");
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
    //$parent.find('.rules').text(empty_field);
  } else if(value != '' && $parent.hasClass('notnumber')) {
    $parent.removeClass('empty');
  } else {
    $parent.removeClass('error empty');
    //$parent.find('.rules').text('');
  }
}
