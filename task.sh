#!/usr/bin/env bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
if [ ! -f ${DIR}/bin/phing ]; then
   echo "Phing was not found in this project's bin directory. Please run composer install."
   exit 1
fi

# This script simply passes all arguments to Phing.
./bin/phing -f build/phing/build.xml $@
