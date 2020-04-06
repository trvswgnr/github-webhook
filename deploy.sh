#!/bin/bash
DEPLOY_PATH=$1
if [ -z "$DEPLOY_PATH" ]; then
	echo "No deploy path set."
	exit 1
fi
cd DEPLOY_PATH
git checkout master
git fetch --all
git reset --hard origin/master
