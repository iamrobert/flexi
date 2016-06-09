<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app  = JFactory::getApplication();
$doc  = JFactory::getDocument();
$lang = JFactory::getLanguage();

// Color Params
$template_is_light = ($this->params->get('templateColor') && colorIsLight($this->params->get('templateColor')));

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.tooltip');

// Add Stylesheets
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template' . ($this->direction == 'rtl' ? '-rtl' : '') . '.css');

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Load specific language related CSS
$file = 'language/' . $lang->getTag() . '/' . $lang->getTag() . '.css';

if (is_file($file))
{
	$doc->addStyleSheet($file);
}

// LOGO

if ($this->params->get('loginLogoFile') != "")
{
	$logo = JUri::root() . $this->params->get('loginLogoFile');
}
elseif ($this->params->get('logoFile'))
{
	$logo = JUri::root() . $this->params->get('logoFile');
}
else
{
	$logo = $this->baseurl . '/templates/' . $this->template . '/images/joomla.png';
}

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<jdoc:include type="head" />
	<script type="text/javascript">
        jQuery(function($) {
            $( "#form-login input[name='username']" ).focus();
        });
	</script>
	<style type="text/css">
/* Template color */
		
.view-login {
	background: #f0f0f0;
}


.logox {
	<?php if ($this->params->get('templateColor')) : ?>
	background: <?php echo $this->params->get('templateColor'); ?>;
	<?php endif; ?>
	border: 1px solid #e2e2e2;
	border-bottom: none;
	padding: 5px 0;
}



.well {
	background: #fff;
	border-top: 0;
}

/* Responsive Styles */
@media (max-width: 480px) {
.view-login .container {
	margin-top: -170px;
}
.btn {
	font-size: 13px;
	padding: 4px 10px 4px;
}
}
 <?php // Check if debug is on ?>  <?php if ($app->get('debug_lang', 1) || $app->get('debug', 1)) : ?>  .view-login .container {
 position: static;
 margin-top: 20px;
 margin-left: auto;
 margin-right: auto;
}
.view-login .navbar-fixed-bottom {
	display: none;
}
 <?php endif;
?>  <?php if ($this->params->get('flexiColor')) : ?>  .loginform .btn-primary {
 background-color: <?php echo $this->params->get('flexiColor');
?>;
 border: 1px solid <?php echo $this->params->get('flexiColor');
?>;
}
<?php endif;
?>  .loginform .btn-primary:hover, .loginform .btn-primary:active {
 background-color:#dadada;
 border: 1px solid #dadada;
}
.view-login .navbar-fixed-bottom, .view-login .navbar-fixed-bottom a {
	color: #ccc;
}
</style>

	<!--[if lt IE 9]>
		<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
	</head>

	<body class="site <?php echo $option . " view-" . $view . " layout-" . $layout . " task-" . $task . " itemid-" . $itemid . " "; ?>">
<!-- Container -->
<div class="container">
      <div id="content"> 
    <!-- Begin Content -->
    
    <div class="logox center"> <img src="<?php echo $logo; ?>" class="logo" alt="<?php echo $sitename;?>" /> </div>
    <div id="element-box" class="login well">
          <jdoc:include type="message" />
          <jdoc:include type="component" />
        </div>
    <noscript>
        <?php echo JText::_('JGLOBAL_WARNJAVASCRIPT'); ?>
        </noscript>
    <!-- End Content --> 
  </div>
    </div>
<div class="navbar<?php echo $template_is_light ? ' navbar-inverse' : ''; ?> navbar-fixed-bottom hidden-phone">
      <p class="pull-right"> &copy; <?php echo date('Y'); ?> <?php echo $sitename; ?> </p>
      <a class="login-joomla hasTooltip" href="http://www.joomla.org" target="_blank" title="<?php echo JHtml::tooltipText('TPL_ISIS_ISFREESOFTWARE'); ?>"><span class="icon-joomla"></span></a> <a href="<?php echo JUri::root(); ?>" target="_blank" class="pull-left"><span class="icon-out-2"></span> <?php echo JText::_('COM_LOGIN_RETURN_TO_SITE_HOME_PAGE'); ?></a> </div>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
