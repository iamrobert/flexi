/*
 *	=======================================================================
	+ REMOVE OUTLINE ON FOCUS
   https://hackernoon.com/removing-that-ugly-focus-ring-and-keeping-it-too-6c8727fefcd2
   https://codepen.io/davidgilbertson/pen/LjGzGw
 *	=======================================================================
 */

function handleFirstTab(e) {
  if (e.keyCode === 9) {
    document.body.classList.add('user-is-tabbing');
    window.removeEventListener('keydown', handleFirstTab);
  }
}

window.addEventListener('keydown', handleFirstTab);


jQuery(function($)
{
	'use strict';
   

/*
 *	=======================================================================
	+ MOBILE NAV: SHOW USER COLUMN ON MENU ACTIVATED
 *	=======================================================================
 */


$(".nav-collapse").on("show",function(event){
        $('#js-user-col').removeClass('hidden-phone hidden-table');
    });

$(".nav-collapse").on("hidden",function(event){
       $('#js-user-col').addClass('hidden-phone hidden-table');
           
    });


/*
 *	=======================================================================
	+ FADE MENU when in disabled mode
 *	=======================================================================
 */
   
   if($('.admin-logo').hasClass('disabled')) {
      $('#top-menu').addClass('js-top-menu-disabled');
   }


/*
 *	=======================================================================
	+ FLEXIcontent Side Menu add Class
 *	=======================================================================
 */
   
$(".flexicontent #submenu li").each(function(i) {
        $(this).addClass("flexi" + (i+1));
        });
	  

   
/* + META
	======================================================================*/	
$( "textarea#jform_metadesc" ).after( "<span id=\"summarychrs\">0</span>" );




function countChar(inobj, maxl, outobj) {
var isTextCounter = $('#jform_metadesc');
if (isTextCounter.length > 0) {
    var len = inobj.value.length;
    var msg = ' characters left';
    if (len >= maxl) {
        inobj.value = inobj.value.substring(0, maxl);
        $(outobj).text(0 + msg);
		$('#jform_metadesc').addClass('error');
		$('#summarychrs').addClass('warning');
    } else {
        $(outobj).text(maxl - len + msg);
		$('#jform_metadesc').removeClass('error');
		$('#summarychrs').removeClass('warning');
    }
}

}

    //set up summary field character count
    countChar($('#jform_metadesc').get(0),160, '#summarychrs'); //show inital value on page load
    $('#jform_metadesc').keyup(function() {
        countChar(this, 160, '#summarychrs'); //set up on keyup event function
    });


   
//
});

