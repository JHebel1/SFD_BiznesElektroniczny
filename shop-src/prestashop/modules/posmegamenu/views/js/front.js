/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also avaiposle through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function()
{	
	activeMobile();
	$(window).resize(function(){
		if($(window).width() < 992)
		{
			$('.pos-menu-horizontal').addClass('pos-mobile-menu');
			$('#_mobile_megamenu img').parent('a').addClass("img_banner"); 
			$('.pos-mobile-menu').removeClass('container');	
		}
		else
		{
			$('.pos-menu-horizontal').removeClass('pos-mobile-menu');
			$('.pos-menu-horizontal .menu-dropdown').show(); 		
		}
		
		
	});

	posInitHorizontalMegamenu(); 

	$('#_desktop_megamenu img').parent('a').addClass("img_desktop"); 
	$('#_mobile_megamenu img').parent('a').addClass("img_banner"); 
	window.top.ceFrontend.hooks.addAction('frontend/element_ready/widget', function($scope, $) {
        var widget = $scope.data('element_type');

        if (widget == 'pos_menu.default') {
        	posInitHorizontalMegamenu(); 
        }
    });
});
function posInitHorizontalMegamenu() {
    var $menuHorizontal = $('.pos-menu-horizontal');
    var $list = $menuHorizontal.find('li.hasChild');

    $list.hover(function() {
        setOffset($(this))
    });
    var setOffset = function($li) {
        var $dropdown = $li.find('.menu-dropdown');
		if($dropdown.hasClass('cat-drop-menu')){
			return;
		}
    	$dropdown.css({
            'right': '',
            'left': '',
            'width': $dropdown.data('width')
        });
        
        var dropdownWidth = $dropdown.outerWidth();
        var dropdownOffset = $dropdown.offset();
        var toRight;
        var viewportWidth;
        var dropdownOffsetRight;
        var $window = $(window);
        var $body = $('body');
        var screenWidth = $window.width();
        if (!dropdownWidth || !dropdownOffset) {
            return
        }
        if (dropdownWidth > screenWidth) {
            dropdownWidth = screenWidth
        }
        $dropdown.css({
            'width': dropdownWidth
        });
        if ($li.hasClass('hasChild') && dropdownWidth > 1200) {
            viewportWidth = $window.width();
            if (dropdownOffset.left + dropdownWidth >= viewportWidth) { 
				toRight = dropdownOffset.left + dropdownWidth - viewportWidth;
				$dropdown.css({
					left: -toRight
				})
			}
            $li.addClass('menu_initialized')
        } else if ($li.hasClass('dropdown-mega')) {
    		viewportWidth = $('#header .elementor-container').innerWidth();
            dropdownOffsetRight = viewportWidth - dropdownOffset.left - dropdownWidth;
            var extraSpace = 0;
            var containerOffset = ($window.width() - viewportWidth) / 2;
            var dropdownOffsetLeft;
            if (dropdownWidth >= viewportWidth) {
                extraSpace = (viewportWidth - dropdownWidth) / 2
            }
            dropdownOffsetLeft = dropdownOffset.left - containerOffset;
			if (dropdownOffsetLeft + dropdownWidth >= viewportWidth) {
				toRight = dropdownOffsetLeft + dropdownWidth - viewportWidth;
				$dropdown.css({
					left: -toRight - extraSpace -10
				})
			}
            
            $li.addClass('menu_initialized')
        } else {
            $li.addClass('menu_initialized')
        }
    };
    $list.each(function() {
        setOffset($(this))
    })
}
function activeMobile(){
	$('.pos-menu-horizontal .menu-item > .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$('.pos-menu-horizontal .menu-item > .icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.pos-menu-horizontal .menu-dropdown').slideUp();
			$('.pos-menu-horizontal .menu-item > .icon-drop-mobile').next('.pos-menu-horizontal .menu-dropdown').slideUp();
		}
		else {	
			$('.pos-menu-horizontal .menu-item > .icon-drop-mobile').removeClass( 'open_menu' ); 
			$('.pos-menu-horizontal .menu-item > .icon-drop-mobile').next('.pos-menu-horizontal .menu-dropdown').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.pos-menu-horizontal .menu-dropdown').slideDown();
	
		}
		
	});
	$('.pos-menu-horizontal .cat-drop-menu .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$(this).parent().siblings().find('.icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.pos-menu-horizontal .cat-drop-menu').slideUp();
			$(this).parent().siblings().find('.cat-drop-menu').slideUp();
		}
		else {	
			$(this).parent().siblings().find('.icon-drop-mobile').removeClass( 'open_menu' );  
			$(this).parent().siblings().find('.cat-drop-menu').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.pos-menu-horizontal .cat-drop-menu').slideDown();
	
		}
		
	});
	$('.pos-menu-horizontal .pos-menu-col > .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$('.pos-menu-horizontal .pos-menu-col > .icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.pos-menu-horizontal ul.ul-column').slideUp();
			$('.pos-menu-horizontal .pos-menu-col > .icon-drop-mobile').next('.pos-menu-horizontal ul.ul-column').slideUp();
		} 
		else {	
			$('.pos-menu-horizontal .pos-menu-col > .icon-drop-mobile').removeClass( 'open_menu' ); 
			$('.pos-menu-horizontal .pos-menu-col > .icon-drop-mobile').next('.pos-menu-horizontal ul.ul-column').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.pos-menu-horizontal ul.ul-column').slideDown();
	
		}
	
	});
	$('.pos-menu-horizontal .submenu-item  > .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$('.pos-menu-horizontal .submenu-item  > .icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.pos-menu-horizontal ul.category-sub-menu').slideUp();
			$('.pos-menu-horizontal .submenu-item  > .icon-drop-mobile').next('.pos-menu-horizontal ul.category-sub-menu').slideUp();
		}
		else {	
			$('.pos-menu-horizontal .submenu-item  > .icon-drop-mobile').removeClass( 'open_menu' ); 
			$('.pos-menu-horizontal .submenu-item  > .icon-drop-mobile').next('.pos-menu-horizontal ul.category-sub-menu').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.pos-menu-horizontal ul.category-sub-menu').slideDown();
	
		}
	});

	
}
