# Overview
The theming examples focus on the theme build process and foundational structure of the theme. There are many ways to approach frontend theming. For now, the examples do not assume any particular approach to creating the CSS or Javascript or particular markdown.

As such, this is NOT a base theme. This is intended as a structure to get a project started using common build process tools.

## Build Tools
The build tools in this example theme use node.js based tools. This includes a node based build tool Gulp as well as a node.js based version of SASS. This simplifies the process over some alternatives since fewer tools must be installed.

### Node.js
To install node.js, see [nodejs.org](https://nodejs.org/)

If you are using OSX and brew, you can instlal node with `brew install node`.

### npm (Node.js Package Manager)
npm comes bundled with node.js, so you should already have it.

npm uses the concept of a package file that lists all of the dependencies for the project. This package list will then be used to install local versions of these libraries for use on the project. They are usually not committed to the repository and are excluded in `.gitignore`.

Gulp is one of these libraries. All of the components that gulp uses are also included in the `package.json` file.

#### Install a package
run `npm install`

#### Update a package
run `npm install <my-package> --save-dev`

The `--save-dev` tells npm to update package.json.

#### Initial setup
`package.json` also includes some site specific meta-data that should be updated for each project.

### Gulp (Node.js based build tool)
Gulp is one of the libraries that is installed with the package. Once all the libraries are installed, gulp will be the command most frequently run.

#### Install
See [https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)

If you have not already installed gulp globally, you must do so now. This makes gulp available from the command line. The local version installed with node.js will still be used to run the actual tasks.

run `npm install --global gulp`

#### Running Gulp
Like many build tools such as SASS and Compass and Grunt, Gulp defines several tasks. These tasks do things like compile the source files into JS and CSS files or launch browser-sync tool.

The simplest set up is to run `gulp`. This will run the default task.

`gulp build` will run the build task and then exit.

`gulp watch` will run the build task and then wait for changes and rebuild as needed.

`gulp live-edit` will run BrowserSync and allow you to edit files as see the changes immediately in the browser.

#### Customizing and configuring Gulp
The `gulpfile.js` file is the settings file for Gulp. This is where all of the libraries are loaded and the tasks defined. There is more documentation in that file.

## Drupal Theme
The Drupal theme is intended as a base structure that will be customized per project.

### Info File
The `example_theme.info` file defines the regions and loads the main `scripts.js` and `styles.css` files for the theme. 

### Templates
These files demonstrate example HTML, Page, and Panels templates that are good starting points for projects.

### Template.php
This is where all of your theme hooks would go. This project does not assume any specific methods will be used.
