// $(document).ready(function(){

//   $('.footer_menu .navbar-toggler').click(function(){
//     if ($('.footer_menu_nav').hasClass("active")){
//       $('.footer_menu_nav').removeClass("active").slideUp();
//     } else{
//       $('.footer_menu_nav ').addClass("active").slideDown();
//     }
//     return false;
//   });

//   $('input[type="file"]').change(function(){
//     var value = $("input[type='file']").val();
//     $('.js-value').text(value);
//   });

//   if($('.input_box .form-control').val.length >= 1){
//     $(this).next('.input_name').addClass('-top');
//   }else{
//     $(this).next('.input_name').removeClass('-top');
//   }


//   $('.input_box .form-control').on('keyup',function(){
//     if($(this).val.length >= 1){
//       $(this).next('.input_name').addClass('-top');
//     }else{
//       $(this).next('.input_name').removeClass('-top');
//     }
//   })

//     $('.mobheader-toggler').click(function(e){
//         e.preventDefault();
//         if ($('.mobheader').hasClass('menu-open'))
//         {
//             $('.mobheader').removeClass('menu-open');
            
//             $('.mobheader-menu-nav').removeClass('open');
            
//         }
//         else
//         {
//             $('.mobheader').addClass('menu-open');
            
//             $('.mobheader-menu-nav').addClass('open');
            
//         }
//     })

//     $('input[name="phone"]').mask('+7 (999) 999-99-99');

//   // mask
//   $(function(){
//   });





// });








$(document).ready(function(){

  // $('.footer_menu .navbar-toggler').click(function(){
  //   if ($('.footer_menu_nav').hasClass("active")){
  //     $('.footer_menu_nav').removeClass("active").slideUp();
  //   } else{
  //     $('.footer_menu_nav ').addClass("active").slideDown();
  //   }
  //   return false;
  // });

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

    // $('.mobheader-toggler').click(function(e){
    //     e.preventDefault();
    //     if ($('.mobheader').hasClass('menu-open'))
    //     {
    //         $('.mobheader').removeClass('menu-open');
            
    //         $('.mobheader-menu-nav').removeClass('open');
            
    //     }
    //     else
    //     {
    //         $('.mobheader').addClass('menu-open');
            
    //         $('.mobheader-menu-nav').addClass('open');
            
    //     }
    // })
    $('.new-header__hamburger-icon').click(function(){
      $('.new-hamburger-menu').fadeIn(300);
    })

    $('.new-hamburger-menu__close').click(function(){
      $('.new-hamburger-menu').fadeOut(300);
    })

    $('input[name="phone"]').mask('+7(999)999-99-99');


    $('.page-contacts__form.form').submit(function(e){
      e.preventDefault();
      let form = $(this).serialize();
      let formDescription = $(this.querySelector('.form__description'));
      let name = $(this.querySelector('input[name="name"]'));
      let email = $(this.querySelector('input[name="email"]'));
      let phone = $(this.querySelector('input[name="phone"]'));
      let phoneValue = $(phone).val();
      let message = $(this.querySelector('textarea[name="message"]'));
      let errors = 0;

      if(phoneValue.length !== 16){
        errors++;        
        $(formDescription).text('Введите корректный номер телефона')
      }

      if(errors == 0){
        $.ajax({
            type: "POST",
            url: '/ajax/sendForm.php',
            data: form,
            success: function(respose){
              if(respose == 1){
                $(name).val('');
                $(email).val('');
                $(phone).val('');
                $(message).val('');
                $(formDescription).text('Ваша заявка успешно отправлена!')
              }
            }
        });
    
      }

    })
});