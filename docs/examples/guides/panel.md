# Custom pane that can be added to panelized pages or page manager pages.

## References
- modules/custom_component_example/custom_component_example.module
- modules/custom_component_example/plugins/content_types/example_pane.inc
- modules/custom_component_example/templates/custom-component-example-example-pane.tpl.php

## Quick Overview
We can define custom panes that can be configured and placed in Drupal. Panes use ctools and its plugin system. As such, we define the plugin types we are going to use and define all the pane specific information in the `.inc` file. For more advanced panes, you can use variables from context, etc. For consistency, we define that this pane should be themed using a template file which renders each part of the pane.
