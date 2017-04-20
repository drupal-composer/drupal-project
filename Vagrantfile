# The absolute path to the root directory of the project.
ENV['DRUPALVM_PROJECT_ROOT'] = "#{__dir__}"

# The relative path from the project root to the VM config directory.
ENV['DRUPALVM_CONFIG_DIR'] = "config"

# The relative path from the project root to the Drupal VM directory.
ENV['DRUPALVM_DIR'] = "drupal-vm"

# Notify users about necessary plugins.
[
  { :name => "vagrant-auto_network", :version => ">= 1.0.2" },
  { :name => "vagrant-vbguest", :version => ">= 0.13.0" },
  { :name => "vagrant-hostsupdater", :version => ">= 1.0.2" },
  { :name => "vagrant-cachier", :version => ">= 1.2.1"}
].each do |plugin|

  if not Vagrant.has_plugin?(plugin[:name], plugin[:version])
    raise "#{plugin[:name]} #{plugin[:version]} is required. Please run `vagrant plugin install #{plugin[:name]}`"
  end
end

dconfig = YAML.load_file("#{ENV['DRUPALVM_PROJECT_ROOT']}/#{ENV['DRUPALVM_CONFIG_DIR']}/config.yml")
vconfig = YAML.load_file("#{ENV['DRUPALVM_PROJECT_ROOT']}/#{ENV['DRUPALVM_CONFIG_DIR']}/vagrant.config.yml")

Vagrant.configure('2') do |config|
  if Vagrant.has_plugin?('vagrant-exec')
    # Ensure that commands executed in the Drupal root by default.
    config.exec.commands '*', directory: vconfig.include?('drupal_core_path') ? vconfig['drupal_core_path'] : dconfig['drupal_core_path']
  end
end

# Load the real Vagrantfile
load "#{__dir__}/#{ENV['DRUPALVM_DIR']}/Vagrantfile"
