/**
 * @file
 * deploy-now.js
 *
 * Update now.json with needed environment
 */

const fs = require('fs');

fs.readFile('template.now.json', 'utf8', (e, data) => {
  const obj = JSON.parse(data);
  let args = process.argv.slice(2);
  obj.name = args[0];
  obj.build.env.BACKEND_SERVER = args[1];
  obj.build.env.DRUPAL_GRAPHQL_SERVER = args[1] + '/graphql';
  fs.writeFile('now.json', JSON.stringify(obj, null, 2), (err) => {
    console.log(err || 'complete');
  });
});
