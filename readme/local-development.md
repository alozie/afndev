
# Local Development

All development for a Drupal site should be done locally, then once complete, it should be committed to a repository and deployed to other environments (eventually to production, if it passes all tests and acceptance criteria).

It's important that developers have a reliable and functional local development environment, and there are many options to choose from. Acquia currently recommends the use of [Drupal VM](http://www.drupalvm.com/) or [Acquia Dev Desktop](https://www.acquia.com/products-services/dev-desktop).

## Using Drupal VM for Bolt-generated projects

To use Drupal VM with a Drupal project that is generated with Bolt, first place your downloaded copy of Drupal VM inside the generated Drupal project folder, and name the drupal-vm directory `box`.

Follow the Quick Start Guide in [Drupal VM's README](TODO), but before you run `vagrant up`, make the following changes to your VM `config.yml` file:

    # Update the hostname to the local development environment hostname.
    vagrant_hostname: [project_local_domain]

    # Provide the path to the project docroot to Vagrant.
    vagrant_synced_folders:
      # Set the local_path for the first synced folder to `../docroot`.
      - local_path: ../docroot
        # Set the destination to the Acquia Cloud subscription machine name.
        destination: /var/www/[project_acquia_subname]
        type: nfs

    # Set this to `7` for a Drupal 7 site, or `8` for a Drupal 8 site.
    drupal_major_version: 8

    # Set drupal_core_path to the same as the `destination` in the synced folder
    # configuration above.
    drupal_core_path: /var/www/[project_acquia_subname]

    # Set drupal_domain to the same thing as the `vagrant_hostname` above.
    drupal_domain: [project_local_domain]

    # Set drupal_site_name to the project's human-readable name.
    drupal_site_name: [project_human_name]

    # If you build the makefile using Bolt's built-in Phing task, set
    # `build_makefile` to `false`.
    build_makefile: false

    # If you need to install the site inside the VM, set `install_site` to
    # `true`. Otherwise, after you build the VM, you can import the database
    # using Drush, Adminer, or any other method of connecting to the MySQL
    # database.
    install_site: true

There are also other changes you can make if you choose to match the Acquia Cloud server configuration more closely. See Drupal VM's example configuration changes in Drupal VM's `examples/acquia/acquia.overrides.yml` file.

Once you've made these changes and completed the steps in Drupal VM's Quick Start Guide, you may run `vagrant up` to bring up your local development environment, and then access the site via the configured `drupal_domain`.

## Alternative local development environments

For reasons, some teams may prefer to use a different development environment. Drupal VM offers a great deal of flexibility and a uniform configuration for everyone, but sometimes a tool like Acquia Dev Desktop, MAMP/XAMPP, or a different environment (e.g. a bespoke Docker-based dev environment) may be preferable.

It is up to each team to choose how to handle local development, but some of the main things that help a project's velocity with regard to local development include:

  - Uniformity and the same configuration across all developer environments.
  - Ease of initial environment configuration (if it takes more than an hour to get a new developer running your project locally, you're doing it wrong).
  - Ability to emulate all aspects of the production environment with minimal hassle (e.g. Varnish, Memcached, Solr, Elasticsearch, different PHP versions, etc.).
  - Helpful built-in developer tools (e.g. XHProf, Xdebug, Adminer, PimpMyLog).
  - Ease of use across Windows, Mac, or Linux workstations.

For these reasons and many others, Drupal VM is the recommended solution as it achieves all these goals and is an actively-maintained Drupal community project. If you choose to use a different solution, please make sure it fits all the needs of your team and project, and will not be a hindrance to project development velocity!