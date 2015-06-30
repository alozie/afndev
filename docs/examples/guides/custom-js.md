# Javascript in Drupal

## References
- modules/add_js_example/js/example.js

## Quick Overview
Javascript in Drupal is designed around the Behaviors system. In this system, whenever content is added to the page, such as on page load, or on AJAX, Drupal calls the function `Drupal.attachBehaviors()`. This runs all `attach` functions defined under `Drupal.behaviors`. For example `Drupal.behaviors.myModule`. This pattern ensures that AJAX content and other content receives all the functionality that content generated on page load recieves.

The example file uses this pattern and also demonstrates how you can retrieve variables passed to the frontend using `Drupal.settings`.
