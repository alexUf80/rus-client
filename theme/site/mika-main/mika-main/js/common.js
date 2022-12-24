$(document).ready(function(){

  var $range_sum = $("#amount-one");
  var $range_day = $("#amount-two");
  var $res1 = $("#demo");
  var $res2 = $("#demo2");
  // var instance;
  // var min = 50000;
  // var max = 12000;

  $range_sum.ionRangeSlider({
    skin: "round",
    type: "single",
    grid: true,
    min: 3000,
    max: 12000,
    from: 5000,
    step: 1000,
    // grid_num: 4, 
    grid_snap: true,
    // values: [
    //   "3000", "5000", "7000", "10000", "12000"
    // ],
    onStart: function(data) {
      $res1.text(data.from + " ₽");
  },
    onChange: function(data) {
      $res1.text(data.from + " ₽");
  },
     postfix: " ₽",
});

$range_day.ionRangeSlider({
  skin: "round",
  type: "single",
  grid: true,
  min: 7,
  max: 30,
  from: 7,
  step: 1,
  grid_num: 4, 
  // grid_snap: true,
  // values: [
  //   "3000", "5000", "7000", "10000", "12000"
  // ],
  onStart: function(data) {
    $res2.text(data.from + " дней");
},
  onChange: function(data) {
    $res2.text(data.from + " дней");
},
   postfix: " дней",
});
  



$('.footer_menu .navbar-toggler').click(function(){
  if ($('.footer_menu_nav').hasClass("active")){
    $('.footer_menu_nav').removeClass("active").slideUp();
  } else{
    $('.footer_menu_nav ').addClass("active").slideDown();
  }
  return false;
})





// кнопка вверх
$(function() {
	$(window).scroll(function() {
	 if($(this).scrollTop() != 0) {
	   $('#toTop').fadeIn();
	 } else {
	   $('#toTop').fadeOut();
	 }
	});
	$('#toTop').click(function() {
	 $('body,html').animate({scrollTop:0},800);
	});
});

// mask
$(function(){
  $("#tel").mask("+7(999) 999-9999");
});

if($(window).width()<='575'){}









});//$(document).ready

