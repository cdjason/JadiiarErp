$(function() 
{

	//对所有submit按钮的遮罩，防止重复提交。
	$(document).ready(function() {
    $('#submit').click(function() {
        $.blockUI({ css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        } });
        setTimeout($.unblockUI, 20000);
    });
    
    //为所有的链接绑定遮罩，平滑用户感受;存在问题，设置路线时
    /*
    $('a').each(function(){
    	$(this).click(function(){
        $.blockUI({ css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        } });
    	})
    });
    */
  });
	// Fade out status messages, but ensure error messages stay visable.
	if ($('.status_msg').length > 0)
	{
		$('#message').delay(4000).fadeTo(1000,0.01).slideUp(500);
	}
  
	
	// Remove No JS Class
	$('html').removeClass('no-js');

	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Convert each css based drop menu into a JS drop menu.
	$('.css_nav_dropmenu').each(function(){
		// Save menu height to enable dropmenu animation
		var menu_height = $(this).find('ul').height();
		$(this).attr('data-menu-height', menu_height);
		
		// Remove fallback css hover effect on JS load
		$(this).attr('class','js_nav_dropmenu'); // Removes existing CSS class	
	});

	// Position Menus for IE6-7 Bug
	$('.js_nav_dropmenu').each(function()
	{
		var pos = $(this).position();
		$(this).find('ul').css('left',pos.left);
	});

	// Toggle Menus
	$('.js_nav_dropmenu').hover(function()
	{
		var menu_height = $(this).attr('data-menu-height');		
		$(this).find('ul').stop().animate({'height':menu_height}, 400);
	},
	function()
	{
		$(this).find('ul').stop().animate({'height':'0px'}, 400);
	});

	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Animate flexi cart ribbon
	$('#flexi_cart_ribbon').hover(
		function()
		{
			$(this).addClass('hover');
			$(this).clearQueue().animate({top:0}, 250);
		},
		function()
		{
			$(this).removeClass('hover');
			$(this).clearQueue().animate({top:-60}, 500);
		}
	);

	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	
	// Fix uneven div heights
	$('.parallel').each(function()
	{
		var tallest_elem = 0;
		$(this).find('.parallel_target').each(function(i)
		{
			tallest_elem = ($(this).height() > tallest_elem)?$(this).height():tallest_elem;
		});
		
		$(this).find('.parallel_target').css({'min-height':tallest_elem});
	});	
	
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	
	// Fade out status messages, but ensure error messages stay visable.
	if ($('.status_msg').length > 0)
	{
		$('#message').delay(4000).fadeTo(1000,0.01).slideUp(500);
	}

	// Tooltip helpers.
	$('.tooltip_trigger[title]').tooltip({effect:'slide', predelay:500});

	$('.tooltip_parent').tooltip({effect: 'slide', relative: true});

	// Toggle show/hide next html tag.
	$('.toggle').on('click', function(){
		$(this).parent().find('.hide_toggle').slideToggle();
	});
	
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	
	// Show hidden help in user guide.
	$('.help_link').click(function(){
		$('#help_guide').show();
	});
	
	

	
	
	
	
	

});
