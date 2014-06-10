# Scripts

The current script types are `prebuild` and `postbuild`. These run before Drush make operations and after, respectively. Within each directory, any number of scripts is supported. As long as the file is executable, it can also be of any language. 

## Example

      scripts/
      ├── postbuild/
      │   └── post.sh
      └── prebuild/
          └── pre.sh