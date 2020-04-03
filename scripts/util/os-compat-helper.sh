## MacOSX / Linux Compatibility Helper functions

###
## Compatible version for "sed -i"
##
## Usage: os_compat_sed_i '/search/replace/' file
case $(sed --help 2>&1) in
  *GNU*) os_compat_sed_i () { sed -i "$@"; };;
  *) os_compat_sed_i () { sed -i '' "$@"; };;
esac

###
## Compatible version for "readlink -f"
##
## Usage: os_compat_readlink -f filenpath
os_compat_readlink() {
  if [[ "$OSTYPE" == *darwin* ]]; then
     if ! os_compat_command_exists greadlink; then
       echo "Error: Missing command greadlink. Please install 'coreutils' via brew." 1>&2
       exit 1
     fi
     greadlink "$@"
  else
     readlink "$@"
  fi
}

###
## Check whether the given command exists.
##
## Usage: if ! os_compat_command_exists FOO; then echo It does not exist; fi"
os_compat_command_exists() {
  # This should be a very portable way of checking if something is on the path.
  type "$1" &> /dev/null
}

###
## Creates a symbolic link to a directory.
##
## It avoids de-referencing an existing link-target, thus behaves as using "--no-target-directory" of the Gnu "ln"
##
## Usage: os_compat_link_directory source-dir target-dir
os_compat_link_directory() {
  ln -sfF $1 $2
}
