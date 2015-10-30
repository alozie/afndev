#!/usr/bin/env bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
if [ ! -f ${DIR}/bin/phing ]; then
   echo "Phing was not found in this project's bin directory."
   echo "Please run composer install."
   echo "You may need to remove the vendor and bin directories first."
   exit 1
fi

# This script simply passes all arguments to Phing.
${DIR}/bin/phing -f ${DIR}/build/phing/build.xml $@