$(document).ready(function() {
  console.log("admin js loaded");
  if(document.URL.indexOf("office/applications") >= 0) {
    $('.nav-item a[href$="/applications"]').parent().addClass('active');
  } else if(document.URL.indexOf("admin/partners") >= 0) {
    $('.nav-item a[href$="/partners"]').parent().addClass('active');
  } else if(document.URL.indexOf("admin/users") >= 0) {
    $('.nav-item a[href$="/users"]').parent().addClass('active');
  }
  calculateLines('day');
  $('[data-toggle="tooltip"]').tooltip();
  $('.date-select').on('click', function() {
    var new_range = getRange($(this));
    $('.date-select').removeClass('active');
    $(this).addClass('active');
    $('.amount').removeClass('show');
    $('.amount.'+new_range).addClass('show');
    calculateLines(new_range);
  });
});

function initAuth()
{
  gapi.load('auth2', function()
  {
    gapi.auth2.init();
  });
}

function signOut() {
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.signOut().then(function () {
    console.log('User signed out.');
  });
}

function getRange(a) {
  if(a.hasClass('date-select-day')){
    return 'day'
  } else if(a.hasClass('date-select-week')){
    return 'week'
  } else if(a.hasClass('date-select-month')) {
    return 'month'
  } else {
    return
  }
}

function calculateLines(a) {
  var loan_stats = [];
  var total = $('.amount.total.'+a).text();
  loan_stats.push($('.amount.accepted.'+a).text());
  loan_stats.push($('.amount.paid-out.'+a).text());
  loan_stats.push($('.amount.rejected.'+a).text());
  loan_stats.push($('.amount.in-process.'+a).text());
  $('.stats-line-item').each(function(i) {
    var line_width = (loan_stats[i] / total) * 100;
    console.log(line_width)
    if(!line_width) {
      $(this).css('flex-basis', '0%');
    } else {
      $(this).css('flex-basis', line_width+'%');
    }
  });
}
