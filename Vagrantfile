# @file
# A delegating Vagrantfile.
# @see http://docs.drupalvm.com/en/latest/deployment/composer-dependency

# The Drupal VM environment name corresponds to the name of our config file.
ENV['DRUPALVM_ENV'] = "drupalvm"

# The absolute path to the root directory of the project. Both Drupal VM and
# the config file need to be contained within this path.
ENV['DRUPALVM_PROJECT_ROOT'] = "#{__dir__}"

# The relative path from the project root to the directory where Drupal VM is located.
ENV['DRUPALVM_DIR'] = "vendor/geerlingguy/drupal-vm"

# Load the real Vagrantfile
load "#{__dir__}/#{ENV['DRUPALVM_DIR']}/Vagrantfile"
