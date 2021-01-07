// node-config で使用するディレクトリが Drupal のものと被るので読み込むディレクトリを指定。
process.env.NODE_CONFIG_DIR = `${__dirname}/config/`;

const browsersync = require('browser-sync');
const config = require('config');
const log = require('fancy-log');
const { dest, lastRun, parallel, series, src, watch } = require('gulp');
const plugins = require('gulp-load-plugins')();

const cssGlobs = 'app/{modules,themes}/custom/**/css/**/*.css';
const jsGlobs = 'app/{modules,themes}/custom/**/js/**/*.es6.js';
const scssGlobs = 'app/{modules,themes}/custom/**/scss/**/*.scss';
const twigGlobs = 'app/{modules,themes}/custom/**/templates/**/*.html.twig';

function browsersyncStart(cb) {
  browsersync.create();
  browsersync.init(config.browsersync);
  cb();
}
exports['browsersync:start'] = browsersyncStart;

function browsersyncStream() {
  return src(cssGlobs, { since: lastRun(browsersyncStream) }).pipe(
    browsersync.stream(),
  );
}

function browsersyncReload(cb) {
  browsersync.reload();
  cb();
}

function buildScss() {
  return src(scssGlobs, { sourcemaps: true })
    .pipe(
      plugins.plumber({
        errorHandler(err) {
          log(err.messageFormatted);
          this.emit('end');
        },
      }),
    )
    .pipe(plugins.sassGlob())
    .pipe(plugins.sass(config.sass))
    .pipe(
      plugins.rename(path => {
        path.dirname = path.dirname.replace(/\/scss(\/|$)/, '/css$1');
      }),
    )
    .pipe(dest('app/', { sourcemaps: '.' }));
}

function buildJs() {
  return src(jsGlobs, { since: lastRun(buildJs), sourcemaps: true })
    .pipe(plugins.plumber())
    .pipe(plugins.babel())
    .pipe(
      plugins.rename(path => {
        path.basename = path.basename.replace(/\.es6$/, '');
      }),
    )
    .pipe(dest('app/', { sourcemaps: '.' }));
}

exports['build:scss'] = buildScss;
exports['build:js'] = buildJs;
exports.build = parallel(buildJs, buildScss);

function lintScss() {
  return src(scssGlobs).pipe(plugins.stylelint(config.stylelint));
}

function lintJs() {
  return src(jsGlobs)
    .pipe(plugins.eslint())
    .pipe(plugins.eslint.format())
    .pipe(plugins.eslint.failAfterError());
}

exports['lint:scss'] = lintScss;
exports['lint:js'] = lintJs;
exports.lint = parallel(lintJs, lintScss);

function watchScss() {
  const tasks = [buildScss];
  if (browsersync.instances.length) {
    tasks.push(browsersyncStream);
  }
  watch(scssGlobs, series(tasks));
}

function watchJs() {
  watch(jsGlobs, buildJs);
}

function watchTwig() {
  if (browsersync.instances.length) {
    watch(twigGlobs, browsersyncReload);
  }
}
exports['watch:scss'] = watchScss;
exports['watch:js'] = watchJs;
exports['watch:twig'] = watchTwig;

const defaultWatch = parallel(watchJs, watchScss, watchTwig);
exports.watch = defaultWatch;

exports.default = series(browsersyncStart, defaultWatch);
