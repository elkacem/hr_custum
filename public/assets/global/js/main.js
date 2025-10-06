(function ($) {
	"user strict";
	// Preloader Js
	$(window).on('load', function () {
		$('.preloader').fadeOut(1000);
		var img = $('.bg_img');
		img.css('background-image', function () {
			var bg = ('url(' + $(this).data('background') + ')');
			return bg;
		});
		bgControl();
	});


	function bgControl() {
		var prevBg = $('.book-section');
		if (prevBg.hasClass('bg--section')) {
			prevBg.next().addClass('pt-120')
			if (prevBg.next().hasClass('bg--section')) {
				prevBg.next().addClass('pt-0')
			}
		}

		var footerBg = $('.footer-section');
		if (footerBg.prev().hasClass('bg--section')) {} else {
			footerBg.addClass('bg--section')
			$('.footer__bottom').removeClass('bg--section')
			$('.footer__bottom').addClass('bg--body')
		}
	}


	$("ul>li>.submenu").parent("li").addClass("menu-item-has-children");

	$('ul').parent('li').hover(function () {
		var menu = $(this).find("ul");
		var menupos = $(menu).offset();
		if (menupos.left + menu.width() > $(window).width()) {
			var newpos = -$(menu).width();
			menu.css({
				left: newpos
			});
		}
	});


	$('.menu li a').on('click', function (e) {
		var element = $(this).parent('li');
		if (element.hasClass('open')) {
			element.removeClass('open');
			element.find('li').removeClass('open');
			element.find('ul').slideUp(300, "swing");
		} else {
			element.addClass('open');
			element.children('ul').slideDown(300, "swing");
			element.siblings('li').children('ul').slideUp(300, "swing");
			element.siblings('li').removeClass('open');
			element.siblings('li').find('li').removeClass('open');
			element.siblings('li').find('ul').slideUp(300, "swing");
		}
	})


	var scrollTop = $(".scrollToTop");
	$(window).on('scroll', function () {
		if ($(this).scrollTop() < 500) {
			scrollTop.removeClass("active");
		} else {
			scrollTop.addClass("active");
		}
	});


	$('.header-bar').on('click', function () {
		$(this).toggleClass('active');
		menu = $('.menu');
		if (menu.hasClass('active')) {
			$('.menu').removeClass('active');
			$('.overlay').removeClass('active');
		} else {
			$('.menu').addClass('active');
			$('.overlay').addClass('active');
		}
	})


	$('.overlay').on('click', function () {
		$(this).removeClass('active');
		menu = $('.menu');
		$('.menu').removeClass('active');
		$('.header-bar').removeClass('active');
		$('.category-sidebar').removeClass('active');
	})

	var headerSticky = $(".header-bottom");
	$(window).on('scroll', function () {
		if ($(this).scrollTop() < 1) {
			headerSticky.removeClass("active");
		} else {
			headerSticky.addClass("active");
		}
	});


	$('.faq__wrapper .faq__title').on('click', function (e) {
		var element = $(this).parent('.faq__item');
		if (element.hasClass('open')) {
			element.removeClass('open');
			element.find('.faq__content').removeClass('open');
			element.find('.faq__content').slideUp(400, "swing");
		} else {
			element.addClass('open');
			element.children('.faq__content').slideDown(400, "swing");
			element.siblings('.faq__item').children('.faq__content').slideUp(400, "swing");
			element.siblings('.faq__item').removeClass('open');
			element.siblings('.faq__item').find('.faq__title').removeClass('open');
			element.siblings('.faq__item').find('.faq__content').slideUp(400, "swing");
		}
	});


	$('.client-slider').owlCarousel({
		loop: true,
		nav: false,
		dots: true,
		items: 1,
		autoplay: true,
		margin: 30,
		responsive: {
			992: {
				items: 2
			}
		}
	})


	$('.banner-slider').owlCarousel({
		loop: true,
		nav: false,
		dots: true,
		items: 1,
		margin: 30,
		autoHeight: true,
	})


	var sync1 = $(".sync1");
	var sync2 = $(".sync2");
	var thumbnailItemClass = '.owl-item';
	var slides = sync1.owlCarousel({
		items: 1,
		autoplay: true,
		loop: true,
		margin: 30,
		mouseDrag: false,
		touchDrag: true,
		pullDrag: false,
		scrollPerPage: true,
		nav: false,
		dots: false,
	}).on('changed.owl.carousel', syncPosition);

	function syncPosition(el) {
		$owl_slider = $(this).data('owl.carousel');
		var loop = $owl_slider.options.loop;

		if (loop) {
			var count = el.item.count - 1;
			var current = Math.round(el.item.index - (el.item.count / 2) - .5);
			if (current < 0) {
				current = count;
			}
			if (current > count) {
				current = 0;
			}
		} else {
			var current = el.item.index;
		}

		var owl_thumbnail = sync2.data('owl.carousel');
		var itemClass = "." + owl_thumbnail.options.itemClass;

		var thumbnailCurrentItem = sync2
			.find(itemClass)
			.removeClass("synced")
			.eq(current);
		thumbnailCurrentItem.addClass('synced');

		if (!thumbnailCurrentItem.hasClass('active')) {
			var duration = 500;
			sync2.trigger('to.owl.carousel', [current, duration, true]);
		}
	}
	var thumbs = sync2.owlCarousel({
			items: 2,
			loop: false,
			margin: 20,
			nav: false,
			dots: true,
			autoplay: true,
			responsive: {
				500: {
					items: 3,
				},
				768: {
					items: 4,
				},
				992: {
					items: 5,
				},
			},
			onInitialized: function (e) {
				var thumbnailCurrentItem = $(e.target).find(thumbnailItemClass).eq(this._current);
				thumbnailCurrentItem.addClass('synced');
			},
		})
		.on('click', thumbnailItemClass, function (e) {
			e.preventDefault();
			var duration = 500;
			var itemIndex = $(e.target).parents(thumbnailItemClass).index();
			sync1.trigger('to.owl.carousel', [itemIndex, duration, true]);
		}).on("changed.owl.carousel", function (el) {
			var number = el.item.index;
			$owl_slider = sync1.data('owl.carousel');
			$owl_slider.to(number, 500, true);
		});
	sync1.owlCarousel();
	$(".owl-prev").html('<i class="las la-angle-left"></i>');
	$(".owl-next").html('<i class="las la-angle-right"></i>');




	$('.filter-in').on('click', function () {
		$('.category-sidebar').addClass('active');
		$('.overlay').addClass('active');
	})
	$('.close-sidebar').on('click', function () {
		$('.category-sidebar').removeClass('active');
		$('.overlay').removeClass('active');
	});


	(function () {
		var sync1 = $(".slider-top");
		var sync2 = $(".slider-bottom");
		var thumbnailItemClass = '.owl-item';
		var slides = sync1.owlCarousel({
			items: 1,
			autoplay: false,
			loop: true,
			margin: 30,
			mouseDrag: false,
			touchDrag: true,
			pullDrag: false,
			scrollPerPage: true,
			nav: false,
			dots: false,
		}).on('changed.owl.carousel', syncPosition);

		function syncPosition(el) {
			$owl_slider = $(this).data('owl.carousel');
			var loop = $owl_slider.options.loop;

			if (loop) {
				var count = el.item.count - 1;
				var current = Math.round(el.item.index - (el.item.count / 2) - .5);
				if (current < 0) {
					current = count;
				}
				if (current > count) {
					current = 0;
				}
			} else {
				var current = el.item.index;
			}

			var owl_thumbnail = sync2.data('owl.carousel');
			var itemClass = "." + owl_thumbnail.options.itemClass;

			var thumbnailCurrentItem = sync2
				.find(itemClass)
				.removeClass("synced")
				.eq(current);
			thumbnailCurrentItem.addClass('synced');

			if (!thumbnailCurrentItem.hasClass('active')) {
				var duration = 500;
				sync2.trigger('to.owl.carousel', [current, duration, true]);
			}
		}
		var thumbs = sync2.owlCarousel({
				items: 2,
				loop: false,
				margin: 20,
				nav: false,
				dots: true,
				autoplay: false,
				responsive: {
					500: {
						items: 3,
					},
				},
				onInitialized: function (e) {
					var thumbnailCurrentItem = $(e.target).find(thumbnailItemClass).eq(this._current);
					thumbnailCurrentItem.addClass('synced');
				},
			})
			.on('click', thumbnailItemClass, function (e) {
				e.preventDefault();
				var duration = 500;
				var itemIndex = $(e.target).parents(thumbnailItemClass).index();
				sync1.trigger('to.owl.carousel', [itemIndex, duration, true]);
			}).on("changed.owl.carousel", function (el) {
				var number = el.item.index;
				$owl_slider = sync1.data('owl.carousel');
				$owl_slider.to(number, 500, true);
			});
		sync1.owlCarousel();
		$(".owl-prev").html('<i class="las la-angle-left"></i>');
		$(".owl-next").html('<i class="las la-angle-right"></i>');
	})()

	$('.popup').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false,
		disableOn: 300
	});

	$("body").each(function () {
		$(this).find(".img-pop").magnificPopup({
			type: "image",
			gallery: {
				enabled: true
			}
		});
	});

	$('.custom-tab ul.tab-menu li').on('mouseover', function (g) {
		var tab = $(this).closest('.custom-tab'),
			index = $(this).closest('li').index();
		tab.find('li').siblings('li').removeClass('active');
		$(this).closest('li').addClass('active');
		tab.find('.tab-area').find('div.tab-item').not('div.tab-item:eq(' + index + ')').hide();
		tab.find('.tab-area').find('div.tab-item:eq(' + index + ')').show();
		g.preventDefault();
	});


	//required
	$.each($('input, select, textarea'), function (i, element) {
		if (element.hasAttribute('required')) {
			$(element).closest('.form-group').find('label').first().addClass('required');
		}

	});

	//data-label of table-dynamic//
	Array.from(document.querySelectorAll('table')).forEach(table => {
		let heading = table.querySelectorAll('thead tr th');
		Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
			Array.from(row.querySelectorAll('td')).forEach((column, i) => {
				(column.colSpan == 100) || column.setAttribute('data-label', heading[i].innerText)
			});
		});
	});


	$.each($('.select2'), function () {
		$(this)
			.wrap(`<div class="position-relative"></div>`)
			.select2({
				dropdownParent: $(this).parent()
			});
	});

	$.each($('.select2-auto-tokenize'), function () {
		$(this)
			.wrap(`<div class="position-relative"></div>`)
			.select2({
				tags: true,
				tokenSeparators: [','],
				dropdownParent: $(this).parent()
			});
	});


	$('.select2').select2();

	$("#confirmationModal").addClass('custom--modal');

})(jQuery);
