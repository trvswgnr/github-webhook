#!/bin/bash
if [[ ! -d ./logs ]]; then mkdir ./logs; fi
exec 3>&1 4>&2
trap 'exec 2>&4 1>&3' 0 1 2 3
exec 1>>./logs/deploy.log 2>&1
date
DEPLOY_PATH=$1
REMOTE=$2
BRANCH=$3
cd $DEPLOY_PATH
git checkout $BRANCH
git clean -fdx
git fetch --all
git reset --hard $REMOTE/$BRANCH
echo ""
