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
  });

  $('input[type="file"]').change(function(){
    var value = $("input[type='file']").val();
    $('.js-value').text(value);
  });

  if($('.input_box .form-control').val.length >= 1){
    $(this).next('.input_name').addClass('-top');
  }else{
    $(this).next('.input_name').removeClass('-top');
  }


  $('.input_box .form-control').on('keyup',function(){
    if($(this).val.length >= 1){
      $(this).next('.input_name').addClass('-top');
    }else{
      $(this).next('.input_name').removeClass('-top');
    }
  })

  // mask
  $(function(){
    $("#tel").mask("+7(999) 999-9999");
    $("#birth_date").mask('99.99.99',{placeholder:"01.08.77"});
    $("#series").mask('9999-999999',{placeholder:"1332-412412"});
    $("#issue").mask('99.99.99',{placeholder:"01.08.77"});
    $("#department_code").mask('999-999',{placeholder:"100-200"});
  });


});






