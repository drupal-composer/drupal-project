#!/usr/bin/env bash

# Bash snippet to apply variables.
# This is a replacement for "envsubst" which is not everywhere available.

# Usage: VAR=1 $0 < path/to/file > path/to/output

echo -e "$(eval "echo -e \"`cat -`\"")"
