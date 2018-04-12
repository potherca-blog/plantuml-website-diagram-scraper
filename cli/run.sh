#!/usr/bin/env bash

set -o errexit  # Exit script when a command exits with non-zero status.
set -o errtrace # Exit on error inside any functions or sub-shells.
set -o nounset  # Exit script on use of an undefined variable.
set -o pipefail # Return exit status of the last command in the pipe that exited with a non-zero exit code

source_files() {
    local sProjectPath

    readonly sProjectPath="${1?One parameter required: <project-path>}"

    # shellcheck source=src/download_pages.sh
    source "${sProjectPath}/download_pages.sh"
    # shellcheck source=src/download_images.sh
    source "${sProjectPath}/download_images.sh"
    # shellcheck source=src/extract_diagrams.sh
    source "${sProjectPath}/extract_diagrams.sh"
}

run() {
    local -i iResult=0
    local sConfirm sDomain sRootPath

    sRootPath="${1?One parameter required: <output-directory>}"

    source_files "$(dirname "${0}")/../src/"

    readonly sRootPath="${sRootPath%/}"
    readonly sDomain='plantuml.com'

    download_pages "${sDomain}" "${sRootPath}" || iResult="$?"

    if [[ ${iResult} != 0 && ${iResult} != 8 ]];then
        echo '! ERROR: Download failure'
        exit
    fi

    download_images "${sRootPath}/${sDomain}" "${sRootPath}/plantuml-images"

    extract_diagrams "${sRootPath}/${sDomain}" "${sRootPath}/diagrams.txt"

    echo '====================================================================='
    echo ' Images and diagrams have been scraped.'
    echo ''
    echo ' Please confirm that all the desired images are present.'
    echo '====================================================================='

    read -r -n1 -p 'Continue? (Y/N): ' sConfirm

    if [[ "${sConfirm}" == [yY] ]];then
        php "$(dirname "${0}")/../web/plantuml-diagrams.php"
    fi

    echo 'Done.'
}

if [[ ${BASH_SOURCE[0]} != "$0" ]]; then
    export -f run
else
    run "${@}"
    exit ${?}
fi

#EOF
