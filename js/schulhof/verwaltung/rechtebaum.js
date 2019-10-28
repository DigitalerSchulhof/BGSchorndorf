$(document).ready(function() {
  $(".cms_recht>.icon").click(function() {
    $(this).toggleClass("cms_recht_eingeklappt");
    $(this).siblings(".cms_rechtekinder").slideToggle("slow");
  });
});
