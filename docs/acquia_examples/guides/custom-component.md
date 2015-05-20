# Custom pane that can be added to panelized pages or page manager pages.

## References
- acquia_examples.module
- plugins/content_types/example_pane.inc
- templates/acquia-examples-example-pane.tpl.php

## Quick Overview
Custom components in Drupal can be created by defining how they are themed in the `hook_theme()` function. We recommend the use of templates for consistency. In this method, the template is passed a render array and each portion of the custom component is then rendered inside the template.

In this example, we are using a ctools pane plugin to build create our component and the `hook_theme()` and template to display the content. This can be done with other types of components as well.
