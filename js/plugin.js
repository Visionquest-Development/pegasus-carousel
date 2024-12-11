
	(function( $ ) {

		$('.quotes').slick({
		  dots: true,
		  infinite: true,
		  autoplay: true,
		  autoplaySpeed: 6000,
		  speed: 800,
		  slidesToShow: 1,
		  adaptiveHeight: true
		});

		/*
		To prevent the flashing of unstyled content (FOUC) I created a class ".no-fouc"
		which hides the slider.  When everything is ready to roll, I simply remove the
		.no-fouc class from the slider section using the script below.  I placed the CSS snippet in the head of the HTML
		page so that it's loaded before other things for obvious reasons.  What about folks with JS turned off?
		Well, I don't worry about them too much anymore.  Oh my.  I feel the heat from the flames already.  :)
		*/

		$( document ).ready(function() {
			$('.no-fouc').removeClass('no-fouc');
		});


		$('.logo-slider.center').slick({
		  centerMode: true,
		  centerPadding: '60px',
		  slidesToShow: 5,
		  autoplay: true,
		  autoplaySpeed: 6000,
		  speed: 800,
		  responsive: [
			{
			  breakpoint: 768,
			  settings: {
				arrows: true,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 3
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				arrows: true,
				centerMode: true,
				centerPadding: '40px',
				slidesToShow: 1
			  }
			}
		  ]
		});

		$('.logo-slider .slick-slide img').matchHeight();
		$('.logo-slider .slick-slide .slick-p').matchHeight();
		//$('.testimonial-slider article').matchHeight();
		$('.slippry-slider-container img').matchHeight();


	})( jQuery );
