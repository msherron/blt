#!/usr/bin/env bash

echo "Note that you will lose any custom scripts in build/custom"

rm -rf build bolt.sh
./vendor/acquia/blt/blt.sh install-alias
blt init

echo "Please run blt configure"
