#!/bin/bash
ROOT_DIR=$(dirname "$0")/..
PROJECTS_DIR=${ROOT_DIR}/projects
ENABLED_DIR=${ROOT_DIR}/enabled

function containsElement () {
    local e
    for e in "${@:2}"; do [[ "$e" == "$1" ]] && return $true; done
    return $false
}

function collectProjects () {
    local files=( ${1}/* )
    local projects=()
    for filename in ${files[@]}; do
        projects+=("$(basename ${filename})")
    done
    echo "${projects[@]}"
}

PROJECTS=( $(collectProjects ${PROJECTS_DIR}) )

echo ${#PROJECTS}
echo ${#PROJECTS[@]}

if [ ${#PROJECTS[@]} -eq 0 ]; then
    echo No projects found
    exit
fi

for PROJECT in "${PROJECTS[@]}"; do
    echo $PROJECT
done

exit

if [ $# -eq 0 ]; then
    select SUBDOMAIN in $ALL_PROJECTS; do
        if [ -z $SUBDOMAIN ]; then
            echo Project not found
            exit
        fi
        PROJECTS=$SUBDOMAIN
        break
    done
else
    PROJECTS=$2-
fi


echo $PROJECTS
