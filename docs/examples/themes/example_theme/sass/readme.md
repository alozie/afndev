##SASS & Compass
- Preferred syntax is scss.
- Gems are installed by bundler
- Compiling is done using compass (bundler's compass gem)
- Styles are organized based on SMACSS
- Breakpoint gem is used for media queries, mobile first

###Install with bundler
Run from the same directory as the Gemfile, first time ran it will create a Gemfile.lock that will need to be committed and pushed to the repo. This is what locks the gem versions for all developers.
```
bundle install
```

###Compile SASS
Because we're using bundler to lock the version of sass & compass, we need to ensure we compile with those versions of the gems. On occassion, if you don't have conflicting gems installed, you may get away with compiling the non-bundler way, don't do it, it'll cause more problems in the long run.

######To compile
```
bundle exec compass compile
```
######To watch
```
bundle exec compass watch
```

####SMACSS
[smacss.com](http://smacss.com)

####Breakpoint
[website](http://breakpoint-sass.com/)

[github](https://github.com/at-import/breakpoint)

[wiki](https://github.com/at-import/breakpoint/wiki)

####Susy
[website](http://susy.oddbird.net/)

[github](https://github.com/ericam/susy)

[documentation](http://susydocs.oddbird.net/en/latest/)