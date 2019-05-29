<?php
/**
* @package Admin-Messages (mod_adminmessages)
* @version 1.0.0
* @copyright Copyright (C) 2011-2012 Carsten Engel. All rights reserved.
* @license GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html 
* @author http://www.pages-and-items.com
*/

// No direct access.
defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$ds = DIRECTORY_SEPARATOR;
$adminmenumanagermenuhelper->do_init($params, $module);
$version = $adminmenumanagermenuhelper->version;
$style = $adminmenumanagermenuhelper->style;
$adminmenumanager_break_left = $adminmenumanagermenuhelper->adminmenumanager_break_left;
$adminmenumanager_break_right = $adminmenumanagermenuhelper->adminmenumanager_break_right;


$output = '';
foreach($amm_menuitems as $menuitem){
	$output .= '<li id="adminmenum_menuitem_'.$menuitem->id.'"';
	$class = '';
	if($menuitem->down!=''){		
		$class .= 'parent';
	}	
	if(JRequest::getInt('hidemainmenu') && $adminmenumanagerdisable){	
		$class .= ' amm_li_disabled';
	}
	
	if($version->RELEASE < '3.0'){
		//joomla 2.5
		if($menuitem->type=='1'){
			//text separator
			$class .= ' adminmenum_text_separator';
			if($menuitem->level=='1'){
				$class .= '_level_1';
			}
		}
	}
	
	if($menuitem->type=='2'){
		//line separator
		$class .= ' adminmenum_line_separator';
		if($menuitem->level=='1'){
			$class .= '_level_1';
		}
	}
	if($class!=''){
		$output .= ' class="'.$class.'"';
	}
	$output .= '>';	
	
	if($version->RELEASE >= '3.0'){
		//joomla 3
		if($menuitem->type=='2'){
			//line separator		
			$output .= '<span>&nbsp;</span>';
		}else{
			//not a line separator
			$class = '';
			$output .= '<a href="';
			if($menuitem->type=='1'){
				//textseparator
				$output .= 'javascript:return false;';
			}else{
				//normal link
				$output .= $menuitem->url;
			}
			$output .= '"';
			if($menuitem->icon=='' && $menuitem->level==1){
				$class .= ' no_icon_level_1';
			}
			if($menuitem->icon!=''){
				$output .= ' style="background-image: url('.$menuitem->icon.');"';
			}		
			if($menuitem->type=='1'){
				//textseparator
				$class .= ' adminmenum_text_separator';
				if($menuitem->level=='1'){
					$class .= '_level_1';
				}			
			}else{
				//normal link
				if($menuitem->target=='1'){
					$output .= ' target="_blank"';
				}	
				if($menuitem->target=='2'){
					$class .= 'modal';
					$output .= ' rel="{size: { x: '.$menuitem->width.' , y: '.$menuitem->height.'}}"';
				}
				if($menuitem->target=='3'){
					$document = JFactory::getDocument();
					$url = 'modules/mod_adminmenumanager/javascript/popup_centered.js';
					$document->addScript($url);
					$output .= ' onclick="amm_pop_centered(this.href, '.$menuitem->width.', '.$menuitem->height.');return false;"';
				}
			}
			if($class){
				$output .= ' class="'.$class.'"';
			}				
			$output .= '>';			
			$output .= '<span class="pad_a';		
			if($menuitem->down!=''){
				if($menuitem->level==1){
					$output .= ' arrow_down';
				}else{
					$output .= ' arrow_right';
				}
			}
			$output .= '">';
			$output .= $menuitem->title;		
			$output .= '</span>';
			
			if($menuitem->down!=''){
				$output .= '<span class="showhide">';
				$output .= '<span class="amm_open" onclick="amm_showhide(this); return false;">&nbsp;</span>';
				$output .= '</span>';
			}
			
			$output .= '</a>';	
		}
	}else{
		//joomla 2.5
		if($menuitem->type=='1'){
			//text separator		
			$output .= '<span';
			if($menuitem->down!='' && $menuitem->level==1){
				if($menuitem->icon){
					$output .= ' class="arrow_down adminmenum_text_separator_icon_wrap"';
				}else{
					$output .= ' class="arrow_down"';
				}
			}
			$output .= '>';
			if($menuitem->icon!=''){
				$output .= '<i class="adminmenum_text_separator_icon" style="background-image: url('.$menuitem->icon.');">';
			}
			$output .= $menuitem->title;	
			if($menuitem->icon!=''){
				$output .= '</i>';
			}	
			$output .= '</span>';
		}elseif($menuitem->type=='2'){
			//line separator		
			if($menuitem->level=='1'){
				$output .= '<span>&nbsp;</span>';
			}else{
				$output .= '&nbsp;';
			}		
		}else{
			//normal link
			$output .= '<a href="'.$menuitem->url.'"';
			if($menuitem->icon=='' && $menuitem->level==1){
				$output .= ' style="padding-left: 1em;"';
			}
			if($menuitem->icon!=''){
				$output .= ' style="background-image: url('.$menuitem->icon.');"';
			}
			if($menuitem->target=='1'){
				$output .= ' target="_blank"';
			}	
			if($menuitem->target=='2'){
				$output .= ' class="modal" rel="{size: { x: '.$menuitem->width.' , y: '.$menuitem->height.'}}"';
			}
			if($menuitem->target=='3'){
				$document = JFactory::getDocument();
				$url = 'modules/mod_adminmenumanager/javascript/popup_centered.js';
				$document->addScript($url);
				$output .= ' onclick="amm_pop_centered(this.href, '.$menuitem->width.', '.$menuitem->height.');return false;"';
			}
			$output .= '>';	
			if($menuitem->down!='' && $menuitem->level==1){
				$output .= '<span class="arrow_down">';
			}
			$output .= $menuitem->title;			
			if($menuitem->down!='' && $menuitem->level==1){
				$output .= '</span>';
			}
			$output .= '</a>';	
		}
	}
	
	if($menuitem->up){
		$output .= '</li>';		
		$output .=  str_repeat('</ul></li>', $menuitem->levels);		
	}elseif($menuitem->down){
		$output .= '<ul';
		if($menuitem->level=='1'){
			$output .= ' class="adminmenum_submenu"';
		}
		$output .= '>';
	}else{
		$output .= '</li>';
	}
}

if($output){	
	$document = JFactory::getDocument();	
	if($style=='isis'){
		//there was an update, so had to rename stylesheet to force browser to load without cache
		$stylesheet = 'isis2';
	}else{
		$stylesheet = $style;
	}
	//$document->addStyleSheet('modules/mod_adminmenumanager/css/'.$stylesheet.'.css');//problems with this when reading sql errors
//	echo '<link rel="stylesheet" href="modules/mod_adminmenumanager/css/'.$stylesheet.'.css" type="text/css" />';
	if($params->get('adminmenumanagercursor', '')=='arrow'){		
		$document->addStyledeclaration('ul.adminmenum_menu li a{cursor: default;}');
	}
	if($params->get('extracss', '')){
		$document->addStyledeclaration($params->get('extracss', ''));
	}	
	if($version->RELEASE >= '3.0' && $style=='isis'){
		$url = 'modules/mod_adminmenumanager/javascript/isis.js';
		$document->addScript($url);
	}
	if($version->RELEASE >= '3.0'){
		$url = 'modules/mod_adminmenumanager/javascript/responsive.js';
		$document->addScript($url);
	}
	if($adminmenumanager_break_left){
		echo '<div style="clear: both;" class="amm_line_separator"></div>';
	}
	$menu_disabled = '';
	if(JRequest::getInt('hidemainmenu') && $adminmenumanagerdisable){		
		$menu_disabled = ' amm_menu_disabled';
	}
	echo '<ul class="adminmenum_menu'.$class_sfx.$menu_disabled.'" id="adminmenum_module_'.$module->id.'">';
	echo $output;
	echo '</ul>';
	
	if($adminmenumanager_break_right){
		echo '<div style="clear: both;" class="amm_line_separator"></div>';
	}	
	//fix subheader. never hardcode height.
	$url = 'modules/mod_adminmenumanager/javascript/subhead.js';
	$document->addScript($url);
	
	if($adminmenumanager_break_right || $adminmenumanager_break_left){
		$document->addStyledeclaration('.subhead-collapse .subhead-fixed{top:60px;}');
	}
}

?>