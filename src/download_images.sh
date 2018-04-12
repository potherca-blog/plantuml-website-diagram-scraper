#!/usr/bin/env bash

set -o errexit  # Exit script when a command exits with non-zero status.
set -o errtrace # Exit on error inside any functions or sub-shells.
set -o nounset  # Exit script on use of an undefined variable.
set -o pipefail # Return exit status of the last command in the pipe that exited with a non-zero exit code

# ==============================================================================
# Download all diagram images
# ------------------------------------------------------------------------------
download_images() {
    local sInputDirectory

    readonly sInputDirectory="${1?Two parameters required: <input-directory> <output-directory>}"
    readonly sOutputDirectory="${2?Two parameters required: <input-directory> <output-directory>}"

    find "${sInputDirectory}" -name '*.html' \
        -exec grep -R -a -P -o 'http://s.plantuml.com/img[pw]/[^"]+\.png' {} \+ \
        | cut -d':' -f2- \
        | parallel --gnu "wget --no-verbose --directory-prefix=${sOutputDirectory} {}"
}

if [[ ${BASH_SOURCE[0]} != "$0" ]]; then
    export -f download_images
else
    download_images "$@"
    exit ${?}
fi

#EOF
