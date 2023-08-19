(function ($) {
// "use strict";

// meanmenu
$('#mobile-menu').meanmenu({
	meanMenuContainer: '.mobile-menu',
	meanScreenWidth: "992"
});

// One Page Nav
var top_offset = $('.header-area').height() - 10;
$('.main-menu nav ul').onePageNav({
	currentClass: 'active',
	scrollOffset: top_offset,
});


$(window).on('scroll', function () {
	var scroll = $(window).scrollTop();
	if (scroll < 245) {
		$(".header-sticky").removeClass("sticky");
	} else {
		$(".header-sticky").addClass("sticky");
	}
});


// mainSlider
function mainSlider() {
	var BasicSlider = $('.slider-active');
	BasicSlider.on('init', function (e, slick) {
		var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
		doAnimations($firstAnimatingElements);
	});
	BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
		var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
		doAnimations($animatingElements);
	});
	BasicSlider.slick({
		autoplay: false,
		autoplaySpeed: 10000,
		dots: false,
		fade: true,
		arrows: false,
		responsive: [
			{ breakpoint: 767, settings: { dots: false, arrows: false } }
		]
	});

	function doAnimations(elements) {
		var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		elements.each(function () {
			var $this = $(this);
			var $animationDelay = $this.data('delay');
			var $animationType = 'animated ' + $this.data('animation');
			$this.css({
				'animation-delay': $animationDelay,
				'-webkit-animation-delay': $animationDelay
			});
			$this.addClass($animationType).one(animationEndEvents, function () {
				$this.removeClass($animationType);
			});
		});
	}
}
mainSlider();

/* magnificPopup img view */
$('.popup-image').magnificPopup({
	type: 'image',
	gallery: {
	  enabled: true
	}
});

/* magnificPopup video view */
$('.popup-video').magnificPopup({
	type: 'iframe'
});


// isotop
$('.grid').imagesLoaded( function() {
	// init Isotope
	var $grid = $('.grid').isotope({
	  itemSelector: '.grid-item',
	  percentPosition: true,
	  masonry: {
		// use outer width of grid-sizer for columnWidth
		columnWidth: '.grid-item',
	  }
	});
});

// filter items on button click
$('.portfolio-menu').on( 'click', 'button', function() {
  var filterValue = $(this).attr('data-filter');
  $grid.isotope({ filter: filterValue });
});

//for menu active class
$('.portfolio-menu button').on('click', function(event) {
	$(this).siblings('.active').removeClass('active');
	$(this).addClass('active');
	event.preventDefault();
});


// scrollToTop
$.scrollUp({
	scrollName: 'scrollUp', // Element ID
	topDistance: '300', // Distance from top before showing element (px)
	topSpeed: 300, // Speed back to top (ms)
	animation: 'fade', // Fade, slide, none
	animationInSpeed: 200, // Animation in speed (ms)
	animationOutSpeed: 200, // Animation out speed (ms)
	scrollText: '<i class="las la-angle-double-up"></i>', // Text for element
	activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
});

// WOW active
new WOW().init();

// open lang dropdown
$('.lang').click(function(){
	$('.lang-switcher').toggleClass('active')
})
$('.lang-option').click(function(){
	$('.lang-switcher').removeClass('active')
})
$('.lang-dropdown #eng').click(function(){
	$('html').removeAttr('dir')
	$('html').attr("dir", "ltr")
})
$('.lang-dropdown #ar').click(function(){
	$('html').removeAttr('dir')
	$('html').attr("dir", "rtl")
})


// open sidebar
$('.main-menu-toggle').click(function(){
	$('.off-canvas-main-menu').toggleClass('active')
})
$('.off-canvas-menu-close').click(function(){
	$('.off-canvas-main-menu').removeClass('active')
})
// open chat sidebar
$('.chat-opener-btn').click(function(){
	$('.chat-left-sidebar').toggleClass('active')
})
$('.chat-user-item').click(function(){
	$('.chat-left-sidebar').removeClass('active')
})


// slider
$('.slider').owlCarousel({
	rtl:true,
    loop:true,
    margin:0,
	items:1,
	navText:['<i class="las la-long-arrow-alt-left"></i>','<i class="las la-long-arrow-alt-right"></i>'],
    nav:true,
	dots:false,
	animateOut: 'fadeOut',
	mouseDrag:false,
	touchDrag:false
})
// post-details-slider
$('.post-details-slider').owlCarousel({
	rtl:true,
    loop:true,
    margin:0,
	items:1,
	navText:['<i class="las la-long-arrow-alt-left"></i>','<i class="las la-long-arrow-alt-right"></i>'],
    nav:true,
	dots:false,
	animateOut: 'fadeOut',
	mouseDrag:false,
	touchDrag:false
})
// service category
$('.service-category').owlCarousel({
	rtl:true,
    loop:true,
    margin:0,
	items:7,
    nav:false,
	dots:false,
	responsive:{
        0:{
            items:3
        },
        767:{
            items:4
        },
        992:{
            items:7
        }
    }
})
// posts
$('.posts').owlCarousel({
	rtl:true,
    loop:true,
    margin:30,
	items:3,
	navText:['<i class="las la-long-arrow-alt-left"></i>','<i class="las la-long-arrow-alt-right"></i>'],
    nav:true,
	dots:false,
	responsive:{
        0:{
            items:1
        },
        767:{
            items:2
        },
        992:{
            items:2
        },
		1200:{
			items:3
		}

    }
})

// choosen
// $(function () {
// 	$(".chzn-select").chosen();
// });

})(jQuery);