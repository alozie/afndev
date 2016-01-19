This directory contains the core tasks that ship with Bolt. No files in this
directory should be customized. You may pull in upstream changes to build files
in this directory via a `git subtree` command:

@todo add command here.

Please note that pulling in upstream changes for file in this directory may not
be sufficient to pull in all upstream build related changes. You may need to
modify other files outside of this directory to successfully update the build
tasks. E.g., `task.sh` is integral to the build process build lives in the 
project root.
