# Custom layout that can be used in panelizer or page manager pages

## References
- modules/panel_layout_example/panel_layout_example.module
- modules/panel_layout_example/plugins/layouts/example_layout/example_layout.inc
- modules/panel_layout_example/plugins/layouts/example_layout/example-layout.tpl.php
- modules/panel_layout_example/plugins/layouts/example_layout/example_layout.png

## Quick Overview
Custom layouts are a ctools plugin. As such, we let ctools know that we are including layouts and then add the three files in the layouts directory. The `.inc` defines the regions for our layout and the `.tpl.php` file allows us to add markup around this layout.
