/**
 * Responsive Pricing Table Front JS
 * Last updated: Nov 24, 2017 
 */

;(function($){
  $(document).ready(function (){

  /* Debounce function for fallback keyup. */
  // http://davidwalsh.name/javascript-debounce-function
  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
  };

  
  // Price switch.
  $('body').on('change', '.rpt_price_switch', function(){
    if (!$(this).closest('.rpt_plans').hasClass('rpt_switched')) {
      $(this).closest('.rpt_plans').addClass('rpt_switched');
    } else {
      $(this).closest('.rpt_plans').removeClass('rpt_switched');
    }
  });

  


  // Equalize each plan of each pricing table on the page.
  function rpt_equalize(){

    $('.rpt_title, .rpt_head, .rpt_features').css({ 'height' : 'auto'});
    $('.rpt_plans').each(function(){

      var current_plan = $(this);

      if(parseInt($( window ).width()) > 640){

        if(current_plan.hasClass('rpt_plan_eq')){

          var titles = [], heads = [], features = [];
          var biggestTitle = [], biggestHead = [], biggestFeatures = [];
      
          current_plan.find('.rpt_title').each(function(){
      
            var current_title = $(this);
            titles.push(current_title.outerHeight());
            
          });
      
          current_plan.find('.rpt_head').each(function(){
      
            var current_head = $(this);
            heads.push(current_head.outerHeight());
      
          });
      
          current_plan.find('.rpt_features').each(function(){
            
            var current_features = $(this);
            features.push(current_features.outerHeight());
      
          });
      
          biggestTitle = Math.max.apply(Math, titles);
          biggestHead = Math.max.apply(Math, heads);
          biggestFeatures = Math.max.apply(Math, features);
      
          current_plan.find('.rpt_title').outerHeight(biggestTitle);
          current_plan.find('.rpt_head').outerHeight(biggestHead);
          current_plan.find('.rpt_features').outerHeight(biggestFeatures);

        }

      } else {

        current_plan.find('.rpt_title').outerHeight('auto');
        current_plan.find('.rpt_head').outerHeight('auto');
        current_plan.find('.rpt_features').outerHeight('auto');

      }
  
    });

    setTimeout(function(){
      $('img.rpt_recommended').each(function(){
        var topPush = ($(this).parent().outerHeight()/2) - ($(this).height()/2);
        $(this).css('top', (topPush - 2)+'px');
      });
    }, 50);
      
  }

  // Calls (wait for images).
  $(window).on("ready", rpt_equalize());
  $(window).on("load", rpt_equalize());

  // Triggers equalizer on resize.
  $(window).resize( debounce(function() { rpt_equalize(); }, 100));
  
});
})(jQuery);