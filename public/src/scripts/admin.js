$(document).ready(function() {
  console.log("admin js loaded");
if(document.URL.indexOf("admin/applications") >= 0) {
  $('.nav-item a[href$="/applications"]').parent().addClass('active');
} else if(document.URL.indexOf("admin/partners") >= 0) {
  $('.nav-item a[href$="/partners"]').parent().addClass('active');
}
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