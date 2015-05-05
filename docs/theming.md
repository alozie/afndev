#Drupal Theming Best Practices

##TOC
- [Purpose](http://github.com/torq/Drupal-Theming-Best-Practices#purpose)
- [Base Theme](http://github.com/torq/Drupal-Theming-Best-Practices#base-theme)
- [Grid System](http://github.com/torq/Drupal-Theming-Best-Practices#grid-system)
- [Markup](http://github.com/torq/Drupal-Theming-Best-Practices#markup)
- [Style Organization](http://github.com/torq/Drupal-Theming-Best-Practices#style-organization)
- [CSS Preprocessing](http://github.com/torq/Drupal-Theming-Best-Practices#css-preprocessing)
- [Style Rules](http://github.com/torq/Drupal-Theming-Best-Practices#style-rules)
- [Conditional CSS](http://github.com/torq/Drupal-Theming-Best-Practices#conditional-css)
- [Media Queries](http://github.com/torq/Drupal-Theming-Best-Practices#media-queries)
- [Images](http://github.com/torq/Drupal-Theming-Best-Practices#images)
- [Template Files](http://github.com/torq/Drupal-Theming-Best-Practices#template-files)
- [Working with Javascript](http://github.com/torq/Drupal-Theming-Best-Practices#working-with-javascript)
- [Links](http://github.com/torq/Drupal-Theming-Best-Practices#links)

##Purpose
We have a lot more bandwidth now, and browsers are better now, than 20 years ago. However, sites have gotten a lot more complicated than they were 20 years ago. So in some cases, the following guidelines may seem unnecessary. Even so, when theming a site, every little bit counts, so the following things should be kept in mind.

##Base Theme
Choose a base theme based on the project design. In most cases this calls for a clean unstyled theme that sets up a solid foundation. Two such themes are Zen 5 ( https://drupal.org/project/zen ) & Basic ( https://drupal.org/project/basic ). These themes are HTML5, come with a grid system, use SASS that is organized based on SMACSS guidelines, and have small footprints. Out of those two base themes, we recommend Zen 5, because it also supports the Compass plug-in for SASS.

In most situations a full blown framework as a base theme isn’t recommended. Frameworks such as bootstrap or foundation are great starting points when the site is designed based on the framework. But, when they aren’t, they can add over 100kb & 6000 lines of CSS to a project that is mostly being overwritten or largely unused.

##Grid System
When creating a theme you can be tempted to not use a grid system, or to create your own grid system. However, this technique breaks down when there are multiple themers and in many multi-language sites. Grid systems and base themes allow the layouts to adapt to RTL language sites. Using and sticking to a grid system provides consistency when there are multiple themers working on multiple parts of a project.

##Markup
Consistent markup makes life easier. Drupal's markup often isn't very pleasant to play with. Luckily, we have tpl files and preprocessors. Performance-wise, tpl files are less efficient, but the volume of tpl files required to seriously degrade performance is pretty large. Just like building a site outside of Drupal, you want to resuse html elements for both hierarchy and consistentency. If you have a styleguide that lays out the basic styles for elements, then you can use the styleguide module to create these base styles. Then you can match up your comps with the style guide, and use tpl files for the fields (or the fences module which is a set of tpl files) to create the markup to support the comps.

If you have shared styles across multiple html elements, you can use theme preprocessors to add a consistent class to style all fields with one rule.

Things like panelizer add a lot of DOM bloat, so simplifying the DOM, and making clean and consistent markup whenever possible is important. 

##Styles

### Style Organization
When organizing styles, group styles based on the SMACSS ( http://smacss.com/ ) guidelines. If using a base theme like Zen 5 or Basic, the SASS directory is organized like this by default. Rules are broken up into 5 main areas.
- **Base Rules** > Bases styles for HTML elements and normalization rules
- **Layout Rules** > Styles setting up widths and placement of regions
- **Component Rules** > The majority of your rules. Should be in a partials directory. Partials should be semantically named for the component they apply to.
- **State Rules** > These are often utility styles applied or toggled by javascript. Quicktabs, collapsible sections, show/hide.
- **Theme Rules** > These are theme styles for things like page background, typography, colors, etc.

Component rules are commonly referred to as *module rules* when discussing SMACSS, however, the term *module styles* means something completely different when dealing with Drupal. It is recommended to add media queries in the relevant component styles and not with the state style rules.

*With SMACSS, the intent is to keep the styles that pertain to a specific component with the rest of the component. That means that instead of having a single break point, either in a main CSS file or in a separate media query style sheet, place media queries around the component states.*

Example rule with media query.

**CSS Version**

```css
.col-b {
  width: 40%;
  float: left;
  margin-left: 5%;
}

@media screen and (max-width: 767px) {
  .col-b {
    width: 100%;
    margin-left: 0;
  }
}
```

**SASS Version**
```scss
.col-b {
  width: 40%;
  float: left;
  margin-left: 5%;
  @media screen and (max-width: 767px) {
    width: 100%;
    margin-left: 0;
  }
}
```

###CSS Preprocessing
CSS preprocessors allow themers to be more efficient when developing a sub theme. We prefer SASS, using scss syntax, and using Compass. These are the Zen 5 defaults and work well. We recommend using Compass for creating sprites for your site using two sprite directories, one for standard resolution, one for retina resolutions.

The majority of your scss files should be partials. These partials are then imported into a single scss file and compiled out as one file. For example:

```scss
@import "init";

/* SMACSS base rules */
@import "normalize";

/* Layout rules */
@import "layouts/responsive";

/* Component rules */
@import "components/misc";
@import "components/nav";
@import "components/header";
@import "components/footer";
```

###Style Rules
When writing styles, the themer’s goal should be to write efficient CSS. This means using the least amount of selectors possible. The best performance in CSS is the ID, but this is often not a realistic selector to use because it limits it's reusability. The exceptions are for layout rules and unique rules for unique items, like the site-name. After that is the class selector, which should be the target of the majority of your rules. Though we write CSS left to right ( `#content .field-item p` ) a browser reads CSS right to left. So in the previous rule, it would first find every paragraph on the page. Then it invalidate the ones that aren’t inside a `.field-item` class. Then invalidate the remaining ones that aren’t inside of an element with the ID `#content`. **When using a CSS Preprocessor, a lot of care needs to be taken in regards to selector depth. It’s extremely easy to nest selectors which will result in extremely inefficient styles.**

Keep your styles generic when you can, think broad strokes. If you can, apply your style to `.field-item` instead of `.article .field-item`. Likewise, when you need to apply the style to a more limited scope, use the semantic class and not the drupal generic class. So, use `.view-articles` instead of simply `.view`.

**Example: You need to apply a style to an ul, li, and an a tag for a particular view of articles. You may be tempted to write SASS like this:**

```css
.article-view {
  /* view styles */
  ul {
    /* ul styles */
    li {
      /* li styles */
      a {
        /* a styles */
      }
    }
  }
}
```
**The previous would compile to:**
```css
.article-view {/* view styles */}
.article-view ul {/* ul styles */}
.article-view ul li {/* li styles */}
.article-view ul li a {/* a styles */}
```
**A better way would be:**
```scss
.article-view {
  /* view styles */
  ul { */ ul styles */}
  li { /* li styles */}
  a {/* a styles */}
}
```
**Which compiles to:**
```css
.article-view {/* view styles */}
.article-view ul {/* ul styles */}
.article-view li {/* li styles */}
.article-view a {/* a styles */}
```
If you look at the first set (the bad one) in the previous example, the last rule requires the browser match 4 items. Since a browser reads right to left, this is extremely inefficient. Nested tags is the most expensive rule in regards to front-end performance. If the HTML tags have classes, like a typical drupal site would, the most efficient rule would use those.

For more information on CSS selector efficiency: https://developers.google.com/speed/docs/best-practices/rendering

If you find yourself fighting a module’s, or drupal core’s default stylesheet and need to remove it’s css file, you should use a hook_css_alter() to unset the file in template.php in your theme directory.

A great module that can be used to set up your theme’s generic styles is the style guide module ( https://drupal.org/project/styleguide ).

Though drupal.org style guidelines don’t consider css preprocessors very much, and some information seems outdated, the css guidelines are worth reading. https://drupal.org/node/1886770

##Conditional CSS
Using the [conditional styles module](https://drupal.org/project/conditional_styles) you can add conditional style sheets in your theme's .info file just like this:

```php
stylesheets-conditional[lte IE 8][all][] = lte-ie-8.css
```
Some themes, like Zen 5, uses classes on the HTML element instead of conditional style sheets. It aids in organization, and is in alignment with SMACSS guidelines because it allows you to write your conditional styles alongside your regular rules. So to overwrite the link color in IE browsers less then version 9, you could use the following rule. So, all rules for the element are kept together.
```css
.a {color: red;}
.lt-ie9 .a {color: blue;}
```
The magic to make the above work is really quite simple, so it would be easy to add this to your html.tpl.php file for any theme:

```html+php
<!DOCTYPE html>
<!--[if IEMobile 7]><html class="iem7" <?php print $html_attributes; ?>><![endif]-->
<!--[if lte IE 6]><html class="lt-ie9 lt-ie8 lt-ie7" <?php print $html_attributes; ?>><![endif]-->
<!--[if (IE 7)&(!IEMobile)]><html class="lt-ie9 lt-ie8" <?php print $html_attributes; ?>><![endif]-->
<!--[if IE 8]><html class="lt-ie9" <?php print $html_attributes; ?>><![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)]><!--><html <?php print $html_attributes . $rdf_namespaces; ?>><!--<![endif]-->
```

If you still insist on using separate conditional stylesheets, and you don't want to use the conditional styles module, then you'll need to use a preprocessor in your template.php file:
```php
function YOURTHEME_preprocess_html(&$variables) {
// Add conditional stylesheets for IE
  drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lt IE 9', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/css/ie9.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 9', '!IE' => FALSE), 'preprocess' => FALSE));
}
```

##Media Queries
There are two ways to really build a responsive site. Which way you go, usually depends on the design provided, which determines the way you work and how you write your media queries.

**Mobile First:** When working mobile first, you should build from the smallest veiwport to the widest desktop. So when writing styles, the styles outside of a media query would work on all viewports and mobile viewports. Media queried rules would apply to larger viewports. Here are some sample media queries for mobile-first. Notice the use of min-width. Mobile only rule does use a max-width. This should be used in the case that the rule is **only mobile**.

```css
@media (max-width: 767px) {} /* mobile only */
@media (min-width: 768px) {} /* tablet and up */
@media (min-width: 768px) and (max-width: 959px) {} /* tablet only */
@media (min-width: 960px) {} /* desktop only */
```

**Desktop Down:** When working desktop down your rules outside of a media query should be for your desktop styles. Then you override them with media-queried rules for smaller viewports. Like with mobile-first, I do like to throw in a desktop only media query (it just makes life easier sometimes).

```css
@media (min-width: 960px) {} /* desktop only */
@media (max-width: 959px) {} /* tablet and down */
@media (max-width: 959px) and (min-width: 768px) {} /* tablet only */
@media (max-width: 767px) {} /* mobile only */
```

**Things to keep in mind**
- Design doesn't have to dictate methods, but it's often easier if you let it.
- All front end devlopers need to be on the same page. Meaning, dev A can't work mobile first while dev B is doing desktop down.
- If developing with a mobile-first strategy, do it. Meaning, write styles and check in a browser at the smallest viewport before working your way up in regards to viewport size.
- A task isn't complete until all viewports are correct
- Sadly, regardless of how many breakpoints are defined by a designer, and the comps provided by the designer, unless design was done in a browser, there are going to be natural breaks in between breakpoints. Since the number and variety of devices continue to grow all the time, you usually can't ignore those. If you're defining your media queries or breakpoints as variables and/or using a mixin (or a ruby gem), I find it easier to handle one-offs as hand coded media queries because if you decide to adjust breakpoints globally, these rules generally aren't effected and you don't want them to change.

##Images

*This section is out of date - at the time of writing, it was preferred, now using srcset with img tags is the way to go in the majority of cases*

Working with images isn't just a theme issue, but it's a problem that needs to be solved and is often overlooked, and left up to the themer to solve. At the time of this writing, the best solution for the image problem is the HTML5 picture element. The downside is, browser support is poor and requires javascript as a polyfill. However, it does work for serving up multiple size and resolutions of an image based on screen size and pixel density and uses breakpoints and picture/image styles.
- [Module](http://drupal.org/project/picture)
- [Instructions](https://drupal.org/node/1902264)

It takes some configuration, but it's currently the most future proof, "right way" solution to providing responsive and retina images. As more and more hardware goes the way of higher pixel density screens, this is possibly one of the most overlooked parts of creating a site.

##Template Files
HMTL and your php variables go in the tpl files. Logic goes into preprocess and process functions that are located in the template.php file. The most important part of working with template files is being consistent with your mark up. If you’d like to clean up some of the standard drupal mark up, you can use the Fences module ( https://drupal.org/project/fences ) to provide a leaner structure.

A good general rule is, if you can accomplish a task with a preprocessor function or adding a tpl file, go with the preprocessor function because it's more efficient. A example preprocessor function to add a class to a block title. Here's the source of my block when I view it using chrome dev tools.
```html
<div id="block-block-1" class="block block-block first last odd">
  <h2 class="block__title block-title">Head Block Demo</h2>
  <p>some stuff</p>
</div>
```
In this example, my theme name is `torq`, the block delta is 1, which I pull from the source (`id=block-block-1`). Here's my simple preprocessor:

```php
/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */

function torq_preprocess_block(&$variables, $hook) {

  $block = $variables['block'];

  if ($block->delta == 1) {
    $variables['title_attributes_array']['class'][] = 'awesome-class';
  }
}
```
This example results in:
```html
<div id="block-block-1" class="block block-block first last odd">
  <h2 class="block__title block-title awesome-class">Head Block Demo</h2>
  <p>some stuff</p>
</div>
```

Avoid using drupal_add_css() & drupal_add_js() in your template files (and custom modules). It’s better to work your styles into your theme. It can be tempting to use the conditional methods of adding css & js as a way to keep file sizes down on page loads. But it also causes Drupal to create multiple versions of the aggregated css & js. One file gets downloaded once and cached locally. In extreme cases, where drupal_add is being used a lot, drupal can create a new aggregate on nearly every page load. CSS & JS aggregate files are normally cached heavily by varnish. Creating new aggregates, however, circumvents the caching, and creates server load from not only having to continuously serve these files, but continuously creating these files.

If the styles are added within the normal site styles, they are simply aggregated once, cached by varnish, downloaded on the first page load, and cached locally.
- Template Suggestions: https://drupal.org/node/223440
- Theme Hook Suggestions: https://drupal.org/node/1089656
- A list of the core tpl files can be found here: https://drupal.org/node/190815

##Working with Javascript
Working with javascript is more difficult than working with styles. Please review the javascript coding standards here: https://drupal.org/node/172169 which provides information on code structure (indeting, comments, etc.).

It’s usually helpful to create a single site-wide javascript file in your js directory of your theme. Drupal includes jquery (version 1.4.4) in core that can be used. If you need a newer version of jquery then what it ships with, you can use the jQuery Update module ( https://drupal.org/project/jquery_update ). Using jQuery Update you have the ability to choose a different version of the jQuery library.

When you need to utilize a third party javascript library or jQuery plugin, there are multiple ways to accomplish it. One way that seems easy is to simply add the plugin to your theme’s JS folder and add it to your theme’s .info file or using drupal_add_js() to include the plugin. However, the best way is to utilize a plugin or library is the libraries api ( https://drupal.org/project/libraries ). You can add the library and use a custom module to add the library to your site.

Drupal Behaviors are Drupal’s way to fire javascript on document.ready and again after ajax calls. Most of the times, this is what you would want because of things being added to the DOM by ajax after the page is loaded.
```javascript
Drupal.behaviors.yourCustomBehavior = {
  attach: function (context, settings) {
    $(‘#block-block-1’).click(function() {
      // do something
    });
  }
}
```

###Javascript and Performance
Using drupal_add_js you can scope javascript to the footer and specifiy load on every page (so you aren't building a bunch of aggregates). This is one way to improve page render time. If including external javascript files, this is the best way to do it. You shouldn't use something like drupal_add_head to attach javascript files because it will place the JS before your CSS, which will block the download of the CSS files until after the javascript files are downloaded.

Use a theme html preprocess function to add theme javascript to footer instead of theme's .info file, load on every page (best for aggregation - https://api.drupal.org/api/drupal/includes%21common.inc/function/drupal_add_js/7 )

```javascript
function example_preprocess_html(&$vars) {
  // example loading from library
  drupal_add_js(libraries_get_path('slick') . '/slick.min.js', array(
    'scope' => 'footer',
    'every_page' => TRUE,
    'weight' => 1,
  ));
  // example loading from theme directory
  drupal_add_js(drupal_get_path('theme', 'example').'/js/hammer.min.js', array(
    'scope' => 'footer',
    'every_page' => TRUE,
    'weight' => 2,
  ));
}
```


##Links
###Themes
- [Zen5](https://drupal.org/project/zen)
- [Basic](https://drupal.org/project/basic)
- [SMACSS in themes](http://www.acquia.com/blog/organize-your-styles-introduction-smacss)
- [SMACSS](http://smacss.com/)

###Grids
- [Zen Grids](http://zengrids.com/help/)

###Compass
- [Compass](http://compass-style.org/)

###CSS Performance
- [Chrome - Optimize Browser Rendering](https://developers.google.com/speed/docs/best-practices/rendering)
- [Mozilla - Writing Efficient CSS](https://developer.mozilla.org/en-US/docs/Web/Guide/CSS/Writing_efficient_CSS)

###Drupal Coding Standards
- [CSS](https://drupal.org/node/1886770)
- [JavaScript](https://drupal.org/node/172169)

###Drupal Guides
- [Template Suggestions](https://drupal.org/node/223440)
- [Theme Hook Suggestions](https://drupal.org/node/1089656)
- [Core Tpl Files](https://drupal.org/node/190815)
- [Working with Javascript](https://drupal.org/node/121997)

###Helpful Drupal Modules
- [Fences](https://drupal.org/project/fences)
- [jQuery Update](https://drupal.org/project/jquery_update)
- [Libraries API](https://drupal.org/project/libraries)
- [Style Guide](https://drupal.org/project/styleguide)
