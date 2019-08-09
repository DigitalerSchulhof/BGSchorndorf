$(document).ready(function() {
  $(".cms_sprachwahl").click(function() {
    Cookies.set("sprache", $(this).data("sprache"));
    location.reload();
  });
});
