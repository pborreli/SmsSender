#!/bin/bash

function installOrUpdate
{
    echo "Installing/Updating $1"

    if [ ! -d "$1" ] ; then
git clone $2 $1
    fi

cd $1
    git fetch -q origin
    git reset --hard $3
    cd -
}

installOrUpdate "vendor/Symfony/Component/ClassLoader" "git://github.com/symfony/ClassLoader.git" "origin/master"
installOrUpdate "vendor/Buzz" "git://github.com/kriswallsmith/Buzz.git" "origin/master"
