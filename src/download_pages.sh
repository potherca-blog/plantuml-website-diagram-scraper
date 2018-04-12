#!/usr/bin/env bash

set -o errexit  # Exit script when a command exits with non-zero status.
set -o errtrace # Exit on error inside any functions or sub-shells.
set -o nounset  # Exit script on use of an undefined variable.
set -o pipefail # Return exit status of the last command in the pipe that exited with a non-zero exit code

# ==============================================================================
# Download all PlantUML pages
# ------------------------------------------------------------------------------
download_pages() {
    local sDomain sOutput

    readonly sDomain="${1?Two parameter required: <domain-name> <output-directory>}"
    readonly sOutput="${2?Two parameter required: <domain-name> <output-directory>}"

    wget                                \
        --convert-links                 \
        --directory-prefix="${sOutput}" \
        --domains "${sDomain}"          \
        --force-directories             \
        --html-extension                \
        --no-clobber                    \
        --no-parent                     \
        --no-verbose                    \
        --page-requisites               \
        --recursive                     \
        --wait=0.05                     \
        "${sDomain}"
}

if [[ ${BASH_SOURCE[0]} != "$0" ]]; then
    export -f download_pages
else
    download_pages "${@}"
    exit ${?}
fi

#EOF
