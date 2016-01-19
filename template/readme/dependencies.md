# Dependency Management

All project and Drupal (module, themes, libraries) dependencies are managed
via Composer. The management strategy is based on [The Drupal Project](https://github.com/drupal-composer/drupal-project)

# Drupal contributed project dependencies

Modules, themes, and other contributed drupal projects can be added as
dependencies in the root composer.json file.

To install or update contributed dependencies, simply run `composer install`
or `composer update`.

# Drupal core

To update drupal core:
1. Update the entry for `drupal/core` in the root composer.json. 
2. Run `composer update`.
3. Run `./scripts/drupal/update-scaffold`. This will update the core files not
   included in `drupal/core`.
4. Use git to review changes to committed files. E.g., changes to `.htaccess`, 
   `robots.txt`, etc. 
5. Add and commit desired changes. 
