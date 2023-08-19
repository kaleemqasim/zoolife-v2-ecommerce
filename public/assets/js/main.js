$(document).ready(function(){
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
// $('.lang-option').click(function(){
// 	$('.lang-switcher').removeClass('active');
// })
// $('.lang-dropdown #eng').click(function(){
// 	$('html').removeAttr('dir')
// 	$('html').attr("dir", "ltr")
// })
// $('.lang-dropdown #ar').click(function(){
// 	$('html').removeAttr('dir')
// 	$('html').attr("dir", "rtl")
// })


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
    navText:['<i class="las la-long-arrow-alt-left"></i>','<i class="las la-long-arrow-alt-right"></i>'],
    nav:true,
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

	$('body').off('click', "#login-btn").on('click', "#login-btn", function(event) {
        event.preventDefault();
        var btn = $(this);
        var old_val = btn.val();
        var form = btn.closest('form');
        var formData = new FormData(form[0]);
        var submitUrl = btn.data('url') ? btn.data('url') : "";

        if (submitUrl) {
            $.ajax({
                url: submitUrl,
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                // data:  formData,
                // contentType: false,
                // cache: false,
                // processData:false,
                beforeSend: function() {
                    $('.error,.submit_notification').html('');
                    form.find(".form-control").removeClass("red-border");
                    $('.btn').attr("disabled", "disabled").val("Sending...");
                },
                success: function(result) {
                    $('.error').html('');
                    $('.btn').removeAttr("disabled").val(old_val);
                    if (result.code == 200) {
                        window.location.reload();
                    } else if (result.code == 402) {
                        $.each(result.errors, function(i, val) {
                            if (val != "") {
                                console.log("#" + i + "_error");
                                form.find("#" + i + "_error").text(val);
                            }
                        });
                    } else {
                        form.find('.submit_notification').html('<span class="text-danger error">' + result.message + '</span>');
                    }
                },
                error: function(e) {
                    $('.btn').removeAttr("disabled").val(old_val);
                    form.find('.submit_notification').html('<span class="text-danger error">Something Went Wrong!... Please try again after refresh</span>');
                }
            });
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#commonModal').on('show.bs.modal', function (e) {

        var btn = $(e.relatedTarget);
        var type = btn.data('type');
        var modalContent = $(this).find('.modal-content');
        var url = btn.data('url') ? btn.data('url') : "";
        if (url) {
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: {type: type },
                beforeSend: function() {
                    modalContent.html('<div class="text-center p-5"><i class="la la-5x la-spin la-spinner"></i><div> Loading...</div></div>')
                    $('.error,.submit_notification').html('');
                },
                success: function(result) {
                    $('.error').html('');
                    modalContent.html(result);
                    // setTimeout(function(){
                    //     loadSubCategory();
                    // },1000);
                },
                error: function(e) {
                    modalContent.html('<div class="modal-header pt-45"><h5 class="modal-title">Post</h5></div><div class="modal-body"><span class="text-danger error">Something Went Wrong!... Please try again after refresh</span><div class="modal-footer pb-35"><button type="button" class="btn theme-btn-light" data-bs-dismiss="modal">Cancel</button></div>');
                }
            });
        }
        
    });

    $('body').off('click', "#savePost").on('click', "#savePost", function(event) {
        event.preventDefault();
        var btn = $(this);
        var old_val = btn.val();
        var form = $('#savePostForm');
        var formData = new FormData(form[0]);
        var submitUrl = btn.data('url') ? btn.data('url') : "";

        if (submitUrl) {
            $.ajax({
                url: submitUrl,
                type: 'post',
                dataType: 'json',
                data:  formData,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function() {
                    $('.error,.submit_notification').html('');
                    form.find(".form-control").removeClass("red-border");
                    $('.btn').attr("disabled", "disabled").val("Sending...");
                },
                success: function(result) {
                    $('.error').html('');
                    $('.btn').removeAttr("disabled").val(old_val);
                    if (result.status == 200) {
                        window.location.reload();
                        $('#post_submit_notification').html('<span class="text-success error">' + result.message + '</span>');
                    } else if (result.status == 402) {
                        $.each(result.errors, function(i, val) {
                            if (val != "") {
                                form.find("#" + i + "_error").text(val);
                            }
                        });
                        console.log('hello');
                        $("#commonModal").animate({ scrollTop: 100 }, "slow");
                    } else {
                        $('#post_submit_notification').html('<span class="text-danger error">' + result.message + '</span>');
                    }
                },
                error: function(e) {
                    $('.btn').removeAttr("disabled").val(old_val);
                    $('#post_submit_notification').html('<span class="text-danger error">Something Went Wrong!... Please try again after refresh</span>');
                }
            });
        }
    });

    $('body').off('click', ".do-fav").on('click', ".do-fav", function(event) {
        event.preventDefault();
        var btn = $(this);
        // var submitUrl = btn.data('url') ? btn.data('url') : "";
        var fav = btn.data('fav');
        var itemId = btn.data('itemid');
        var type = btn.data('type');
        console.log(fav);

        if (favouritUrl) {
            $.ajax({
                url: favouritUrl,
                type: 'post',
                dataType: 'json',
                data: {fav: fav, id: itemId, type:type},
                beforeSend: function() {
                    $('.error,.submit_notification').html('');
                    $('.btn').attr("disabled", "disabled");
                },
                success: function(result) {
                    $('.error').html('');
                    $('.btn').removeAttr("disabled");
                    if (result.status == 200) {
                        if(fav == '1'){
                            btn.data('fav','0');
                            console.log(btn);
                            btn.find('.favrt-icon').removeClass('lar').addClass('las');
                            if(btn.find('span').length){
                                btn.find('span').text('Remove from favourites');
                            }
                        }else{
                            btn.data('fav','1');
                            btn.find('.favrt-icon').removeClass('las').addClass('lar');
                            if(btn.find('span').length){
                                btn.find('span').text('Add to favourites');
                            }
                        }
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                    } else {
                        // alert(result.message);
                        $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                    }
                },
                error: function(e) {
                    $('.btn').removeAttr("disabled");
                    $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                }
            });
        }
    });

    $('body').off('click', ".do-like").on('click', ".do-like", function(event) {
        event.preventDefault();
        var btn = $(this);
        // var submitUrl = btn.data('url') ? btn.data('url') : "";
        var like = btn.data('like');
        var itemId = btn.data('itemid');
        var type = btn.data('type');

        if (likeUrl) {
            $.ajax({
                url: likeUrl,
                type: 'post',
                dataType: 'json',
                data: {like: like, id: itemId, type:type},
                beforeSend: function() {
                    $('.error,.submit_notification').html('');
                    $('.btn').attr("disabled", "disabled");
                },
                success: function(result) {
                    $('.error').html('');
                    $('.btn').removeAttr("disabled");
                    if (result.status == 200) {
                        if(like == '1'){
                            btn.data('like','0');
                            btn.find('.like-icon').removeClass('lar la-thumbs-up').addClass('las la-thumbs-down');
                            if(btn.find('span').length){
                                btn.find('span').text('Unlike');
                            }
                        }else{
                            btn.data('like','1');
                            btn.find('.like-icon').removeClass('las la-thumbs-down').addClass('lar la-thumbs-up');
                            if(btn.find('span').length){
                                btn.find('span').text('Like');
                            }
                        }
                    } else {
                        $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                    }
                },
                error: function(e) {
                    $('.btn').removeAttr("disabled");
                    $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                    $('.toast-notify').find('.toast-body').text("Something Went Wrong!... Please try again after refresh");
                    $('.toast-notify').toast('show');
                }
            });
        }
    });

    $('body').off('click', ".remove-img-btn").on('click', ".remove-img-btn", function(event) {
        var btn = $(this);
        btn.closest('.image-preview').find('img').attr("src", "");
        btn.closest('.image-preview').find('input').val('');
        btn.closest(".image-preview").find('.remove-img-btn').addClass('d-none');
    });

    $('body').off('click', ".delete-post-btn, .remove-post-img").on('click', ".delete-post-btn, .remove-post-img", function(event) {
        event.preventDefault();
        var btn = $(this);
        var submitUrl = btn.data('url') ? btn.data('url') : "";
        var id = btn.data('id');
        var type = btn.data('type');
        var data = {id: id};

        if (type == 'remove_image') {
            submitUrl = removePostImageUrl;
            data.item_id = btn.data('item');
            console.log(data);
        } else if (type == 'remove_post') {
            submitUrl = removePostUrl;
        }

        if (submitUrl) {
            bootbox.confirm({
                message: "Are you sure you want to delete this data?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (confirm) {
                    if (confirm) {
                        $.ajax({
                            url: submitUrl,
                            type: 'post',
                            dataType: 'json',
                            data: data,
                            beforeSend: function() {
                                $('.error,.submit_notification').html('');
                                $('.btn').attr("disabled", "disabled");
                            },
                            success: function(result) {
                                $('.error').html('');
                                $('.btn').removeAttr("disabled");
                                $('.toast-notify').find('.toast-body').text(result.message);
                                if (result.status == 200) {
                                    if (type == 'remove_image') {
                                        btn.closest('.image-preview').find('img').attr("src", "");
                                        btn.remove();
                                    } else if (type == 'remove_post') {
                                        btn.closest('.post-item').remove();
                                        location.reload();
                                    }
                                } else {
                                    $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                                }
                                $('.toast-notify').toast('show');
                            },
                            error: function(e) {
                                $('.btn').removeAttr("disabled");
                                $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                                $('.toast-notify').find('.toast-body').text("Something Went Wrong!... Please try again after refresh");
                                $('.toast-notify').toast('show');
                            }
                        });
                    }
                }
            });
        }
    });

    $('body').off('click', "#saveComment").on('click', "#saveComment", function(event) {
        event.preventDefault();
        var btn = $(this);
        var comment = $('#comment').val();
        var itemId  = btn.data('id');
        // if(!comment){
        //     $('#comment_error').html('Please enter comment before saving');
        //     return;
        // }
        if (commentUrl) {
            $.ajax({
                url: commentUrl,
                type: 'post',
                dataType: 'json',
                data: {comment: comment, id: itemId},
                beforeSend: function() {
                    $('.error,.submit_notification').html('');
                    btn.attr("disabled", "disabled");
                },
                success: function(result) {
                    $('.error').html('');
                    btn.removeAttr("disabled");
                    if (result.status == 200) {
                        $('.comment-list').prepend(result.tr);
                        $('.toast-notify').removeClass('bg-danger').addClass('bg-success');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                        $('#comment').val('');

                    } else if (result.status == 402) {
                        $.each(result.errors, function(i, val) {
                            if (val != "") {
                                $("#" + i + "_error").text(val);
                            }
                        });
                    } else {
                        $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                    }
                },
                error: function(e) {
                    btn.removeAttr("disabled");
                    $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                    $('.toast-notify').find('.toast-body').text("Something Went Wrong!... Please try again after refresh");
                    $('.toast-notify').toast('show');
                }
            });
        }
    });

    $('body').off('click', "#placeBid").on('click', "#placeBid", function(event) {
        event.preventDefault();
        var btn = $(this);
        var bid_amount = $('#bid_amount').val();
        var item_id  = btn.data('id');
        // if(!bid_amount){
        //     $('#bid_amount_error').html('Please enter bid amount before placing bid');
        //     return;
        // }
        if (bidUrl) {
            $.ajax({
                url: bidUrl,
                type: 'post',
                dataType: 'json',
                data: {bid_amount: bid_amount, item_id: item_id},
                beforeSend: function() {
                    $('.error,.submit_notification').html('');
                    btn.attr("disabled", "disabled");
                },
                success: function(result) {
                    $('.error').html('');
                    btn.removeAttr("disabled");
                    if (result.status == 200) {
                        $('.bidder-list').prepend(result.tr);
                        $('.toast-notify').removeClass('bg-danger').addClass('bg-success');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                        $('.min-bid-price span').text(bid_amount);
                        $('#bid_amount').val('');

                    } else if (result.status == 402) {
                        $.each(result.errors, function(i, val) {
                            if (val != "") {
                                $("#" + i + "_error").text(val);
                            }
                        });
                    } else {
                        $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                        $('.toast-notify').find('.toast-body').text(result.message);
                        $('.toast-notify').toast('show');
                    }
                },
                error: function(e) {
                    btn.removeAttr("disabled");
                    $('.toast-notify').removeClass('bg-success').addClass('bg-danger');
                    $('.toast-notify').find('.toast-body').text("Something Went Wrong!... Please try again after refresh");
                    $('.toast-notify').toast('show');
                }
            });
        }
    });

    $('[data-countdown]').each(function() {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            console.log('hello', event);
            if (event.type == 'finish') {
                $this.closest('label').find('.expire-timer').removeClass('d-none');
                $this.remove();
            } else {
                $this.html(event.strftime('%Dd %Hh %Mm %Ss'));
            }
        }).on('finish.countdown', function(event) {
            $this.closest('label').find('.expire-timer').removeClass('d-none');
            $this.remove();
        });
    });
});

function setLocale(lang) {
	if (lang == 'ar') {
        $('html').removeAttr('dir')
        $('html').attr("dir", "rtl")
    } else {
        $('html').removeAttr('dir')
        $('html').attr("dir", "ltr")
    }
}

function copyShareLink(textToCopy) {
    // navigator clipboard api needs a secure context (https)
    if (navigator.clipboard && window.isSecureContext) {
        // navigator clipboard api method'
        return navigator.clipboard.writeText(textToCopy);
    } else {
        // text area method
        let textArea = document.createElement("textarea");
        textArea.value = textToCopy;
        // make the textarea out of viewport
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        return new Promise((res, rej) => {
            // here the magic happens
            document.execCommand('copy') ? res() : rej();
            textArea.remove();
        });
    }
}

function countdown() {
    var bidTime = $(".bid-timer").data("time");
    console.log(bidTime);
    // Set the date we're counting down to
    var countDownDate = new Date(bidTime).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();

      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the result in the element with id="demo"
      document.getElementById("bid-timer").innerHTML = days + "d " + hours + "h "
      + minutes + "m " + seconds + "s ";

      // If the count down is finished, write some text
      if (distance < 0) {
        clearInterval(x);
        // document.getElementById("bid-timer").innerHTML = "EXPIRED";
        $("#bid-timer").remove();
        $("#expire-timer").removeClass('d-none');
        $("#bid-section").remove();
        $("#winner-section").removeClass('d-none');
      }
    }, 1000);
}