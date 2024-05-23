# Lifted Logic Base Theme
###### README Version 1.0.0
This document details how to use Lifted Logic's custom WordPress theme.

If you run into issues getting your development environment set up, please contact aaronc@liftedlogic and kyle@liftedlogic.com for assistance. It is vital to use source control properly for themes that are continuously being edited by Lifted Logic and third party developers at the same time. 

## Requirements
  - [Node.js](https://nodejs.org/en/download/) - Use v7.10.1
  - [Bower](https://bower.io/)
  - [Grunt](https://gruntjs.com/installing-grunt)
  - [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) - Installed automatically on plugin activation
  - Access to the theme repository on [Beanstalk](https://lifted-logic.beanstalkapp.com/) - Version Control

## Helpful Tools
  - [Node Version Manager](https://github.com/creationix/nvm) - Allows for quick changing between Node.js versions
  - [Local By Flywheel](https://local.getflywheel.com/) - Easy-to-use complete development environment for WordPress sites
  - [SourceTree](https://www.sourcetreeapp.com/) - GUI client for source control

## Source Control Setup
  - You must have an account set up for you on Beanstalk before working on a Lifted Logic theme
  - Login to Beanstalk, click on your name in the top right to edit your account.
  - Click on the SSH Keys tab, click "Add public key", and enter your public SSH key. If you do not have one, follow steps online to generate an SSH key for yourself.
  - Click on Repositories, and open the theme repository page you are wanting to make changes to. The Git reposity URL is shown in the upper right.
  - Pull down the repository using SourceTree or your preferred method.
  - Create a branch off of the master branch to do your development. When the work is completed and working properly, merge the branch back into master.

## Theme Deployment
  - To deploy your changes to the live website (or staging site), go to the reposity in Beanstalk. Click the Deployments link, select the server to deploy to, and confirm the commit to deploy.

## Development Environment Setup
  - Once the theme has been pulled down, open the theme folder in the editor of your choice
  - Duplicate the config.json.example file, and rename it config.json.
  - Open Terminal, and change directory to the theme folder
  - Run the command `bower install` and then `npm install` to install the theme dependencies
  - Run the command `grunt` to begin listening for changes to the .js and .scss files (these compile into main.min.css and scripts.min.js)

## Common Issues
  - After running `npm install`, there may be some errors that appear concerning bower. These can be ignored if running `bower install` works fine.
  - If there are version issues that appear when running `npm install`, delete /node_modules and /resources/vendor folders and rerun `npm install` and `bower install`. Make sure you are using Node version 7.10.1 (`node -v`). Change Node version with Node Version Manager.

## Theme Structure
Overview of the important files present across the theme.

  - __base.php__ - Wrapper framework around all pages of the site
  - __Gruntfile.js__ - Grunt settings and tasks
  - __page.php__ - Default Template
  - __single.php__ - Single post type template
  - __functions.php__ - This file includes all custom PHP function files for the theme. Do not add functions here - look in __/lib/custom__ instead.

### /acf-json
  - Automatically created .json files associated with the Advanced Custom Fields plugin. This ensures custom field groups are automatically synced between environments without a need to export/import field groups that have changed.

### /assets
#### /assets/css
  - __main.min.css__ - Compiled file including all custom SCSS and vendor CSS

#### /assets/img
  - __symbol-devs.svg__ - Symbol definitions for SVG icons
  - `<svg class="icon icon-namegoeshere"><use xlink:href="#icon-namegoeshere"></use></svg>`

#### /assets/js
  - __scripts.min.js__ - Compiled file including all custom JS and vendor JS
  - __admin.js__ - WordPress Admin-specific JavaScript

### /components
  - This folder contains components that can be used throughout the site
  - Create new component: `grunt generate:component:"Component Name"`
  - This would create a new component and it's own php, js, and scss files in  __/components/component-name__
  - When generating a new component, the js and scss are automatically included and compiled
  - Call the compoment:
  ```
  <?php
  ll_include_component(
    'component-name',
    [
      'parameter' => value,
    ],
    [
      'classes' => ['']
      'id' => ''
    ]
  );
  ```

### /generate
  - This contains the templates used when generating a new component

### /lib
  - __init.php__ - Initialize widgets, menus, and theme support activation
  - __nav.php__ - Custom Nav walker
  - __scripts.php__ - Enqueue and register various scripts

#### /lib/cpt
  - __cpt-name__ - Register custom post type
  - __main.php__ - Include CPT files in runtime

#### /lib/custom
  - Contains custom function files for Lifted Logic themes
  - All of these function files are included in the root __functions.php__ file
  - Do not write functions in __functions.php__, add functions to the appropriate file in these folders (or add you own new file and require it in __/lib/custom/main.php__)

#### /lib/metabox
  - Custom functions for advanced custom fields

### /resources
#### /resource/js
  - ___common.js__ - Javascript the is run globally
  - ___modal.js__ - Magnific Popup modal component
  - Javascript components are included when needed by adding a data attribute on the page that it is required.
  - EX: `<button class="js-init-popup" data-modal="#popup-id" data-component="modal">Open popup</button>`

#### /resource/sass
  - __main.scss__ - All SCSS files must be imported here to be compiled
  - __app.scss__ - Components are automatically added here when generated to be compiled

##### /resource/sass/base
  - Base styles, reset file, and typography

##### /resource/sass/helpers
  - Helper functions, mixins, utilities, and variables

##### /resource/sass/layout
  - Layout, grid, footer styles  

##### /resource/sass/pages
  - Page-specific styles

##### /resource/sass/partials
  - Styles that don't quite fit in components or specific pages, often on pieces that are shared across the theme

### /templates
  - __template-name__ - Create templates in this directory

#### /templates/partials
  - __footer.php__ - Sitewide footer
  - __head.php__ - Sitewide head element
  - __header.php__ - Sitewide footer
