/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */
/** ----------------------------
+ iamrobert add /remove classes
----------------------------**/
(function( $ ){
 
  $(document).ready(function(){

		// IAMROBERT TEMPLATE
		$('.fcsep_level0').parents('.controls').removeClass('controls');
		$('.fcsep_level1').parents('.controls').removeClass('controls');
		$('.fcsep_level2').parents('.controls').removeClass('controls');
		
		$('li.hugegallery').parents('.controls').removeClass('controls');
		// BLOCKFLAT
		$( ".com_templates .span12 table" ).wrap( "<div class=\"block-flat\"></div>" );
		$( ".com_menus .span12 table" ).wrap( "<div class=\"block-flat\"></div>" );
			  
	  // 
	  // FILTER BAR - clearfix
	  $( "div#filter-bar" ).addClass( "clearfix" );
	  });  
	  })( jQuery );


/** ----------------------------
+ Joomla Default
----------------------------**/


(function($)
{
	$(document).ready(function()
	{
		$('*[rel=tooltip]').tooltip();

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$('.btn-group label:not(.active)').click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				input.trigger('change');
			}
		});
		$('.btn-group input[checked=checked]').each(function()
		{
			if ($(this).val() == '') {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-danger');
			} else {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-success');
			}
		});
		// add color classes to chosen field based on value
		$('select[class^="chzn-color"], select[class*=" chzn-color"]').on('liszt:ready', function(){
			var select = $(this);
			var cls = this.className.replace(/^.(chzn-color[a-z0-9-_]*)$.*/, '\1');
			var container = select.next('.chzn-container').find('.chzn-single');
			container.addClass(cls).attr('rel', 'value_' + select.val());
			select.on('change click', function()
			{
				container.attr('rel', 'value_' + select.val());
			});

		});

		/**
		 * USED IN: All list views to hide/show the sidebar
		 */
		window.toggleSidebar = function(force)
		{
			var context = 'jsidebar';

			var $sidebar = $('#j-sidebar-container'),
				$main = $('#j-main-container'),
				$message = $('#system-message-container'),
				$debug = $('#system-debug'),
				$toggleSidebarIcon = $('#j-toggle-sidebar-icon'),
				$toggleButtonWrapper = $('#j-toggle-button-wrapper'),
				$toggleButton = $('#j-toggle-sidebar-button'),
				$sidebarToggle = $('#j-toggle-sidebar');

			var openIcon = 'icon-arrow-left-2',
				closedIcon = 'icon-arrow-right-2';

			var $visible = $sidebarToggle.is(":visible");

			if (jQuery(document.querySelector("html")).attr('dir') == 'rtl')
			{
				openIcon = 'icon-arrow-right-2';
				closedIcon = 'icon-arrow-left-2';
			}

			var isComponent = $('body').hasClass('component');

			$sidebar.removeClass('span2').addClass('j-sidebar-container');
			$message.addClass('j-toggle-main');
			$main.addClass('j-toggle-main');
			if (!isComponent) {
				$debug.addClass('j-toggle-main');
			}

			var mainHeight = $main.outerHeight()+30,
				sidebarHeight = $sidebar.outerHeight(),
				bodyWidth = $('body').outerWidth(),
				sidebarWidth = $sidebar.outerWidth(),
				contentWidth = $('#content').outerWidth(),
				contentWidthRelative = contentWidth / bodyWidth * 100,
				mainWidthRelative = (contentWidth - sidebarWidth) / bodyWidth * 100;

			if (force)
			{
				// Load the value from localStorage
				if (typeof(Storage) !== "undefined")
				{
					$visible = localStorage.getItem(context);
				}

				// Need to convert the value to a boolean
				$visible = ($visible == 'true');
			}
			else
			{
				$message.addClass('j-toggle-transition');
				$sidebar.addClass('j-toggle-transition');
				$toggleButtonWrapper.addClass('j-toggle-transition');
				$main.addClass('j-toggle-transition');
				if (!isComponent) {
					$debug.addClass('j-toggle-transition');
				}
			}

			if ($visible)
			{
				$sidebarToggle.hide();
				$sidebar.removeClass('j-sidebar-visible').addClass('j-sidebar-hidden');
				$toggleButtonWrapper.removeClass('j-toggle-visible').addClass('j-toggle-hidden');
				$toggleSidebarIcon.removeClass('j-toggle-visible').addClass('j-toggle-hidden');
				$message.removeClass('span10').addClass('span12');
				$main.removeClass('span10').addClass('span12 expanded');
				$toggleSidebarIcon.removeClass(openIcon).addClass(closedIcon);
				$toggleButton.attr( 'data-original-title', Joomla.JText._('JTOGGLE_SHOW_SIDEBAR') );
				$sidebar.attr('aria-hidden', true);
				$sidebar.find('a').attr('tabindex', '-1');
				$sidebar.find(':input').attr('tabindex', '-1');

				if (!isComponent) {
					$debug.css( 'width', contentWidthRelative + '%' );
				}

				if (typeof(Storage) !== "undefined")
				{
					// Set the last selection in localStorage
					localStorage.setItem(context, true);
				}
			}
			else
			{
				$sidebarToggle.show();
				$sidebar.removeClass('j-sidebar-hidden').addClass('j-sidebar-visible');
				$toggleButtonWrapper.removeClass('j-toggle-hidden').addClass('j-toggle-visible');
				$toggleSidebarIcon.removeClass('j-toggle-hidden').addClass('j-toggle-visible');
				$message.removeClass('span12').addClass('span10');
				$main.removeClass('span12 expanded').addClass('span10');
				$toggleSidebarIcon.removeClass(closedIcon).addClass(openIcon);
				$toggleButton.attr( 'data-original-title', Joomla.JText._('JTOGGLE_HIDE_SIDEBAR') );
				$sidebar.removeAttr('aria-hidden');
				$sidebar.find('a').removeAttr('tabindex');
				$sidebar.find(':input').removeAttr('tabindex');

				if (!isComponent && bodyWidth > 768 && mainHeight < sidebarHeight)
				{
					$debug.css( 'width', mainWidthRelative + '%' );
				}
				else if (!isComponent)
				{
					$debug.css( 'width', contentWidthRelative + '%' );
				}

				if (typeof(Storage) !== "undefined")
				{
					// Set the last selection in localStorage
					localStorage.setItem( context, false );
				}
			}
		}
	});
})(jQuery);



/*--------------------------------------------
:: EQUAL HEIGHT COLUMNS
--------------------------------------------*/


(function($)
{
    equalheight = function (container) {

        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0;
        $(container).each(function () {

            $el = $(this);
            $($el).height('auto')
            topPostion = $el.position().top;

            if (currentRowStart != topPostion) {
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
                rowDivs.length = 0; // empty the array
                currentRowStart = topPostion;
                currentTallest = $el.height();
                rowDivs.push($el);
            } else {
                rowDivs.push($el);
                currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
            }
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }


    // HIGHER DIV LOWER
	


 // HIGHER DIV LOWER
    $(document).ready(function() {
        $('.subhead').css('top', $('nav.navbar').css('height'));
    });

    $(window).resize(function() {
        $('.subhead').css('top', $('nav.navbar').css('height'));
    });
	




	
	
	// FILters equal height
	 $(window).click(function () {
        equalheight('#filterline .block-flat-s');
    });


    $(window).resize(function () {
        equalheight('#filterline .block-flat-s');
    });
	
	    // HIGHER DIV LOWER
 $(window).load(function() {

         equalheight('.eq-height');
	
    });

$(window).resize(function () {

         equalheight('.eq-height');

    }); 

 
   // HIGHER DIV LOWER
    $(document).ready(function() {
        $('.xspace').css('margin-top', $('nav.navbar').css('height'));
    });

    $(window).resize(function() {
        $('.xspace').css('margin-top', $('nav.navbar').css('height'));
    });   //
	})(jQuery);
//



(function( $ ){
 
  $(document).ready(function(){
 
    var current_width = $(window).width();
    //do something with the width value here!
    if(current_width < 767)
      $('.bgcolor, .logo-box').addClass("hide-eq-height").removeClass("eq-height");
  
  
   if(current_width > 767)
      $('.bgcolor, .logo-box').addClass("eq-height").removeClass("hide-eq-height");
  });
 
  //update the width value when the browser is resized (useful for devices which switch from portrait to landscape)
  $(window).resize(function(){
    var current_width = $(window).width();
    //do something with the width value here!
 if(current_width < 767)
      $('.bgcolor, .logo-box').addClass("hide-eq-height").removeClass("eq-height");
  
 if(current_width > 767)
      $('.bgcolor, .logo-box').addClass("eq-height").removeClass("hide-eq-height");
 	
  });
 
})( jQuery );


jQuery(function($) {
  var json, tabsState;
  $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown', function(e) {
    var href, json, parentId, tabsState;

    tabsState = localStorage.getItem("tabs-state");
    json = JSON.parse(tabsState || "{}");
    parentId = $(e.target).parents("ul.nav.nav-pills, ul.nav.nav-tabs").attr("id");
    href = $(e.target).attr('href');
    json[parentId] = href;

    return localStorage.setItem("tabs-state", JSON.stringify(json));
  });

  tabsState = localStorage.getItem("tabs-state");
  json = JSON.parse(tabsState || "{}");

  $.each(json, function(containerId, href) {
    return $("#" + containerId + " a[href=" + href + "]").tab('show');
  });

  $("ul.nav.nav-pills, ul.nav.nav-tabs").each(function() {
    var $this = $(this);
    if (!json[$this.attr("id")]) {
      return $this.find("a[data-toggle=tab]:first, a[data-toggle=pill]:first").tab("show");
    }
  });
});

jQuery(function(){
	/*jQuery("#qtc_item_state1").attr("checked","checked");
	
	jQuery("#qtc_item_state1").parents(".control-group").css({"display":"none"});
	jQuery("#USD").parents(".control-group").css({"display":"none"}); */
	jQuery("#USD").attr('value','0');
	jQuery("#jform_price_USD").attr('value','0');
	jQuery("#item_slab").attr('value','1');
	jQuery("#min_item").attr('value','1');
	jQuery("#max_item").attr('value','1000');
	jQuery("#qtc_price_currencey_textbox").css("display","none");
	jQuery("#item_slab").parent(".controls").parent(".control-group").css("display","none");
	jQuery("#min_item").parent(".controls").parent(".control-group").css("display","none");
	jQuery("#max_item").parent(".controls").parent(".control-group").css("display","none");
	jQuery("#item_attris").parent(".q2c-wrapper").parent("div").css("display","none");
	jQuery("#mediafile").parent("div").css("display","none");
});