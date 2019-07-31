<?php
/* iamrobert.com | TAIWAN -------------------------------------------------
   _                     __           __ 
  (_)__ ___ _  _______  / /  ___ ____/ /_
 / / _ `/  ' \/ __/ _ \/ _ \/ -_) __/ __/
/_/\_,_/_/_/_/_/  \___/_.__/\__/_/  \__/ 

===================================================== DIGITAL DESIGN STUDIO  
# author    Robert Stark
# copyright Copyright (C) 2019 iamrobert.com All rights reserved.
# @license  GNU General Public License version 2 or later;
# Website   https://www.iamrobert.com
-------------------------------------------------------------------------*/

/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app   = JFactory::getApplication();
$lang  = JFactory::getLanguage();
$input = $app->input;
$user  = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Gets the FrontEnd Main page Uri
$frontEndUri = JUri::getInstance(JUri::root());
$frontEndUri->setScheme(((int) $app->get('force_ssl', 0) === 2) ? 'https' : 'http');
$mainPageUri = $frontEndUri->toString();

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add filter polyfill for IE8
JHtml::_('behavior.polyfill', array('filter'), 'lte IE 9');

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add app.js
JHtml::_('script', 'app.js', array('version' => 'auto', 'relative' => true));

// Add Stylesheets
JHtml::_('stylesheet', 'template' . ($this->direction === 'rtl' ? '-rtl' : '') . '.css', array('version' => 'auto', 'relative' => true));

// Load specific language related CSS
JHtml::_('stylesheet', 'administrator/language/' . $lang->getTag() . '/' . $lang->getTag() . '.css', array('version' => 'auto'));

// Load custom.css
JHtml::_('stylesheet', 'custom.css', array('version' => 'auto', 'relative' => true));


JHtml::_('stylesheet', 'responsive.css', array('version' => 'auto', 'relative' => true));

/* + UNSET FLEXICONTENT STYLE
	======================================================================*/
unset($this->_styleSheets[JURI::root( true ).'/administrator/modules/mod_adminmenumanager/css/isis2.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/assets/css/tabber.css']);
unset($this->_styleSheets[JURI::root( true ).'/administrator/components/com_flexicontent/assets/css/flexicontentbackend.css']);
unset($this->_styleSheets[JURI::root( true ).'/administrator/components/com_flexicontent/assets/css/j3x.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/assets/css/flexi_form_fields.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/librairies/jquery/css/ui-lightness/jquery-ui-1.9.2.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/librairies/prettyCheckable/dist/prettyCheckable.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/librairies/select2/select2.css']);

//ADMIN MENU - doesn't work
unset($this->_styleSheets[JURI::root( true ).'/modules/mod_adminmenumanager/css/isis2.css']);

//QUICK ICONS - doesn't work
unset($this->_styleSheets[JURI::root( true ).'administrator/modules/mod_akquickicons/css/akquickicons.css']);

// Detecting Active Variables
$option   = $input->get('option', '');
$view     = $input->get('view', '');
$layout   = $input->get('layout', '');
$task     = $input->get('task', '');
$itemid   = $input->get('Itemid', 0, 'int');
$sitename = htmlspecialchars($app->get('sitename', ''), ENT_QUOTES, 'UTF-8');
$cpanel   = $option === 'com_cpanel';

$hidden = $app->input->get('hidemainmenu');

$showSubmenu          = false;
$this->submenumodules = JModuleHelper::getModules('submenu');

foreach ($this->submenumodules as $submenumodule)
{
	$output = JModuleHelper::renderModule($submenumodule);

	if ($output !== '')
	{
		$showSubmenu = true;
		break;
	}
}

// Template Parameters
$displayHeader = $this->params->get('displayHeader', '1');
$statusFixed   = $this->params->get('statusFixed', '1');
$stickyToolbar = $this->params->get('stickyToolbar', '1');

// Header classes
$navbar_color    = $this->params->get('templateColor') ?: '';
$header_color    = $displayHeader && $this->params->get('headerColor') ? $this->params->get('headerColor') : '';
$navbar_is_light = $navbar_color && colorIsLight($navbar_color);
$header_is_light = $header_color && colorIsLight($header_color);

if ($displayHeader)
{
	// Logo file
	if ($this->params->get('logoFile'))
	{
		$logo = JUri::root() . $this->params->get('logoFile');
	}
	else
	{
		$logo = $this->baseurl . '/templates/' . $this->template . '/images/logo' . ($header_is_light ? '-inverse' : '') . '.png';
	}
}

function colorIsLight($color)
{
	$r = hexdec(substr($color, 1, 2));
	$g = hexdec(substr($color, 3, 2));
	$b = hexdec(substr($color, 5, 2));

	$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

	return $yiq >= 200;
}

// Pass some values to javascript
$offset = 20;

if ($displayHeader || !$statusFixed)
{
	$offset = 30;
}

$stickyBar = 0;

if ($stickyToolbar)
{
	$stickyBar = 'true';
}

// Template color
if ($navbar_color)
{
	$this->addStyleDeclaration('
	.navbar-inner,
	.navbar-inverse .navbar-inner,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
	.navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
	.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle,
	#status.status-top {
		background: ' . $navbar_color . ';
	}');
}

// Template header color
if ($header_color)
{
	$this->addStyleDeclaration('
	.header {
		background: ' . $header_color . ';
	}');
}

// Sidebar background color
if ($this->params->get('sidebarColor'))
{
	$this->addStyleDeclaration('
	.nav-list > .active > a,
	.nav-list > .active > a:hover {
		background: ' . $this->params->get('sidebarColor') . ';
	}');
}

// Link color
if ($this->params->get('linkColor'))
{
	$this->addStyleDeclaration('
	a,
	.j-toggle-sidebar-button,
   .cpanel a:hover, .cpanel a:focus,
   .nav-tabs > .active > a, .nav-tabs > .active > a:hover, .nav-tabs > .active > a:focus,
   .accordion-heading a, #flexicontent ul.tabbernav li.tabberactive a, #flexicontent ul.tabbernav li.tabberactive a:hover,
   .nav-tabs > li > a:hover, .nav-tabs > li > a:focus, #flexicontent ul.tabbernav li:hover a, .contentpane.component #flexicontent .nav-tabs.nav-stacked > li > a {
		color: ' . $this->params->get('linkColor') . ';
	}');
   
 	$this->addStyleDeclaration('  
   .tabberactive *:not(:hover) > .fc-icon-gray:before {
      color: ' . $this->params->get('linkColor') . '!important;
  }');
      
      
 $this->addStyleDeclaration('  
.select2-drop-active, .select2-drop.select2-drop-above.select2-drop-active, .select2-container-active .select2-choice,
.select2-container-active .select2-choices, .select2-dropdown-open.select2-drop-above .select2-choice,
.select2-dropdown-open.select2-drop-above .select2-choices, .select2-container-multi.select2-container-active .select2-choices {
		border-color: ' . $this->params->get('linkColor') . ';
	}'); 
  
   
   $this->addStyleDeclaration('   
   
   .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus,
   #flexicontent ul.tabbernav li.tabberactive a, #flexicontent ul.tabbernav li.tabberactive a:hover {
   border-top-color: ' . $this->params->get('linkColor') . '; 
    }');  
   
    $this->addStyleDeclaration(' 
   .btn-primary, .accordion-toggle:not(.collapsed), .contentpane.component #flexicontent .nav-tabs.nav-stacked > li > a:hover, .contentpane.component #flexicontent .nav-tabs.nav-stacked > li > a:focus {
   background-color: ' . $this->params->get('linkColor') . '; 
   }');  
}

if ($this->params->get('hoverColor'))
{
$this->addStyleDeclaration('  
a:hover, a:focus, .j-toggle-sidebar-button:hover {
color: ' . $this->params->get('hoverColor') . '; 
}');  
   
$this->addStyleDeclaration('     
.btn-primary:hover, .btn-primary:focus {
   background-color: ' . $this->params->get('hoverColor') . '; 
 }');    
}
//
if ($this->params->get('flexiColor'))
{
$this->addStyleDeclaration('
   .aniColorBkg {
		background: ' . $this->params->get('flexiColor') . ';
	}');
   
$this->addStyleDeclaration('
   .ruler, .fcsep_level_h, .fcsep_level0, .fcsep_level2, .fcsep_level3, .com_cpanel div.well.well-small.span3 > h3.module-title.nav-header {
		color: ' . $this->params->get('flexiColor') . ';
	}');    
}

//REMOVED FEATURED ITEMS
if ($this->params->get('featuredItems') == 0) {
$this->addStyleDeclaration('
.h_feat, .featured.btn {
	display: none !important;
}');  
}

//REMOVE TAGS
if ($this->params->get('flexiTags') == 0) {
 $this->addStyleDeclaration('  
.h_tags, .col_tag, #fc-toggle-tags_btn, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_12_label, #submenu li.flexi8  {
	display: none !important;
}');     
}

//HIDE AUTHOR FROM TABLE
if ($this->params->get('flexiAuthor') == 0) {
 $this->addStyleDeclaration(' 
.h_author, #columnchoose_adminListTableFCitems_5_label, .col_authors {
	display: none !important;
}');    
}


//HIDE TEMPLATE EDITING
if ($this->params->get('flexiGlobalEdit') == 0) {
 $this->addStyleDeclaration(' 
.adminlist td.col_edit_layout .fc-edit-layout-btn {
	display: none !important;
}

th.col_edit_layout, td.col_edit_layout {
padding: 0;
}
');    
}

//HIDE LANGUAGE
if ($this->params->get('flexiLang') == 0) {
 $this->addStyleDeclaration(' 
.col_lang, .h_lang, .col_assoc, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_6_label, .com_flexicontent.view-category #tabset_cat_props_desc_tab > div > div.span4.col-4.full_width_980.off-white > div > div:nth-child(4) {
	display: none !important;
}
');    
}

//Hide Ratings
if ($this->params->get('flexiRatings') == 0) {
 $this->addStyleDeclaration(' 
.col_ratings, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_17_label, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_17 {
	display: none !important;
}
');    
}


if ($this->params->get('flexiVotes') == 0) {
 $this->addStyleDeclaration(' 
.col_votes, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_16_label, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_16 {
	display: none !important;
}
');    
}

//Hide Hits
if ($this->params->get('flexiHits') == 0) {
 $this->addStyleDeclaration(' 
.col_hits, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_15_label, .com_flexicontent.view-items #columnchoose_adminListTableFCitems_18,
.com_flexicontent.view-item #fc_versions > tbody > tr:nth-child(3) {
	display: none !important;
}
');    
}

//Hide more button in item View
//if ($this->params->get('flexiMoreButton') == 0) {
// $this->addStyleDeclaration(' 
//.view-item #toolbar-action_btns_group, #toolbar-action_btns_group {
//	display: none !important;
//}
//');    
//}

//Hide keywords
if ($this->params->get('keywords') == 0) {
 $this->addStyleDeclaration(' 
.hkey, #h-208, #jform_metakey-lbl, #jform_metakey {
	display: none !important;
}
');    
}

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<jdoc:include type="head" />
</head>
<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?>  <?php if ($this->params->get('amm_used') != '0') : ?><?php echo ' amm';?><?php endif; ?>" data-basepath="<?php echo JURI::root(true); ?>">
<!-- Top Navigation -->
   
<nav class="navbar navbar-inverse navbar-fixed-top" id="top-menu">
	<div class="navbar-inner">
		<div class="container-fluid">
			<?php if ($this->params->get('admin_menus') != '0') : ?>
				<a href="#" class="btn btn-navbar collapsed vc-button" data-toggle="collapse" data-target=".nav-collapse">
					<span class="element-invisible"><?php echo JTEXT::_('TPL_IAMR_TOGGLE_MENU'); ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
			<?php endif; ?>
         
         			<!-- skip to content -->
			<a class="element-invisible" href="#skiptarget"><?php echo JText::_('TPL_IAMR_SKIP_TO_MAIN_CONTENT'); ?></a>
         
         
         <div class="row flex align-stretch">
            <!--LOGO-->
         <div class="span2 flex shrink align-self-stretch aniColorBkg">
        
			<a class="admin-logo logo-box flex align-self-middle  <?php echo ($hidden ? 'disabled' : ''); ?>" <?php echo ($hidden ? '' : 'href="' . $this->baseurl . '/index.php"'); ?>>
				<img src="<?php echo $logo; ?>" alt="<?php echo $sitename;?>" class="logox" />
				<div class="element-invisible">
					<?php echo JText::_('TPL_IAMR_CONTROL_PANEL'); ?>
				</div>
			</a>
          
         </div>
             <!--LOGO--> 
            <!--MAIN NAV-->
         <div class="span9 auto align-self-middle">
            <div<?php echo ($this->params->get('admin_menus') != '0') ? ' class="nav-collapse collapse"' : ''; ?>>
				<jdoc:include type="modules" name="menu" style="none" />
            </div>
          

			</div>
            
                        <div class="span1 shrink align-self-middle hidden-phone hidden-table" id="js-user-col">
            <ul class="nav nav-user<?php echo ($this->direction == 'rtl') ? ' pull-left' : ' pull-right'; ?>">
					<li class="dropdown">
						<a class="<?php echo ($hidden ? ' disabled' : 'dropdown-toggle'); ?>" data-toggle="<?php echo ($hidden ? '' : 'dropdown'); ?>" <?php echo ($hidden ? '' : 'href="#"'); ?>><span class="icon-user"></span>
							<span class="caret"></span>
							<div class="element-invisible">
								<?php echo JText::_('TPL_IAMR_USERMENU'); ?>
							</div>
						</a>
						<ul class="dropdown-menu">
							<?php if (!$hidden) : ?>
								<li>
									<span>
										<span class="icon-user"></span>
										<strong><?php echo $user->name; ?></strong>
									</span>
								</li>
								<li class="divider"></li>
								<li>
									<a href="index.php?option=com_admin&amp;task=profile.edit&amp;id=<?php echo $user->id; ?>"><?php echo JText::_('TPL_IAMR_EDIT_ACCOUNT'); ?></a>
								</li>
								<li class="divider"></li>
								<li class="">
									<a href="<?php echo JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1'); ?>"><?php echo JText::_('TPL_IAMR_LOGOUT'); ?></a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				</ul>
				<a class="brand visible-desktop visible-tablet" href="<?php echo $mainPageUri; ?>" title="<?php echo JText::sprintf('TPL_IAMR_PREVIEW', $sitename); ?>" target="_blank"><span class="icon-out-2 small"></span></a>
            </div>
            
         </div>
<!--            /Main Nav-->
         </div> 

         
      </div>
</nav>
   
   
   <?php if ($displayHeader) : ?>
	<header class="header<?php echo $header_is_light ? ' header-inverse' : ''; ?>">
		<div class="container-title">
			<jdoc:include type="modules" name="title" />
		</div>
	</header>
<?php endif; ?>
   

<?php if (!$cpanel) : ?>
	<!-- Subheader -->
	<a class="btn btn-subhead" data-toggle="collapse" data-target=".subhead-collapse"><?php echo JText::_('TPL_IAMR_TOOLBAR'); ?>
		<span class="icon-wrench"></span></a>
	<div class="subhead-collapse collapse" id="isisJsData" data-tmpl-sticky="<?php echo $stickyBar; ?>" data-tmpl-offset="<?php echo $offset; ?>">
		<div class="subhead">
			<div class="container-fluid">
				<div id="container-collapse" class="container-collapse"></div>
				<div class="row-fluid">
					<div class="span12">
						<!-- target for skip to content link -->
						<a id="skiptarget" class="element-invisible"><?php echo JText::_('TPL_IAMR_SKIP_TO_MAIN_CONTENT_HERE'); ?></a>
						<jdoc:include type="modules" name="toolbar" style="no" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else : ?>
	<div style="margin-bottom: 20px">
		<!-- target for skip to content link -->
		<a id="skiptarget" class="element-invisible"><?php echo JText::_('TPL_IAMR_SKIP_TO_MAIN_CONTENT_HERE'); ?></a>
	</div>
<?php endif; ?>
<!-- container-fluid -->
<div class="container-fluid container-main">
	<section id="content">
		<!-- Begin Content -->
		<jdoc:include type="modules" name="top" style="xhtml" />
		<div class="row-fluid">
			<?php if ($showSubmenu) : ?>
			<div class="span2">
				<jdoc:include type="modules" name="submenu" style="none" />
			</div>
			<div class="span10">
				<?php else : ?>
				<div class="span12">
					<?php endif; ?>
      
					<jdoc:include type="message" />           
					<jdoc:include type="component" />
				</div>
			</div>
			<?php if ($this->countModules('bottom')) : ?>
				<jdoc:include type="modules" name="bottom" style="xhtml" />
			<?php endif; ?>
			<!-- End Content -->
	</section>

	<?php if (!$this->countModules('status') || (!$statusFixed && $this->countModules('status'))) : ?>
		<footer class="footer">
			<p class="text-center">
				<jdoc:include type="modules" name="footer" style="no" />
				&copy; <?php echo $sitename; ?> <?php echo date('Y'); ?></p>
		</footer>
	<?php endif; ?>
</div>
<?php if ($statusFixed && $this->countModules('status')) : ?>
	<!-- Begin Status Module -->
	<div id="status" class="navbar navbar-fixed-bottom hidden-phone">
		<div class="btn-toolbar">
			<div class="btn-group pull-right">
				<p>
					<jdoc:include type="modules" name="footer" style="no" />
					&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
				</p>

			</div>
			<jdoc:include type="modules" name="status" style="no" />
		</div>
	</div>
	<!-- End Status Module -->
<?php endif; ?>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
