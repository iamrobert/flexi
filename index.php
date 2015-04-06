<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
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


//CURRENT TEMPLATE
$tpath = $this->baseurl.'/templates/'.$this->template;

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScriptVersion($this->baseurl . '/templates/' . $this->template . '/js/template.js');

// HIDE FLEXICONTENT CSS
unset($this->_styleSheets[JURI::base( true ).'/components/com_flexicontent/assets/css/flexicontentbackend.css']);
unset($this->_styleSheets[JURI::base( true ).'/components/com_flexicontent/assets/css/j3x.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/librairies/select2/select2.css']);
unset($this->_styleSheets[JURI::root( true ).'/components/com_flexicontent/assets/css/tabber.css']);
// Add Stylesheets
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/select2/select2.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/flex3x.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/template.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/layout.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/forms.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/style.css');
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/flexi.css'); 

// Load specific language related CSS
$file = 'language/' . $lang->getTag() . '/' . $lang->getTag() . '.css';


if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}





// Detecting Active Variables
$option   = $input->get('option', '');
$view     = $input->get('view', '');
$layout   = $input->get('layout', '');
$task     = $input->get('task', '');
$itemid   = $input->get('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename', ''), ENT_QUOTES, 'UTF-8');
$cpanel   = ($option === 'com_cpanel');


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

// Logo file
if ($this->params->get('logoFile'))
{
	$logo = JUri::root() . $this->params->get('logoFile');
}
else
{
	$logo = $this->baseurl . '/templates/' . $this->template . '/images/logo.png';
}

// Template Parameters
$displayHeader = $this->params->get('displayHeader', '1');
$statusFixed   = $this->params->get('statusFixed', '1');
$stickyToolbar = $this->params->get('stickyToolbar', '1');
$featuredItems = $this->params->get('featuredItems', '1');
$flexiTags = $this->params->get('flexiTags', '1');
$flexiLang = $this->params->get('flexiLang', '1');
$flexiOrder = $this->params->get('flexiOrder', '1');
$flexiID = $this->params->get('flexiID', '1');
$flexiAuthor= $this->params->get('flexiAuthor', '1');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?php /*?><link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'><?php */?>
<jdoc:include type="head" />


<?php if ($this->params->get('linkColor')) : ?>
<style type="text/css">
a, .j-toggle-sidebar-button {color: <?php echo $this->params->get('linkColor');?>;}
</style>
<?php endif; ?>

<?php if ($this->params->get('hoverColor')) : ?>
<style type="text/css">
#cpanel div.icon a:hover, #cpanel div.icon a:focus, #cpanel div.icon a:active, .cpanel div.icon a:hover, .cpanel div.icon a:focus, .cpanel div.icon a:active {border-color: <?php echo $this->params->get('hoverColor');?>}

a:hover, a:focus, .j-toggle-sidebar-button:hover {color: <?php echo $this->params->get('hoverColor');?>;}
</style>
<?php endif; ?>

<?php if ($this->params->get('flexiColor')) : ?>
<style type="text/css">
.logo-box, .fcsep_level2, .com_flexicontent .accordion.accordion-semi .panel-heading a {
 background: <?php echo $this->params->get('flexiColor');
?>;
}
.purple, .pane-sliders .panel h3, .pane-sliders .panel h3 a, h3.tabberheading, .nav-tabs>li>a:hover, #flexicontent ul.tabbernav li a:hover, .com_flexicontent .accordion.accordion-semi .panel-heading a.collapsed:hover {
 color: <?php echo $this->params->get('flexiColor');
?>;
}
#flexicontent ul.tabbernav li.tabberactive a, #flexicontent ul.tabbernav li.tabberactive a:hover, .nav-tabs>.active>a, .nav-tabs>.active>a:hover, .nav-tabs>.active>a:focus, .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
 color: <?php echo $this->params->get('flexiColor');?>;
 border-top: 2px solid <?php echo $this->params->get('flexiColor');?>;
}


a.thumbnail:hover, a.thumbnail:focus {
border-color:  <?php echo $this->params->get('flexiColor');?>;
}



ul.adminmenum_menu li li a:hover, ul.adminmenum_menu li li a:active, ul.adminmenum_menu li li a:focus {
background-color: <?php echo $this->params->get('flexiColor');
?>;
}
</style>
<?php endif; ?>
<?php if ($this->params->get('buttonColor')) : ?>
<?php endif; ?>
<?php if ($this->params->get('buttonhoverColor')) : ?>
<style type="text/css">
.fcfield-button:hover, .fcfield-button:active, .fcfield-button.active {
 background: <?php echo $this->params->get('buttonhoverColor');
?>;
}
</style>
<?php endif; ?>



<?php if ($this->params->get('flexiTags') == 0) : ?>
<style type="text/css">
.htags, #columnchoose_adminListTableFCitems_12_label {
	display: none !important;
}
</style>
<?php endif; ?>

<?php if ($this->params->get('flexiAuthor') == 0) : ?>
<style type="text/css">
.hauthor, #columnchoose_adminListTableFCitems_4_label {
	display: none !important;
}
</style>
<?php endif; ?>

<?php if ($this->params->get('flexiLang') == 0) : ?>
<style type="text/css">
.hlang, #columnchoose_adminListTableFCitems_5_label {
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
.hid, #columnchoose_adminListTableFCitems_15_label {
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

<!--[if lt IE 9]>
	<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?>">
<!-- Top Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<div class="container-fluid w100">
  <div class="row-fluid">
    <div class="span2 logo-box eq-height outvc">
      <div class="vc50"><a class="site-logo <?php echo ($hidden ? 'disabled' : ''); ?>" <?php echo ($hidden ? '' : 'href="' . $this->baseurl . '"'); ?>><img src="<?php echo $logo; ?>" alt="<?php echo $sitename;?>" class="logox" /></a></div>
    </div>
    <div class="span10 bgcolor eq-height outvc"> 
      <!--VERTICAL CENTER-->
      
      <div class="vc"> 
        <!--NAVIGATION-->
        <?php if ($this->params->get('admin_menus') != '0') : ?>
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
        <?php endif; ?>
        <a class="brand hidden-desktop hidden-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_ISIS_PREVIEW', $sitename); ?>" target="_blank">
        <?php /*?><?php echo JHtml::_('string.truncate', $sitename, 14, false, false); ?><?php */?>
        <span class="icon-out-2 small"></span></a>
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
        <!--/NAVIGATION--> 
        
      </div>
      <!--/VERTICAL CENTER--> 
      
    </div>
  </div>
  <!--/NAVIGATION--> 
  
</div>
</nav>
<!-- Header -->
<?php if ($displayHeader) : ?>
<header class="header">
  <div class="container-title">
    <jdoc:include type="modules" name="title" />
  </div>
</header>
<?php endif; ?>
<?php if ((!$statusFixed) && ($this->countModules('status'))) : ?>
<!-- Begin Status Module -->
<div id="status" class="navbar status-top hidden-phone">
  <div class="btn-toolbar">
    <jdoc:include type="modules" name="status" style="no" />
  </div>
  <div class="clearfix"></div>
</div>
<!-- End Status Module -->
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
<div style="margin-bottom: 20px"></div>
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
      <?php
	// Show the page title here if the header is hidden
					if (!$displayHeader) : ?>
      <h1 class="content-title"><?php echo JHtml::_('string.truncate', $app->JComponentTitle, 0, false, false); ?></h1>
      <?php endif; ?>
      <jdoc:include type="component" />
      <br class="clear">
    </div>
  </div>
  <?php if ($this->countModules('bottom')) : ?>
  <jdoc:include type="modules" name="bottom" style="xhtml" />
  <?php endif; ?>
  <!-- End Content -->
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
