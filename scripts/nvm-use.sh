#!/bin/sh

# Install the Node Version Manager (NVM) script if it is not available.
if [ ! -s "$NVM_DIR/nvm.sh" ]; then
  # Maybe the script exists but NVM_DIR just didn't get set.
  if [ ! -s "$HOME/.nvm/nvm.sh" ]; then
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | sh
  fi
  export NVM_DIR="$HOME/.nvm"
fi

# Load the NVM script.
unset npm_config_prefix
source "$NVM_DIR/nvm.sh"

# Make sure the project has an .nvmrc file.
if [ ! -s .nvmrc ]; then
  OLD_STASH=$(git rev-parse -q --verify refs/stash)
  git stash save -q
  NEW_STASH=$(git rev-parse -q --verify refs/stash)
  nvm install node | grep -ohe 'v[0-9]*\.[0-9]*\.[0-9]*' | head -1 > .nvmrc
  git add :/.nvmrc
  #git commit -m 'Add .nvmrc file with latest node'
  #git push
  if [ $OLD_STASH != $NEW_STASH ]; then
    git stash pop
  fi
fi

# Load the version of node specified in .nvmrc.
nvm use
