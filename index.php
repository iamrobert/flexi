<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.flexi
 * @copyright   Copyright (C) 2014 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$lang            = JFactory::getLanguage();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$input           = $app->input;
$user            = JFactory::getUser();


// HIDE FLEXICONTENT CSS
unset($this->_styleSheets[JURI::base( true ).'/components/com_flexicontent/assets/css/flexicontentbackend.css?'.FLEXI_VHASH.'']);
unset($this->_styleSheets[JURI::base( true ).'/components/com_flexicontent/assets/css/j3x.css?'.FLEXI_VHASH.'']);
unset($this->_styleSheets[JURI::root( true ).'/administrator/components/com_flexicontent/assets/css/j3x.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/assets/css/flexi_form_fields.css?'.FLEXI_VHASH.'']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/librairies/jquery/css/ui-lightness/jquery-ui-1.9.2.css']);
/*unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/librairies/select2/select2.css']);*/
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/assets/css/tabber.css']);
unset($this->_styleSheets[JURI::root( true ).'/administrator/components/com_flexicontent/assets/css/flexicontentbackend.css']);
unset($this->_styleSheets[JURI::root( true ).'/modules/mod_flexiadmin/assets/css/style.css']);


// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScriptVersion($this->baseurl . '/templates/' . $this->template . '/js/template.js');

//CURRENT FRONTEND TEMPLATE		
$tpath = $this->baseurl.'/templates/'.$this->template;
// Add Stylesheets
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/jquery-ui.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/flexi.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/template.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/flexi-up.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/extra.css');

// Load custom.css
$file = 'templates/' . $this->template . '/css/custom.css';

// Load specific language related CSS
$languageCss = 'language/' . $lang->getTag() . '/' . $lang->getTag() . '.css';

if (file_exists($languageCss) && filesize($languageCss) > 0)
{
	$doc->addStyleSheetVersion($languageCss);
}

if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}

$jinput = JFactory::getApplication()->input;
// Detecting Active Variables
$option   = $input->get('option', '');
$view     = $input->get('view', '');
$layout   = $input->get('layout', '');
$task     = $input->get('task', '');
$itemid   = $input->get('Itemid', '');


$sitename = htmlspecialchars($app->get('sitename', ''), ENT_QUOTES, 'UTF-8');
$cpanel   = ($option === 'com_cpanel');

$hidden = JFactory::getApplication()->input->get('hidemainmenu');

$showSubmenu          = false;
$this->submenumodules = JModuleHelper::getModules('submenu');

foreach ($this->submenumodules as $submenumodule)
{
	$output = JModuleHelper::renderModule($submenumodule);

	if (strlen($output))
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
$template_is_light = ($this->params->get('templateColor') && colorIsLight($this->params->get('templateColor')));
$header_is_light = ($displayHeader && $this->params->get('headerColor') && colorIsLight($this->params->get('headerColor')));

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
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<jdoc:include type="head" />
<!-- Template color -->
<?php if ($this->params->get('templateColor')) : ?>
<style type="text/css">
.navbar-inner, .navbar-inverse .navbar-inner, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle, #status.status-top {
 background: <?php echo $this->params->get('templateColor');
?>;
}
</style>
<?php endif; ?>
<!-- Template header color -->
<?php if ($displayHeader && $this->params->get('headerColor')) : ?>
<style type="text/css">
.header {
 background: <?php echo $this->params->get('headerColor');
?>;
}
</style>
<?php endif; ?>

<!-- Sidebar background color -->
<?php if ($this->params->get('sidebarColor')) : ?>
<style type="text/css">
.nav-list > .active > a, .nav-list > .active > a:hover {
 background: <?php echo $this->params->get('sidebarColor');
?>;
}
</style>
<?php endif; ?>

<!-- Link color -->
<?php if ($this->params->get('linkColor')) : ?>
<style type="text/css">
a, .j-toggle-sidebar-button, .icon-arrow-up-2:hover {
 color: <?php echo $this->params->get('linkColor');
?>;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('hoverColor')) : ?>
<style type="text/css">
a:hover, a:focus {
color: <?php echo $this->params->get('hoverColor');
?>;
}
#cpanel div.icon a:hover, #cpanel div.icon a:focus, #cpanel div.icon a:active, .cpanel div.icon a:hover, .cpanel div.icon a:focus, .cpanel div.icon a:active {
border-color: <?php echo $this->params->get('hoverColor');
?>;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('flexiColor')) : ?>
<style type="text/css">
.site-logo, .navbar .btn-navbar, .label-important, .badge-important, .pane-toggler-down span.badge, .pane-toggler span.badge, .fcsep_level2, .flexicontent .accordion.accordion-semi .panel-heading a, .navbar-inverse .dropdown-menu>li>a:hover, .navbar-inverse .dropdown-menu>li>a:focus, .navbar-inverse .dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover, .dropdown-menu>li>a:focus, .dropdown-submenu:hover>a, .dropdown-submenu:focus>a, .com_flexicontent .accordion.accordion-semi .panel-heading a, .pane-toggler-down span.badge, .pane-toggler span.badge, .com_flexicontent .accordion.accordion-color .panel-heading a, .com_flexicontent .accordion.accordion-color .panel-collapse .panel-body, .com_flexicontent .accordion.accordion-semi .panel-heading a, .com_flexicontent .accordion.accordion-semi .panel-heading.success a, .com_flexicontent .accordion.accordion-semi .panel-heading a.collapsed:hover, .pane-sliders .pane-toggler.title:hover, .pane-sliders .title.pane-toggler-down {
 background: <?php echo $this->params->get('flexiColor');
?>;
}
.pane-sliders .pane-toggler.title:hover a {
	color: #fff;
}
.pane-sliders .panel h3, .pane-sliders .panel h3 a, h3.tabberheading, .nav-tabs>li>a:hover, #flexicontent ul.tabbernav li a:hover, .flexicontent .accordion.accordion-semi .panel-heading a.collapsed:hover, #status a:hover, #status a:focus, dl.tabs dt.open span, dl.tabs dt.open h3 a, .flexicontent .nav-tabs > li > a:hover, .flexicontent .nav-tabs > li > a:active, .show-all, .show-all a, .com_flexicontent .accordion .panel-heading a, .accordion .panel-heading h4 a {
 color: <?php echo $this->params->get('flexiColor');
?>;
}
.color-primary, .purple {
 color: <?php echo $this->params->get('flexiColor');
?> !important;
}
#flexicontent ul.tabbernav li.tabberactive a, #flexicontent ul.tabbernav li.tabberactive a:hover, .nav-tabs>.active>a, .nav-tabs>.active>a:hover, .nav-tabs>.active>a:focus, .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
 color: <?php echo $this->params->get('flexiColor');
?>;
 border-top: 2px solid <?php echo $this->params->get('flexiColor');
?>;
}
dd.tabs dl.tabs dt.open, dl.tabs dt.open {
border-top: 3px solid <?php echo $this->params->get('flexiColor');
?>;
}
a.thumbnail:hover, a.thumbnail:focus {
border-color:  <?php echo $this->params->get('flexiColor');
?>;
}
.flexicontent .nav-tabs > li.active > a, .flexicontent .nav-tabs > li.active > a:hover, .flexicontent .nav-tabs > li.active > a:focus {
border-top: 2px solid <?php echo $this->params->get('flexiColor');
?>;
}
.tabs-left>.nav-tabs .active>a, .tabs-left>.nav-tabs .active>a:hover, .tabs-left>.nav-tabs .active>a:focus {
border-left: 2px solid <?php echo $this->params->get('flexiColor');
?>;
}

@media (max-width: 767px) {
.navbar-inverse .nav-collapse .nav > li > a:hover, .navbar-inverse .nav-collapse .nav > li > a:focus, .navbar-inverse .nav-collapse .dropdown-menu a:hover, .navbar-inverse .nav-collapse .dropdown-menu a:focus {
 background-color: <?php echo $this->params->get('flexiColor');
?>;
}
}
ul.adminmenum_menu li a:hover {
	background-color: transparent !important;
 color: <?php echo $this->params->get('flexiColor');
?> !important;
}
ul.adminmenum_menu li li a:hover, ul.adminmenum_menu li li a:active, ul.adminmenum_menu li li a:focus {
background-color: <?php echo $this->params->get('flexiColor');
?> !important;
	color: #fff !important;
}
/* background color li's on hover submenu and deeper */
ul.adminmenum_menu ul li a:hover {
	background-color: #0083C5;
}
ul.amm_menu_disabled li.amm_li_disabled a, ul.amm_menu_disabled li.amm_li_disabled a:hover {
	color: #555 !important;
}
.dropdown-toggle.menu-article:after, .dropdown-toggle.menu-category:after {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('flexiTags') == 0) : ?>
<style type="text/css">
.htags, #columnchoose_adminListTableFCitems_12, #columnchoose_adminListTableFCitems_12_label {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('flexiAuthor') == 0) : ?>
<style type="text/css">
.hauthor, #columnchoose_adminListTableFCitems_4_label, #columnchoose_adminListTableFCitems_4 {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('flexiLang') == 0) : ?>
<style type="text/css">
.hlang, #columnchoose_adminListTableFCitems_5_label, #columnchoose_adminListTableFCitems_5, #columnchoose_adminListTableFCitems_9_label, #columnchoose_adminListTableFCitems_9, #columnchoose_adminListTableFCcats_11_label {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('flexiOrder') == 0) : ?>
<style type="text/css">
.hflexiorder {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('flexiID') == 0) : ?>
<style type="text/css">
.hid, #columnchoose_adminListTableFCitems_16_label, #columnchoose_adminListTableFCitems_16  {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('featuredItems') == 0) : ?>
<style type="text/css">
.hfeat {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('keywords') == 0) : ?>
<style type="text/css">
.hkey {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('icons') == 0) : ?>
<style type="text/css">
#fcform_tabset_0_tab_3 .flexi_params .h-0, #fcform_tabset_0_tab_3 .flexi_params .h-1, #fcform_tabset_0_tab_3 .flexi_params .h-2, #fcform_tabset_0_tab_3 .flexi_params .h-3, #fcform_tabset_0_tab_3 .flexi_params .h-4 {
	display: none !important;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('loadCss')) : ?>
<style type="text/css">
<?php echo $this->params->get('loadCss');?>
</style>
<?php endif; ?>
<!--[if lt IE 9]>
	<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
<![endif]-->
</head>

<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?><?php if ($this->params->get('amm_used') != '0') : ?><?php echo ' amm';?><?php endif; ?> ">
<!-- Top Navigation -->

<nav class="navbar<?php echo $template_is_light ? '' : ' navbar-inverse'; ?> navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid w100"> 
      <!--Logo + Menu-->
      <div class="row-fluid w100">
        <div class="span2 logo-box"> <a class="site-logo <?php echo ($hidden ? 'disabled' : ''); ?>" <?php echo ($hidden ? '' : 'href="' . $this->baseurl . '"'); ?>><img src="<?php echo $logo; ?>" alt="<?php echo $sitename;?>" class="logox" /></a> </div>
        
        <!--NAVIGATION-->
        <div class="span10 bgcolor eq-height outvc preloadz">
          <div class="vc">
            <?php if ($this->params->get('admin_menus') != '0') : ?>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
            <?php endif; ?>
            <a class="brand hidden-desktop hidden-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_ISIS_PREVIEW', $sitename); ?>" target="_blank"><span class="icon-out-2 small"></span></a>
            <div<?php echo ($this->params->get('admin_menus') != '0') ? ' class="nav-collapse"' : ''; ?>>
              <jdoc:include type="modules" name="menu" style="none" />
              <ul class="nav nav-user<?php echo ($this->direction == 'rtl') ? ' pull-left' : ' pull-right'; ?>">
                <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="icon-user"></span> <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li> <span> <span class="icon-user"></span> <strong><?php echo $user->name; ?></strong> </span> </li>
                    <li class="divider"></li>
                    <li class=""> <a href="index.php?option=com_admin&amp;task=profile.edit&amp;id=<?php echo $user->id; ?>"><?php echo JText::_('TPL_ISIS_EDIT_ACCOUNT'); ?></a> </li>
                    <li class="divider"></li>
                    <li class=""><a href="<?php echo JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1'); ?>"><?php echo JText::_('TPL_ISIS_LOGOUT'); ?></a> </li>
                  </ul>
                </li>
              </ul>
              <a class="brand visible-desktop visible-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_ISIS_PREVIEW', $sitename); ?>" target="_blank"><span class="icon-out-2 small"></span></a> </div>
          </div>
        </div>
        <!--/NAVIGATION--> 
      </div>
      <!--/Logo + Menu--> 
    </div>
  </div>
</nav>
<?php if ($displayHeader) : ?>
<div class="xspace"></div>
<header class="header help20">
  <div class="container-title">
    <jdoc:include type="modules" name="title" />
  </div>
</header>
<?php endif; ?>
<?php if (!$cpanel) : ?>
<!-- Subheader --> 
<a class="btn btn-subhead" data-toggle="collapse" data-target=".subhead-collapse"><?php echo JText::_('TPL_ISIS_TOOLBAR'); ?> <i class="icon-wrench"></i></a>
<div class="subhead-collapse collapse">
  <div class="subhead">
    <div class="container-fluid">
      <div id="container-collapse" class="container-collapse"></div>
      <div class="row-fluid">
        <div class="span12">
          <jdoc:include type="modules" name="toolbar" style="no" />
        </div>
      </div>
    </div>
  </div>
</div>
<?php else : ?>
<!--<div style="margin-bottom: 20px"></div>-->
<?php endif; ?>
<!-- container-fluid -->
<div class="container-fluid container-main <?php if($cpanel ==1) :?>cpanel<?php endif; ?>">
<section id="content">

<!-- Begin Content -->
<jdoc:include type="modules" name="top" style="xhtml" />
<div class="row-fluid">
  <?php if ($showSubmenu) : ?>
  <div class="span2">
    <jdoc:include type="modules" name="submenu" style="none" />
  </div>
  <div class="span10 mainx">
    <?php else : ?>
    <div class="span12 mainx">
      <?php endif; ?>
      <jdoc:include type="message" />
      <?php
					// Show the page title here if the header is hidden
					if (!$displayHeader) : ?>
      <h1 class="content-title"><?php echo JHtml::_('string.truncate', $app->JComponentTitle, 0, false, false); ?></h1>
      <?php endif; ?>
      <jdoc:include type="component" />
    </div>
  </div>
  <?php if ($this->countModules('bottom')) : ?>
  <jdoc:include type="modules" name="bottom" style="xhtml" />
  <?php endif; ?>
  <!-- End Content -->
  
  <?php if ($cpanel) : ?>
  <div class="row-fluid version">
    <div class="span12">
      <hr>
      <p class="text-right grey"><small>
        <?php
jimport('joomla.version');
$version = new JVersion();

echo 'Joomla! - '.$version->getShortVersion();?>
        </small></p>
    </div>
  </div>
  <?php endif; ?>
  </section>
  <?php if (!$this->countModules('status') || (!$statusFixed && $this->countModules('status'))) : ?>
  <footer class="footer">
    <p align="center">
      <jdoc:include type="modules" name="footer" style="no" />
      &copy; <?php echo $sitename; ?> <?php echo date('Y'); ?></p>
  </footer>
  <?php endif; ?>
</div>
<?php if (($statusFixed) && ($this->countModules('status'))) : ?>
<!-- Begin Status Module -->
<div id="status" class="navbar navbar-fixed-bottom hidden-phone">
  <div class="btn-toolbar">
    <div class="btn-group pull-right">
      <p>
        <jdoc:include type="modules" name="footer" style="no" />
        &copy; <?php echo date('Y'); ?> <?php echo $sitename; ?> </p>
    </div>
    <jdoc:include type="modules" name="status" style="no" />
  </div>
</div>
<!-- End Status Module -->
<?php endif; ?>
<jdoc:include type="modules" name="debug" style="none" />
<?php if ($stickyToolbar) : ?>
<script>
		jQuery(function($)
		{

			var navTop;
			var isFixed = false;

			processScrollInit();
			processScroll();

			$(window).on('resize', processScrollInit);
			$(window).on('scroll', processScroll);

			function processScrollInit()
			{
				if ($('.subhead').length) {
					navTop = $('.subhead').length && $('.subhead').offset().top - <?php echo ($displayHeader || !$statusFixed) ? 30 : 20;?>;

					// Only apply the scrollspy when the toolbar is not collapsed
					if (document.body.clientWidth > 480)
					{
						$('.subhead-collapse').height($('.subhead').height());
						$('.subhead').scrollspy({offset: {top: $('.subhead').offset().top - $('nav.navbar').height()}});
					}
				}
			}

			function processScroll()
			{
				if ($('.subhead').length) {
					var scrollTop = $(window).scrollTop();
					if (scrollTop >= navTop && !isFixed) {
						isFixed = true;
						$('.subhead').addClass('subhead-fixed');
					} else if (scrollTop <= navTop && isFixed) {
						isFixed = false;
						$('.subhead').removeClass('subhead-fixed');
					}
				}
			}
		});
	</script>
<?php endif; ?>
</body>
</html>
