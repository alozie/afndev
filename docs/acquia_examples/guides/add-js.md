# Javascript in Drupal

## References
- acquia_examples.module
- js/acquia_examples.js
- js/example_library.js

## Quick Overview
To add javascript in Drupal, we recommend the use of `#attached` in render arrays when javascript is associated with specific content like blocks and panels. See `acquia_examples_block_view()`.

To add javascript in other situations, `drupal_add_js()` and `drupal_add_library()` are recommended. See `acquia_examples_preprocess_page()`.
