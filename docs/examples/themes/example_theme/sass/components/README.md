Here we orgnaize our styles into partials based on SMACSS. Then we're using sass-globbing in our styles.scss file to bring them in. Since we're using sass-globbing order can't matter. It shouldn't matter because the styles in the partial is probably going to be nested inside a unique class.

Partials aren't an exact science, but they should be modular in nature. You can have a few, or a lot. They should be named in a way that makes them easy to quickly scan and locate. Chances are, your text editor or IDE of choice probably lists file alphabetically, so, here's a good example: 

_directory.scss

_header-login.scss

_header-search.scss

_navigation.scss

_slideshow-homepage.scss

_slideshow-landingpage.scss
