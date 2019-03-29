#!/usr/bin/env bash
# Helper script to set the following variables:
#
# - GIT_BRANCH - The branch that is currently tested. May be "HEAD" if unknown.
#    Any existing GIT_BRANCH variable is *not* overwritten.
# - GIT_CURRENT_BRANCH - The currently active branch. When develop is merged
#     into the branch, before tests are run the HEAD becomes detached. This
#     variable points to the name of the resulting (possibly temporary) branch.
#
# Usage: eval scripts/util/get-branch.sh

# Determine current branch.
GIT_CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
# Initialize GIT_BRANCH environment variable if not set yet.
GIT_BRANCH=${GIT_BRANCH:-$GIT_CURRENT_BRANCH}

# Support detached HEADs.
# If a detached HEAD is found, we must give it a branch name. This is necessary
# as composer does not update metadata when dependencies are added in via Git
# commits, thus we need a branch.
if [[ $GIT_CURRENT_BRANCH == "HEAD" ]]; then
  # On travis, fall back to the the travis branch for GIT_BRANCH.
  if [[ ! -z "$TRAVIS" ]] && [[ "$GIT_BRANCH" == "HEAD" ]]; then
    GIT_BRANCH=$(if [ "$TRAVIS_PULL_REQUEST" == "false" ]; then echo $TRAVIS_BRANCH; else echo $TRAVIS_PULL_REQUEST_BRANCH; fi)
  fi

  GIT_CURRENT_BRANCH=tmp/$(date +%s)
  git checkout -b $GIT_CURRENT_BRANCH
fi

# Remove leading origin/ if any.
export GIT_BRANCH=${GIT_BRANCH/origin\//}
export GIT_CURRENT_BRANCH=${GIT_CURRENT_BRANCH/origin\//}
