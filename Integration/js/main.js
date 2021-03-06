(function($){
   /*
    * Détection mobile
    */
    function isMobile(){
        if(/Android|webOS|iPhone|iPad|iPod|pocket|psp|kindle|avantgo|blazer|midori|Tablet|Palm|maemo|plucker|phone|BlackBerry|symbian|IEMobile|mobile|ZuneWP7|Windows Phone|Opera Mini/i.test(navigator.userAgent)){
            return true;
        }
        return false;
    };

   /*
	* Menu responsive (Bootstrap)
	*/
    $('.collapse').collapse('toggle');
    
   /*
    * Dropdown (Bootstrap)
    */
    $('.dropdown-toggle').dropdown();

    if(isMobile() !== true){
       /*
        * Bandeau photos
        */
        $('.pic-info').hide();
        $('.pic').hover(function(e){
            var $pic = $(this);
            $pic.find('.pic-info').show();
        },function(){
            $('.pic-info').hide();
        });
    }else{
       /*
        * Adapter background index aux smartphones
        */
        if($(window).width() !== 0 && $(window).width() <= 425){
            $('.index').removeClass().addClass('index-mobile');
            $('.login').removeClass().addClass('login-mobile');
        }
    }

   /*
    * Empecher le click droit dans la page
    */
    $('.r-click').hide();
    $('.fancybox img, .pic-img-port img, .pic-img-land img').bind('contextmenu', function(e){
        $('.r-click').fadeIn().delay('5000').fadeOut();
        $('.r-click a').click(function(){
            $('.r-click').stop().fadeOut();
        });
        return false;
    });

   /*
    * Datepicker paramètres du compte
    */
    // $('#birth').datepicker({
    //     format: 'yyyy-mm-dd'
    // });
    $('#birth').data({startDate: '2012-08-08'});
    $('#birth').datepicker('update');
    
    // $('#birth').datepicker({
    //     format: 'mm/dd/yyyy',
    //     startDate: '-3d'
    // });

   /*
    * Fancybox
    */
    $('.fancybox').fancybox({
       /*
        * Empecher le click droit dans le visualisateur
        */
        beforeShow: function(){
            $.fancybox.wrap.bind('contextmenu', function(e){
                return false; 
            });
        },
        padding: 0,
        closeBtn: false,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        helpers : {
            title : {
                type : 'over'
            }
        }
    });
   
   /*
    * Afficher/Masquer les informations exifs lors
    * de l'édition de la fiche photo en fonction des
    * bouttons radio
    */
    $('.exifs input:radio').click(function(){
        var value = $(this).val();
        if(value === 'option2'){
            $('.exifs-infos').slideUp();
        }else{
            $('.exifs-infos').slideDown();
        }

    });
})(jQuery);
