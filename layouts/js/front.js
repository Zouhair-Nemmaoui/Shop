$(function(){

    'use strict';
    // Für alle required Felder im Formular
  $('form input[required], form select[required], form textarea[required]').each(function () {
    // Nächstes Label finden
    let label = $("label[for='" + $(this).attr('id') + "']");
    if (label.length && label.find('.req-star').length === 0) {
      label.append('<span class="req-star text-danger"> *</span>');
    }
  });
    // Switch Between Login And Signup
     $('.login-page h1 span').click(function(){

           $(this).addClass('selected').siblings().removeClass('selected');
           
           $('.login-page form').hide();
           
           $('.' + $(this).data('class')).fadeIn(100);
     });
    // Trigger The Selectboxit
    $("select").selectBoxIt({
        autoWidth: false
    });

    // Hide Placeholder on form focus
    
    $('[placeholder]').focus(function(){
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function (){
        $(this).attr('placeholder',$(this).attr('data-text'));
    });
   // Confirmation Message On Button 
   $('.confirm').click(function(){
    return confirm('Are You Sure?'); 
   }); 
     
    $('.live').keyup(function () {
      $($(this).data('class')).text($(this).val());
    });
    


    });
 