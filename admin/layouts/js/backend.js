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




     // Dashboard 
    $('.toggle-info').click(function() {
    
    if ($(this).parent().next('.panel-body').is(':animated')) {
        return;
    }

    $(this).toggleClass('selected');
    $(this).parent().next('.panel-body').fadeToggle(100);

    if ($(this).hasClass('selected')) {
        $(this).html('<i class="fa fa-minus fa-lg"></i>');
    } else {
        $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
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

   // Category View Option 

   $('.cat h3').click(function(){
   
    $(this).next('.full-view').fadeToggle(500);
   });
   $('.option span').click(function (){
    $(this).addClass('active').siblings('span').removeClass('active');

    if($(this).data('view') === 'full' ) {

        $('.cat .full-view').fadeIn(200);
    } else {
        $('.cat .full-view').fadeOut(200);
    }
   });  
    
   // Show Delete Button On Child Cats

   $('.child-link').hover(function () {
       $(this).find('.show-delete').fadeIn(400);
   }, function () {
     $(this).find('.show-delete').fadeOut(400);
   });

    });
 