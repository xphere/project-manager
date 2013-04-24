#!/bin/bash
if [ $# -ne 2 ]
then
    echo "Usage: $(basename $0) project-public-directory subdomain-name"
    exit $E_BADARGS
fi

PROJECTS_DIR=$(dirname "$0")/../projects
PROJECT_NAME=$1
SUBDOMAIN_NAME=${2}.devel

if ln -s "${PROJECT_NAME}" "${PROJECTS_DIR}/${SUBDOMAIN_NAME}"
    then echo "Project ${PROJECT_NAME} succesfully added to project list as ${SUBDOMAIN_NAME}"
    else echo "An error occurred while adding project ${PROJECT_NAME} as ${SUBDOMAIN_NAME}"
fi
