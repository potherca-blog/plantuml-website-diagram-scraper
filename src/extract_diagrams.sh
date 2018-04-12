#!/usr/bin/env bash

set -o errexit  # Exit script when a command exits with non-zero status.
set -o errtrace # Exit on error inside any functions or sub-shells.
set -o nounset  # Exit script on use of an undefined variable.
set -o pipefail # Return exit status of the last command in the pipe that exited with a non-zero exit code

# ==============================================================================
# Grab all diagrams
# ------------------------------------------------------------------------------
extract_diagrams() {
    local sDirectory sFile

    readonly sDirectory="${1?Two parameters required: <input-directory> <output-file>}"
    readonly sFile="${2?Two parameters required: <input-directory> <output-file>}"

    find  "${sDirectory}"                               \
        -name '*.html'                                  \
        -exec                                           \
            grep -R -a -P -z -o                         \
                '(?s)(&#64;|@)startuml.+?@enduml' {} \+ \
        > "${sFile}"
}

if [[ ${BASH_SOURCE[0]} != "$0" ]]; then
    export -f extract_diagrams
else
    extract_diagrams "$@"
    exit ${?}
fi

#EOF
