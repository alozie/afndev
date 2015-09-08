<?php
/**
 * @file
 * Classy Panel Styles API include file.
 */

/**
 * Implements hook_classy_panel_styles().
 *
 * Provides a set of example Classy Panel Styles definitions.
 */
function classy_panel_styles_classy_panel_styles() {
  $export = array();

  $style = new stdClass();
  $style->disabled = FALSE; /* Edit this to true to make a default style disabled initially */
  $style->api_version = 1;
  $style->name = 'width';
  $style->visibility = 1;
  $style->groups = 'a:1:{i:0;s:11:"cps_default";}';
  $style->title = 'Width';
  $style->required = TRUE;
  $style->description = 'Change the width';
  $style->options = 'a:6:{s:9:"width-100";s:10:"Full Width";s:8:"width-75";s:9:"3/4 Width";s:8:"width-66";s:9:"2/3 Width";s:8:"width-50";s:9:"1/2 Width";s:8:"width-33";s:9:"1/3 Width";s:8:"width-25";s:9:"1/4 Width";}';
  $style->default_value = 'width-100';
  $style->layouts_setting = 0;
  $style->layouts = 'a:0:{}';
  $export['width'] = $style;

  return $export;
}
