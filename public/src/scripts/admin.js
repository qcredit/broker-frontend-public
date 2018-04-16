$(document).ready(function() {
  console.log("admin js loaded");
if(document.URL.indexOf("admin/applications") >= 0) {
  $('.nav-item a[href$="/applications"]').parent().addClass('active');
} else if(document.URL.indexOf("admin/partners") >= 0) {
  $('.nav-item a[href$="/partners"]').parent().addClass('active');
} else if(document.URL.indexOf("admin/users") >= 0) {
  $('.nav-item a[href$="/users"]').parent().addClass('active');
}
});
