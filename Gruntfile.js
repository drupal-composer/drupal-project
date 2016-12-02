module.exports = function(grunt) {

  // Project configuration.
  var pkg = require('./package.json');
  var config = {
    buildcontrol: {
      options: {
        dir: '.',
        commit: true,
        push: true,
        force: true,
        message: 'Built %sourceName% from commit %sourceCommit%',
        connectCommits: false
      },
      deploy: {
        options: {
          remote: pkg.config.deploy.pantheon,
          branch: process.env.CIRCLE_BRANCH || pkg.config.deploy.branch
        }
      }
    }
  };

  // Load Grunt config and the Build Control task.
  grunt.initConfig(config);
  grunt.loadNpmTasks('grunt-build-control');
};