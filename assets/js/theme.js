;(function($) {
    "use strict"; 
	
	//* Navbar Fixed  
    function navbarFixed(){
        if ( $('body').length ){ 
            $(window).on('scroll', function() {
                var scroll = $(window).scrollTop();   
                if (scroll >= 295) {
                    $(".main_header_area").addClass("navbar_fixed");
                } else {
                    $(".main_header_area").removeClass("navbar_fixed");
                }
            });  
			
            // toggle_menu
            $("#menu-opener").on('click', function () {
                $(".toggle_menu").toggleClass("active");
            });
            $("section, .close").on('click', function () {
                $(".toggle_menu").removeClass("active");
            });
        };
    };   
	
    //* Magnificpopup js
    function magnificPopup() {
        if ($('.popup-youtube, .portfoli_inner').length) { 
            //Video Popup
            $('.popup-youtube').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false, 
                fixedContentPos: false,
            });  
			
			// Image popups
            $('.environment_img').magnificPopup({
                delegate: '.zoom_img',
                type: 'image',
                removalDelay: 500,
                callbacks: {
                    beforeOpen: function () { 
                        this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                closeOnContentClick: true,
                midClick: true
            });
        };
    };  
	
	//* Select js
    function selectmenu(){
        if ( $('.post_select').length ){ 
            $('select').niceSelect();
        };
    };  
	
	//* Testimonial 
    function testimonial(){
        if ( $('.testimonial_slid').length ){ 
            $('.testimonial_slid').owlCarousel({
            	loop: true,
            	margin: 30,
            	nav: false,
				dots: true,
            	responsive: {
            		0: {
            			items: 1
            		}, 
            		991: {
            			items: 2
            		}
            	}
            })
        };
    };   
	
	//* Slider Js 
    function sliderArea(){
        if ( $('.slider_area').length ){ 
            $('.slider').bind('mousewheel DOMMouseScroll', function(e){ 
				if(e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
					$(this).carousel('prev'); 
				}
				else{
					$(this).carousel('next'); 
				}
			});
        };
    }; 
    
    // Scroll to top
    function scrollToTop() {
        if ($('.scroll-top').length) {  
            $(window).on('scroll', function () {
                if ($(this).scrollTop() > 200) {
                    $('.scroll-top').fadeIn();
                } else {
                    $('.scroll-top').fadeOut();
                }
            }); 
            //Click event to scroll to top
            $('.scroll-top').on('click', function () {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                return false;
            });
        }
    }
    
    // Preloader JS
    function preloader(){
        if( $('.preloader').length ){
            $(window).on('load', function() {
                $('.preloader').fadeOut();
                $('.preloader').delay(50).fadeOut('slow');  
            })   
        }
    }     
	
    /*Function Calls*/ 
//    new WOW().init();
    navbarFixed ();   
	scrollToTop ();
	magnificPopup (); 
	selectmenu ();
	testimonial ();
	sliderArea ();
	preloader ();
    
})(jQuery);


     function encrypt()
	 {
        var pass=document.getElementById('lvpwd').value;
		var hide=document.getElementById('hide').value;

        document.getElementById("hide").value = document.getElementById("lvpwd").value;
        var hash = CryptoJS.MD5(pass);
        document.getElementById('lvpwd').value=hash;
        return true;
	}

    function mySignUp() {
        document.getElementById("signUpModal").style.display = "block";
    }
    function closeSignUp() {
        document.getElementById("signUpModal").style.display = "none";
    }
