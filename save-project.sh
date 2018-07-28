#!/bin/bash

function save-project-to-repo() {
    git remote rm origin
    git remote add origin $1
    git push
}

gitRemotes=(
    git@bitbucket.org:pH_7/lifyzer-webapp-cms.git
    git@gitlab.com:pH-7/lifyzer-webapp-cms.git
    git@github.com:Lifyzer/Lifyzer-WebApp-CMS.git
)
for remote in "${gitRemotes[@]}"
do
    save-project-to-repo $remote
done
