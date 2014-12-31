jQuery(function($) {
	trackEvents();
	// resetFilter();
	// var gallery = $(".gallery");
	// var divWidth = 0;
	// var debug = true;
	// var leftMax = "";
	// var rightMax = "";
	var resizeId;

    // Breakpoints, responsive design
    // ///////////////////////////////////////////////////////////////
	var breakXsmall = 25; //400px is 400/16 is 25 em
	var breakSmall = 30; //480px is 30 em
	var breakSmallToMedium = 36.75; //590 is 36.75 em
	var breakMedium = 48; //768px is 48 em
	// var breakLarge = 64; //1024px is 64 em590/1
	var breakLarge = 57.5; //920px is 60 em
	var breakWide = 70; //1120px is 70 em

	var windowWidth = viewportSize.getWidth(); //replaces buggy and unreliable $(window).width();
	var windowWidthEms = ((viewportSize.getWidth()) / 16);

	// Do these swaps and cutnpaste's before menuSearchCheck() swap figure in nodes if no .introduction present
	// long selector because for specificity. Only direct child of .main-content
	// Because there are .node-full's within .node-full's
	if ($(".main-content > .node-full .introduction").length < 1) {
		//there is no .introduction, make one from first <p>
		// if colukmn isert after meta
		if($(".node-column.node-full").length > 0) {
			$(".content-wrap p:first-child").insertAfter(".meta").addClass("introduction");
		}
		//move first <p> in first .node-full > content-wrap, insertAfter #page-title
		else {
			$(".main-content > .node-full > .content-wrap > p:first-child").insertAfter("#page-title").addClass("introduction");
		}
	}

	if($(".messages.error").length > 0) {
		// insrte text for webforms
		$("form .messages.error h2, .node-type-webform .messages.error h2 ").after('<h2>Let op:</h2><p>Het formulier is nog niet verzonden. Vul graag nog deze verplichte velden in:</p>');
		
		// move messges wrt form elements to in content as per design
		$('.node-type-webform main > .inner-wrap > .messages').insertBefore('.webform-client-form');	
	}

	// for styling sake and Drupal Schmupal's unbending caps add a wrapper to the search input and btn to make grey box on search page :\
	if($('body.page-search')) {
		$('#search-form #edit-basic .form-submit').insertAfter('#edit-keys');
		$('#edit-keys').next('.form-submit').andSelf().wrapAll('<div class="search-inner-wrap"/>'); 
	}

	// do initial checks for menu and search
	menuSearchCheck();

	// do some checks when window is resized
	$(window).resize(function() {
		if (viewportSize.getWidth() > 768) {		
			clearTimeout(resizeId);
	    	resizeId = setTimeout(menuSearchCheck, 25);
	    }
	});

	//toggle .search when hidden on small/mobile devices
	$(".toggle-search").on( "click", function(e) {
		e.preventDefault();
		var searchform = $("#search-block-form");
		var nav = $("header nav");
		if (searchform.hasClass("expanded")) {
			$(this).removeClass("toggle-active");
			searchform.slideUp().removeClass("expanded");
		}
		else if (!(searchform.hasClass("expanded"))) {
			if (nav.hasClass("expanded")) {
				nav.slideUp("slow", function() {
					nav.removeClass("expanded");
					$(".toggle-menu").removeClass("toggle-active");
					$(".toggle-search").addClass("toggle-active");
					searchform.slideDown().addClass("expanded");
				});				
			}
			else {
				$(this).addClass("toggle-active");
				searchform.slideDown().addClass("expanded");
			}
		}
	});

	// toggle the main menu when hidden on small mobile devices
	$(".toggle-menu").on( "click", function(e) {
		e.preventDefault();
		var nav = $("header nav");
		var searchform = $("#search-block-form");
		if (nav.hasClass("expanded")) {
			$(this).removeClass("toggle-active");
			nav.slideUp().removeClass("expanded");
		}
		else if (!(nav.hasClass("expanded"))) {
			if (searchform.hasClass("expanded")) {
				searchform.slideUp("slow", function() {
					searchform.removeClass("expanded");
					$(".toggle-search").removeClass("toggle-active");
					$(".toggle-menu").addClass("toggle-active");
					nav.slideDown().addClass("expanded");
				});
			}
			else {
				$(this).addClass("toggle-active");
				nav.slideDown().addClass("expanded");
			}
		}
	});

	// add/remove class to search input to clear image when filled
	if ($('#search-form .form-text')) {
		if ($('#search-form .form-text').val()) {
			$('#search-form .form-text').addClass('active');
		}
	}
	$('#search-block-form .form-text, #search-form .form-text').blur(function() {
	    if ($(this).val()) {
	        $(this).addClass('active');
	    }
	    if (!$(this).val()) {
	        $(this).removeClass('active');
	    }
	});

	//page-search move result count to right after label, because design...
	if($(".page-search").length > 0) {
		$(".search-result-count").insertAfter($("#search-form .form-item-keys label"));
		$(".form-item-keys + .form-submit").insertAfter($("#search-form .form-item-keys input.text"))
	}

// ////////////////////////////////////////////////////////////////////////////
// ////////////////////////////////////////////////////////////////////////////

	// SOME NEW HIDES!
	$(".js .commerce-line-item-actions #edit-submit").hide();

// ////////////////////////////////////////////////////////////////////////////

	$(".node-detail-page .expandable .content").hide();
	$("#wrapper a[href^='http']").not("#wrapper a[href*='.transplantatiestichting.nl'], #wrapper .download-btn a").attr('target','_blank');
	$(".field-field-download-file .file a").text('Downloaden');
	
	//06-11-2012 Added Cookiebalk S. Bonardt - Two Kings        
	$(function(){
		var cookie = readCookie('cookiewet');
		if ((typeof cookie == 'undefined') || (cookie == null)) {
			addCookieWetHtml('www.transplantatiestichting.nl', '/cookiebeleid');
		}
	});

	// toggle tables
	$(".expandable h2 .toggle-table").click(function(e) {
		e.preventDefault();
		$(this).parent().toggleClass("active");
		$(this).parents(".expandable").find(".content").slideToggle();
	});       

	// select all for copy
	$(".iframe-option textarea").focus(function() {
		var $this = $(this);
		$this.select();

	// Work around Chrome's little problem
	$this.mouseup(function() {
	// Prevent further mouseup intervention
	$this.unbind("mouseup");
		return false;
	});
	});

	// S. Bonardt 24-04-2014
	// Sorry commerce download-only solution. Hide add to cart if download_only equals 1 (checkbox checked)
	// also do this when product variation selectbox Ajax has fired for selecting a product variation
	checkProductDownloadStatus();
	$( document ).ajaxComplete(function( event,request, settings ) {
		checkProductDownloadStatus();
	});

	// cart form things
	// update cart on contents change
	$('#views-form-commerce-cart-form-default .form-type-textfield input').blur(function() {
		$('#edit-submit').mousedown();
	});

	$(".carousel-container ").css("opacity","0");

	$(".form-heading a").mouseover(function(e) {
		var parentOffset = $(this).parent().offset();
		var xpos = (e.pageX - 140) - parentOffset.left;
		$(".drop").css("left", xpos);
		$(".drop").css("display", "block");
	}).mouseleave(function() {
		$(".drop").css("display", "");
	}); 

	// bind click to expandable block with submenu items (e.g shopping cart/categories, etc.)
	$('.shopping-cart h2, .categories h2').on("click", function() {
		// the clickable h2 is directly below the block container div
		var container = $(this).parent();
		if (container.hasClass("active")) {
			container.removeClass("active");
		}
		else {
			container.addClass("active");
		}
	});

	//toggling for search and menu on mobile
	function menuSearchCheck() {
		//update window width
		windowWidth = viewportSize.getWidth(); // $(window).width();
		windowWidthEms = (viewportSize.getWidth()) / 16;

		// do various show n hides for menu's, search and .main-image based on viewport width
		if ( windowWidthEms <= breakSmall) {
			$("#search-block-form").hide();	
			$(".toggle-search").show();	
		}
		else {
			$("#search-block-form").show();
			$(".toggle-search").hide();	
		}

		if ( windowWidthEms <= breakSmallToMedium) {
			//copy the secondary menu to just before footer for design purposes, only when mobile
			if ($("body > .secondary-menu").length == 0 ) {
				$(".secondary-menu").insertBefore("body > footer");
			}
		}
		else {
			// copy the secondary menu back to header if not in the <header> already
			if ($("header .secondary-menu").length == 0 ) {
				$(".secondary-menu").appendTo("body > header > .inner-wrap");
			}
		}

		if ( windowWidthEms <= 39.375) {
			// copy the carousel overlay image src to src
			$('.front .homepage-theme .overlay').attr("src", "/sites/all/themes/nederlandsetrans/images/car-placeholder.gif");
			//console.log(imageSrc);
		}
		else {
// copy the carousel overlay image src to src
			var imageSrc = $('.front .homepage-theme .file-image > .content').html();
			$('.front .homepage-theme .overlay').attr("src", imageSrc);
			
			// //check for the widest child element of theme-introduction, set that as theme-introduction width
			// //set default width to
			// var widestChildElement = 400;
			// //console.log(widestChildElement);
			// $('.homepage-theme .theme-introduction > div').children().each(function() {
			// 	if ($(this).outerWidth(true) > widestChildElement) {
			// 		widestChildElement = $(this).outerWidth(true);
			// 	};
			// });

			// if (widestChildElement > 400) {
			// 	$('.homepage-theme .theme-introduction').css('width', widestChildElement);
			// }33.75

			//copy the height from theme-introduction-text (on the homepage theme-header block) and paste to theme-intro-bg
			var themeTextHeight = $('.theme-introduction').height();
			//console.log(themeTextHeight);
			$('.theme-introduction-bg > div').css('height', themeTextHeight);
		}

		if ( windowWidthEms < breakMedium) {
			// nothing yet...
		}
		else {
			// nothing yet...
		}

		// for frontpage large layout at 51ems and up, layout.scss r.114, blocks.scss r.376
		if ( windowWidthEms < 51) {
			$('.report-donor-wide').insertBefore('.front .news-listing-home');
		}
		else {
			$('.report-donor-wide').prependTo('.front main .inner-wrap > .home-sidebar');
		}


		if ( windowWidthEms < breakLarge) {
			$("nav").hide();	
			//copy search to div class search in header
			$("#search-block-form").appendTo('body > header > .search');

			// copy submenu from ASIDE to right place on mobile(S)
			// if there is a #page-title immediately, insert after title, else....
			if (($(".node-full > #page-title:first-child").length > 0) || ($(".node-full > time + #page-title").length > 0)) {
				$(".sub-menu-block").insertAfter("#page-title");
			}
			// else put it at the start of node-full, because this is a special page with title below
			// else {
			// 	$(".sub-menu-block").prependTo(".node-full");
			// }
			// make sure aside is on level, without extra top margin
			$("main > aside").css("margin-top", 0);

			// theme_page highlighted block
			// after the submenu has been moved, move the highlight block in place, just after the submenu
			// check if we're on theme_page and do it!
			if ($("body.node-type-theme-page").length > 0) {
				$(".theme-page-highlighted").insertAfter(".sub-menu-block");
			}

			$("aside > .shopping-cart").insertBefore('#page-title');
			$("aside > .categories").insertBefore('#page-title');
		}
		else {
			$("nav").show();	
			// put submenu back in the ASIDE if we're scaling/sliding up from mobile/small viewports
			// and find the sub-menu-block under .main-content
			if ($(".main-content .sub-menu-block").length > 0 ) {
				$(".sub-menu-block").prependTo("main .inner-wrap > aside");
			}

			$("#search-block-form").insertBefore('body > header .secondary-menu');

			$(".shopping-cart").prependTo('aside');
			$(".block.categories").prependTo('aside');

			// offset the main aside so that it does not get overlapped with H1 and intro
			// calculate offset
			var asideOffset = calculateAsideOffset();
			$("main .inner-wrap > aside").css("margin-top", asideOffset);
			//copy data-src attribute of .main-image element in the <header> to src attribute
			$(".main-image").attr("src", $(".main-image").attr("data-src"));

			// theme_page highlighted block
			// move highlight block back in place, because we're on large screens and this needs to be back as highlighted and not between content
			// check if we're on theme_page and do it!
			if ($("body.node-type-theme-page").length > 0) {
				$(".theme-page-highlighted").prependTo(".highlighted");
			}

			//copy the height from theme-introduction-text (on the homepage theme-header block) and paste to theme-intro-bg
			var themeTextHeight = $('.theme-introduction').height();
			//console.log(themeTextHeight);
			$('.theme-introduction-bg > div').css('height', themeTextHeight + 50);

			// if ($(".columns-listing").length > 0) {
			// 	var heightArticles = new Array();
			// 	$(".columns-listing > article > a").each(function() {
			// 		heightArticles[heightArticles.length] = $(this).height();
			// 	});
			// 	var highestArticle = Math.max.apply(Math, heightArticles); 				
			// 	$(".columns-listing > article > a").height(highestArticle);
			// }
			
			// this is not needed anymore
			// if ($(".node-full").length > 0) {
			// 	// copy data-src attr for figure under node-full to src attr
			// 	var nodeFullFigure = $(".node-full > figure img");
			// 	nodeFullFigure.attr("src", (nodeFullFigure.attr("data-src")));
			// 	// fix for temp issue with introductions. If intro field is not filled use first paragraph
			// 	// but then we need to put the figure element in node-full view after the first <p>
			// }
		}
	}


}); //end JQuery wrapper function

// ///////////////////////////////////////////////////////////////////////////////// 
// // ////////////////////////////                        )     ) (       )        (     
// // ////////////////////////////   *   ) (  (     ( /(  ( /( )\ ) ( /( (      )\ )  
// // //////////////////////////// ` )  /( )\))(   ')\()) )\()|()/( )\()))\ )  (()/(  
// // ////////////////////////////  ( )(_)|(_)()\ )((_)\|((_)\ /(_)|(_)\(()/(   /(_)) 
// // //////////////////////////// (_(_())_(())\_)() ((_)_ ((_|_))  _((_)/(_))_(_))   
// // //////////////////////////// |_   _|\ \((_)/ // _ \ |/ /|_ _|| \| (_)) __/ __|  
// // ////////////////////////////   | |   \ \/\/ /| (_) |' <  | | | .` | | (_ \__ \  
// // ////////////////////////////   |_|    \_/\_/  \___/_|\_\|___||_|\_|  \___|___/  
// // ////////////////////////////
// /////////////////////////////////////////////////////////////////////////////////

/* nieuwe functies herbouw */
		
	function calculateAsideOffset() {
		var introductionOffset = 0;
		var firstParagraphOffset = 0;
		var pageTitleHeight = 0;
		var totalAsideOffset = 0;

		/* there (almost) always a page-title. Set that as minimum offset for Aside */
		var pageTitle = $("#page-title");
		if (pageTitle.length > 0) {
			var pageTitleHeight = pageTitle.outerHeight(true);
			pageTitleHeight = parseInt(pageTitleHeight);
			totalAsideOffset = pageTitleHeight;
		}
		/* get position of the introduction paragraph if exists. Set offset to Y position 
		of introduction + introduction height */
		if ($(".node-full .introduction").length > 0) {
			var introductionParagraph = $(".node-full .introduction");
			var introductionHeight = introductionParagraph.outerHeight(true);
			totalAsideOffset = parseInt(pageTitleHeight + introductionHeight);
		}
		/* else get position of the first paragraph if exists. Set offset to Y position 
		of first paragraph + introduction height */
		else if ($(".node-full .content-wrap p:first-child")) {
			var firstParagraph = $(".node-full .content-wrap p:first-child");
			var firstParagraphHeight = firstParagraph.outerHeight(true);

			totalAsideOffset = parseInt(pageTitleHeight + firstParagraphHeight);
		}

		return totalAsideOffset;
	}


/* ////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////// */
/* ////////////////////////////////////////////////////////// */



// S. Bonardt 24-04-2014
// Sorry @$$ commerce download-only solution. Hide add to cart if download_only equals 1 (checkbox checked)
// also do this when product variation selectbox Ajax has fired for selecting a product variation
function checkProductDownloadStatus() {
	$('.commerce-product-field-field-download-only').each(function() {
		var downloadOnlyValue = $(this).text();
		//console.log(this);
		if (downloadOnlyValue != " ") {
			if (downloadOnlyValue == "0") {
				$(this).next().find('.form-submit').removeAttr("disabled", "disabled").removeClass("disabled");
				$(this).parents('.node-product').removeClass('node-product-download-only');
			}
			if (downloadOnlyValue == "1") {
				$(this).next().find('.form-submit').attr("disabled", "disabled").addClass("disabled");
				$(this).parents('.node-product').addClass('node-product-download-only');
			}
		}
	});
	$(".field-field-download-file .file a").text('download');
}

// 06-11-2012 Cookiebalk added S. Bonardt - Two Kings
function addCookieWetHtml(name, link) {
	$('body').append('<div class="cookiewet"><div class="cookiewet-bg"></div><p class="cookiewet-description">Deze website maakt gebruik van cookies. Lees <a href="' + link + '">hier</a> waarom. <a href="#" id="cookiewet-ok" class="cookiewet-btn accept">Sluiten</a></p></div>');

	var original_margin_bottom = parseInt($('body').css('margin-bottom'));
	var margin_bottom = original_margin_bottom + $('body div.cookiewet').outerHeight();
	$('.cookiewet-btn').bind('click', function(e){
		e.preventDefault();
		createCookie('cookiewet', 'true',365);
		$('div.cookiewet').hide();
		$('body').css('margin-bottom', original_margin_bottom);
	});
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}	

function addToggleForNumbers() {	
	$(".numberTableHeader").click(function() {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');
		}
		$(this).parent().next().slideToggle("slow", "swing");
	});
}

// function checkMultiLineListItem() {
// 	$("li.active a.active").each(function() {
// 		switch ($(this).height()) {
// 			case 30:
// 			$('<div class="singleLine"></div>').insertBefore($(this));
// 			$(this).appendTo(".singleLine");
// 			break;
// 			case 60:
// 			$('<div class="doubleLine"></div>').insertBefore($(this));
// 			$(this).appendTo(".doubleLine");
// 			break;
// 		}
// 	});
// }

	function trackEvents() {
		$("a[href$='.pdf']").click(function() {
			var url = $(this).attr("href");
			_gaq.push(['_trackEvent', 'Downloads', 'PDF', url]);
		});
		$("a[href^='http']").click(function() {
			var url = $(this).attr("href");
			_gaq.push(['_trackPageview'], 'External link');
	//console.log('tracked');
	});
}

// function resetFilter() {
// 	$('.page-vraag-en-antwoord .side-form input, .page-vraag-en-antwoord .side-form .facetapi-checkbox-processed, a.wis-filter').click(function() {
// 		$('.filter-overlay').css('display', 'block');
// 	});
// }

if (!window.$) {
	window.$ = jQuery;
}