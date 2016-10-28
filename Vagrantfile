####################################################
## drunomics Vagrantfile, (c) drunomics GmbH
##
## The following vagrant plugins are required:
## - vagrant-hosts-provisioner
##
## Install with vagrant plugin install $name
####################################################

# Get the right base directory if the Vagrantfile was a symlink.
base_path = File.dirname(File.realdirpath(__FILE__));
ENV['VAGRANT_DEFAULT_PROVIDER'] = 'docker'
require 'yaml'

Vagrant.configure("2") do |config|

  # We are dynamically retrieving a list of nodes by reading from a
  # project.yml file in this directory.
  project = YAML.load(File.open(base_path + '/devsetup/project.yml'))
  project_name = project['project'];
  nodes = project['nodes'];

  config.ssh.username = "root"
  config.ssh.password = "docker.io"
  config.ssh.insert_key = true

  node_defaults = {
    "hostname" => project_name,
    "subdomains" => [],
    "hostaliases" => [],
    "extra_ansible_requirement" => '',
    "extra_ansible_playbook" => '',
    "docker_image_vendor" => 'drunomics',
    "docker_image_name" => 'lamp',
    "docker_container_expose" => [ 22, 80, 443 ],
  }

  # Now, iterate over all the nodes defined by the project file.
  nodes.each do |name, variables|
    # Apply the defaults as defined above.
    variables = node_defaults.merge(variables)

    config.vm.define "#{project_name}.#{name}" do |node|

      # Append to .local to all hosts locally.
      node.vm.hostname = variables['hostname'] + ".local"
 
      variables['subdomains'] << "devel-mail"
      variables['subdomains'] << "devel-mysql"

      hostaliases = [];
      variables['hostaliases'].each do |hostalias|
        hostaliases << hostalias
        hostaliases << hostalias + ".local"
      end
      variables['subdomains'].each do |hostalias|
        hostaliases << hostalias + "." + project_name + ".local"
      end

      node.vm.provision :hostsupdate, run: 'always' do |host|
        host.hostname = node.vm.hostname
        host.manage_guest = false
        host.manage_host = true
        host.aliases = hostaliases
      end

      # Provision via local ansible and copy settings.
      node.vm.provision "file",
        source: base_path + "/devsetup/vagrant-playbook.yml",
        destination: "/root/playbooks/vagrant.yml"
      node.vm.provision "file",
        source: base_path + "/devsetup/provision-vars.yml",
        destination: "/etc/ansible/group_vars/all/provision-vars.yml"
      node.vm.provision "file",
        source: base_path + "/devsetup/vagrant-default-vars.yml",
        destination: "/etc/ansible/group_vars/all/00-vagrant-vars.yml"

      if File.exists?(base_path + "/devsetup/" + variables['extra_ansible_requirement'])
        node.vm.provision "file",
          source: base_path + "/devsetup/" + variables['extra_ansible_requirement'],
          destination: "/root/" + variables['extra_ansible_requirement']
      end
      if File.exists?(base_path + "/devsetup/" + variables['extra_ansible_playbook'])
        node.vm.provision "file",
          source: base_path + "/devsetup/" + variables['extra_ansible_playbook'],
          destination: "/root/playbooks/" + variables['extra_ansible_playbook']
      end

      node.vm.provision "shell",
        args: variables['extra_ansible_requirement'],
        inline: "
          export ANSIBLE_FORCE_COLOR=true
          export PYTHONUNBUFFERED=1
          [ -f /root/$1 ] &&  ansible-galaxy install -r /root/$1 --force
          ansible-playbook /root/playbooks/*.yml
        ",
        name: "ansible playbooks",
        keep_color: true

      node.vm.provider "docker" do |d|
        d.image = variables['docker_image_vendor'] + '/' + variables['docker_image_name']
        d.name = variables['hostname']
        d.expose = variables['docker_container_expose']
        d.has_ssh = true
        # Customize ulimit parameters for varnish.
        d.create_args = [ "--ulimit", "nofile=131072:131072", "--ulimit", "memlock=82000:82000" ]
        node.ssh.username = "root"
      end

      # Configure shared folders.
      node.vm.synced_folder base_path, "/srv/default/vcs"
    end
  end
end
