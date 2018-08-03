var pkg = require('./package.json');
module.exports = function doGrunt(grunt) {
  // Project configuration.
  var config = {
    buildcontrol: {
      options: {
        dir: '.',
        commit: true,
        push: true,
        shallowFetch: false,
        fetchProgress: false,
        force: true,
        message: 'Built %sourceName% from commit %sourceCommit%',
        connectCommits: false
      },
      deploy: {
        options: {
          remote: pkg.config.deploy.destination,
          branch: process.env.CIRCLE_BRANCH || pkg.config.deploy.branch,
          force: true
        }
      }
    }
  };

  // Load Grunt config and the Build Control task.
  grunt.initConfig(config);
  grunt.loadNpmTasks('grunt-build-control');
};
