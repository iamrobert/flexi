/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */



(function($)
{
	$(document).ready(function()
	{
		//iamrobert
		$("body").removeClass("animated");
		
	/*	$('#j-main-container > .span10').wrapInner( "<div class='shift-right'></div>" );
		$('#j-main-container.span12').wrapInner( "<div class='m20'></div>" );
		$('#j-main-container.span10').wrapInner( "<div class='m20'></div>" );
		$('#content > .row12 > .span10').wrapInner( "<div class='shift-right'></div>" );
		$('#application-form > div > div.span10').wrapInner( "<div class='shift-right'></div>" );
		$('#component-form > div.row-fluid > div.span10').wrapInner( "<div class='shift-right'></div>" ); */
		
		$('.fcsep_level0').parents('.controls').removeClass('controls');
		$('.fcsep_level1').parents('.controls').removeClass('controls');
		$('.fcsep_level2').parents('.controls').removeClass('controls');
		
		$('li.hugegallery').parents('.controls').removeClass('controls');
		// BLOCKFLAT
		$( ".com_templates .span12 table" ).wrap( "<div class=\"block-flat\"></div>" );
		$( ".com_menus .span12 table" ).wrap( "<div class=\"block-flat\"></div>" );
	
	//hide search	
// $("#select2Id").select2('container').find('.select2-search').addClass('hidden');

		//
		
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
	});
})(jQuery);


/*///////////////////////////
:: EQUAL HEIGHT COLUMNS
//////////////////////////*/




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
	


$(window).load(function() {
	   
        $('.smaller-div').css('height', $('.navbar-inner').css('height'));
	
    });

$(window).resize(function () {

        $('.smaller-div').css('height', $('.navbar-inner').css('height'));
		
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

    //
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


