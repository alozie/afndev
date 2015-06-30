<!DOCTYPE html>
<!--[if IE 7]> <html class="ie7 lt-ie9 lt-ie8 no-js" <?php print $html_attributes; ?>> <![endif]-->
<!--[if IE 8]> <html class="ie8 lt-ie9 no-js" <?php print $html_attributes; ?>> <![endif]-->
<!--[if IE 9]> <html class="ie9 no-js" <?php print $html_attributes; ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php print $html_attributes ?>> <!--<![endif]-->
<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <meta name="HandheldFriendly" content="true">
  <meta name="MobileOptimized" content="width">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="cleartype" content="on">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>">
  <a href="#main" class=""><?php print t('Skip to main content'); ?></a>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
