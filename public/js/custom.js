(function ($) {
	"use strict";
  
	// Collapse navbar on item click
	$('.navbar-collapse a').on('click', function () {
	  $(".navbar-collapse").collapse('hide');
	});
  
	// Initialize all carousels on document ready
	$(document).ready(function () {
	  initCarousel(".hotelslist", {
		loop: false,
		margin: 30,
		nav: true,
		responsive: {
		  0: { items: 1 },
		  767: { items: 2 },
		  990: { items: 3 },
		  1170: { items: 4 }
		}
	  });
  
	  initCarousel(".testimonialsList", {
		loop: true,
		margin: 30,
		nav: false,
		responsive: {
		  0: { items: 1 },
		  700: { items: 1 },
		  1170: { items: 2, nav: true }
		}
	  });
  
	  initCarousel(".blogGrid", {
		loop: true,
		margin: 30,
		nav: false,
		responsive: {
		  0: { items: 1 },
		  700: { items: 2 },
		  1170: { items: 3, nav: true }
		}
	  });
  
	  initCarousel(".owl-clients", {
		loop: true,
		margin: 30,
		nav: false,
		responsive: {
		  0: { items: 2 },
		  700: { items: 4 },
		  1170: { items: 5, nav: true }
		}
	  });
  
	  // Main Gallery
	  initCarousel(".galleryview", {
		loop: false,
		margin: 30,
		nav: false,
		navText: [
		  '<i class="fas fa-chevron-left"></i>',
		  '<i class="fas fa-chevron-right"></i>'
		],
		responsive: {
		  0: { items: 1 },
		  700: { items: 1 },
		  1170: { items: 1, nav: true }
		}
	  });
  
	  // Thumbnails
	  initCarousel(".gallery-thumbnails", {
		items: 5,
		loop: false,
		margin: 10,
		nav: true,
		dots: false,
		navText: [
		  '<i class="fas fa-chevron-left"></i>',
		  '<i class="fas fa-chevron-right"></i>'
		],
		responsive: {
		  0: { items: 2 },
		  600: { items: 4 },
		  1000: { items: 5 }
		}
	  });
  
	  // Sync Gallery
	  $(".galleryview").on('changed.owl.carousel', function (event) {
		const index = event.item.index;
		$(".gallery-thumbnails .owl-item").removeClass('current')
		  .eq(index).addClass('current');
	  });
  
	  $(".gallery-thumbnails").on('click', '.owl-item', function () {
		const index = $(this).index();
		$(".galleryview").trigger('to.owl.carousel', [index, 300]);
	  });
	});
  
	// Revolution Slider Initialization
	if ($('.tp-banner').length) {
	  $('.tp-banner').show().revolution({
		delay: 6000,
		startheight: 700,
		startwidth: 1170,
		hideThumbs: 1000,
		navigationType: 'none',
		touchenabled: 'on',
		onHoverStop: 'on',
		fullWidth: 'on'
	  });
	}
  
	// Helper to initialize carousels
	function initCarousel(selector, options) {
	  if ($(selector).length) {
		// Check if the page is RTL - multiple detection methods
		const isRTL = $('html').attr('dir') === 'rtl' || 
					  $('body').hasClass('rtl') || 
					  $('html').attr('lang') === 'ar' ||
					  document.documentElement.dir === 'rtl' ||
					  document.body.dir === 'rtl';
		
		// Debug RTL detection (remove in production)
		console.log('RTL Detection for', selector, ':', {
			htmlDir: $('html').attr('dir'),
			bodyClass: $('body').hasClass('rtl'),
			htmlLang: $('html').attr('lang'),
			docDir: document.documentElement.dir,
			bodyDir: document.body.dir,
			isRTL: isRTL
		});
		
		// Add RTL support to options
		const rtlOptions = {
		  ...options,
		  rtl: isRTL,
		  // Fix RTL navigation arrows
		  navText: isRTL ? [
			'<i class="fa fa-arrow-right" aria-hidden="true"></i>',
			'<i class="fa fa-arrow-left" aria-hidden="true"></i>'
		  ] : options.navText || [
			'<i class="fa fa-arrow-left" aria-hidden="true"></i>',
			'<i class="fa fa-arrow-right" aria-hidden="true"></i>'
		  ]
		};
		
		$(selector).owlCarousel(rtlOptions);
		
		// Fix RTL positioning issues
		if (isRTL) {
		  $(selector).on('initialized.owl.carousel', function() {
			// Ensure carousel is properly positioned for RTL
			$(this).attr('dir', 'rtl').css('direction', 'rtl');
			$(this).find('.owl-stage-outer').attr('dir', 'rtl').css('direction', 'rtl');
			$(this).find('.owl-stage').attr('dir', 'rtl').css('direction', 'rtl');
			$(this).find('.owl-item').attr('dir', 'rtl').css('direction', 'rtl');
			
			// Force reflow to ensure changes take effect
			$(this)[0].offsetHeight;
		  });
		  
		  // Also apply RTL attributes immediately after carousel creation
		  setTimeout(function() {
			$(selector).attr('dir', 'rtl').css('direction', 'rtl');
			$(selector).find('.owl-stage-outer').attr('dir', 'rtl').css('direction', 'rtl');
			$(selector).find('.owl-stage').attr('dir', 'rtl').css('direction', 'rtl');
			$(selector).find('.owl-item').attr('dir', 'rtl').css('direction', 'rtl');
		  }, 100);
		}
	  }
	}
	
	// Manual RTL trigger function for language switching
	window.applyRTLToCarousels = function() {
	  $('.owl-carousel').each(function() {
		const $carousel = $(this);
		const isRTL = $('html').attr('dir') === 'rtl' || 
					  $('body').hasClass('rtl') || 
					  $('html').attr('lang') === 'ar' ||
					  document.documentElement.dir === 'rtl' ||
					  document.body.dir === 'rtl';
		
		if (isRTL) {
		  $carousel.attr('dir', 'rtl').css('direction', 'rtl');
		  $carousel.find('.owl-stage-outer').attr('dir', 'rtl').css('direction', 'rtl');
		  $carousel.find('.owl-stage').attr('dir', 'rtl').css('direction', 'rtl');
		  $carousel.find('.owl-item').attr('dir', 'rtl').css('direction', 'rtl');
		  
		  // Reinitialize carousel with RTL options
		  const currentOptions = $carousel.data('owl.carousel').options;
		  currentOptions.rtl = true;
		  $carousel.trigger('destroy.owl.carousel');
		  $carousel.owlCarousel(currentOptions);
		}
	  });
	};
  
  })(jQuery);
  
  
  // ---------------------------
  // Counter Animation
  // ---------------------------
  document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.counter-number').forEach(counter => {
	  const from = parseInt(counter.dataset.from);
	  const to = parseInt(counter.dataset.to);
	  const speed = parseInt(counter.dataset.speed);
  
	  let current = from;
	  const interval = Math.max(Math.floor(speed / (to - from)), 1);
	  const timer = setInterval(() => {
		current += 1;
		counter.textContent = current;
		if (current >= to) {
		  clearInterval(timer);
		  counter.textContent = to;
		}
	  }, interval);
	});
  });
  
  
  // ---------------------------
  // Typewriter Effect
  // ---------------------------
  document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.typewrite').forEach(el => {
	  const toRotate = JSON.parse(el.dataset.type || "[]");
	  const period = parseInt(el.dataset.period || 2000);
	  if (toRotate.length) {
		new TxtType(el, toRotate, period);
	  }
	});
  });
  
  function TxtType(el, toRotate, period) {
	this.toRotate = toRotate;
	this.el = el;
	this.loopNum = 0;
	this.period = period;
	this.txt = '';
	this.isDeleting = false;
	this.tick();
  }
  
  TxtType.prototype.tick = function () {
	const i = this.loopNum % this.toRotate.length;
	const fullTxt = this.toRotate[i];
  
	this.txt = this.isDeleting
	  ? fullTxt.substring(0, this.txt.length - 1)
	  : fullTxt.substring(0, this.txt.length + 1);
  
	const wrap = this.el.querySelector('.wrap');
	if (wrap) wrap.textContent = this.txt;
  
	let delta = 200 - Math.random() * 100;
	if (this.isDeleting) delta /= 2;
  
	if (!this.isDeleting && this.txt === fullTxt) {
	  delta = this.period;
	  this.isDeleting = true;
	} else if (this.isDeleting && this.txt === '') {
	  this.isDeleting = false;
	  this.loopNum++;
	  delta = 500;
	}
  
	setTimeout(() => this.tick(), delta);
  };
  
  
  // ---------------------------
  // Range Slider Sync with Input
  // ---------------------------
  const rangeInputs = document.querySelectorAll(".range-input input");
  const priceInputs = document.querySelectorAll(".price-input input");
  const range = document.querySelector(".slider .progress");
  
  const priceGap = 100;
  const minPriceLimit = 10;
  const maxPriceLimit = 10000;
  
  priceInputs.forEach(input => {
	input.addEventListener("input", (e) => {
	  let min = parseInt(priceInputs[0].value);
	  let max = parseInt(priceInputs[1].value);
  
	  if (max - min >= priceGap && max <= maxPriceLimit && min >= minPriceLimit) {
		if (e.target.classList.contains("input-min")) {
		  rangeInputs[0].value = min;
		} else {
		  rangeInputs[1].value = max;
		}
		updateSlider();
	  }
	});
  });
  
  function updateSlider() {
	const minVal = parseInt(rangeInputs[0].value);
	const maxVal = parseInt(rangeInputs[1].value);
	const sliderMax = parseInt(rangeInputs[1].max);
  
	if (range) {
	  range.style.left = ((minVal / sliderMax) * 100) + "%";
	  range.style.right = (100 - (maxVal / sliderMax) * 100) + "%";
	}
  
	priceInputs[0].value = minVal;
	priceInputs[1].value = maxVal;
  }
  