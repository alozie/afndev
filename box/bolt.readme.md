## Virtual Machine

Bolt ships with tasks for installing [Drupal VM](https://github.com/geerlingguy/drupal-vm), 
simple virtual machine built on Ansible and Vagrant.

For full instructions on how to configure and install the packaged VM, see 
[Drupal VM](https://github.com/geerlingguy/drupal-vm) documentation. 

### Requirements

If you'd like to use Drupal VM, you will need to install
the following dependencies:

* VirtualBox 4.3.x [Download](https://www.virtualbox.org/wiki/Downloads)
  * Drupal VM also works with Parallels or VMware if you have the [Vagrant VMware integration plugin](http://www.vagrantup.com/vmware))
* Vagrant 1.7.2 or higher [Download](http://www.vagrantup.com/downloads.html)
* Vagrant Host Updater
  * Instructions: `vagrant plugin install vagrant-hostsupdater`
* Ansible 1.9.2 or higher [Install](http://docs.ansible.com/intro_installation.html).
  * Mac / Linux only
  * OSX instructions from Homebrew: `brew install ansible`
  * Linux instructions:
    * `sudo easy_install pip`
    * `sudo CFLAGS=-Qunused-arguments CPPFLAGS=-Qunused-arguments pip install ansible`

### Installation

The Drupal VM can be installed with pre-configured settings to make it
compatible with Bolt out of the box. Run the follow command to install:

`./task.sh vm:add`

This will download and bootstrap the Drupal VM using the box/bolt.config.yml
configuration file.
