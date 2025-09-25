const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const browserSync = require('browser-sync').create();
const { deleteSync } = require('del');

// Caminhos dos arquivos
const paths = {
  scss: {
    src: 'assets/scss/**/*.scss',
    dest: 'assets/dist/css'
  },
  js: {
    src: 'assets/js/**/*.js',
    dest: 'assets/dist/js'
  },
  images: {
    src: 'assets/images/**/*',
    dest: 'assets/dist/images'
  },
  vendors: {
    css: [
      'node_modules/bootstrap/dist/css/bootstrap.min.css',
      'node_modules/@fortawesome/fontawesome-free/css/all.min.css'
    ],
    js: [
      'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'
    ],
    fonts: [
      'node_modules/@fortawesome/fontawesome-free/webfonts/**/*'
    ]
  }
};

// Limpar diretório dist
function clean(done) {
  deleteSync(['assets/dist/**/*']);
  done();
}

// Compilar SCSS
function styles() {
  return gulp.src(paths.scss.src)
    .pipe(plumber({
      errorHandler: notify.onError({
        title: 'SCSS Error',
        message: '<%= error.message %>'
      })
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'expanded',
      includePaths: ['node_modules']
    }))
    .pipe(autoprefixer({
      cascade: false
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(browserSync.stream());
}

// Compilar SCSS apenas para home page
function homeStyles() {
  return gulp.src('assets/scss/home.scss')
    .pipe(plumber({
      errorHandler: notify.onError({
        title: 'Home SCSS Error',
        message: '<%= error.message %>'
      })
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'expanded',
      includePaths: ['node_modules']
    }))
    .pipe(autoprefixer({
      cascade: false
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(cleanCSS())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(paths.scss.dest));
}

// Minificar CSS
function minifyStyles() {
  return gulp.src(`${paths.scss.dest}/**/*.css`)
    .pipe(plumber())
    .pipe(cleanCSS())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(paths.scss.dest));
}

// Processar JavaScript
// Compilar JavaScript
function scripts() {
  return gulp.src([
    'assets/js/app.js',
    'assets/js/**/*.js',
    '!assets/js/vendors/**',
    '!assets/js/home-page.js'
  ])
    .pipe(plumber())
    .pipe(concat('app.js'))
    .pipe(gulp.dest(paths.js.dest))
    .pipe(browserSync.stream());
}

// Compilar JavaScript apenas para home page
function homeScripts() {
  return gulp.src('assets/js/home-page.js')
    .pipe(plumber())
    .pipe(gulp.dest(paths.js.dest))
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(paths.js.dest));
}

// Minificar JavaScript
function minifyScripts() {
  return gulp.src(`${paths.js.dest}/app.js`)
    .pipe(plumber())
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(paths.js.dest));
}

// Otimizar imagens
function images() {
  return gulp.src(paths.images.src)
    .pipe(gulp.dest(paths.images.dest));
}

// Copiar vendors (Bootstrap, etc.)
function vendors(done) {
  // CSS vendors
  gulp.src(paths.vendors.css)
    .pipe(gulp.dest(`${paths.scss.dest}/vendors`));

  // JS vendors
  gulp.src(paths.vendors.js)
    .pipe(gulp.dest(`${paths.js.dest}/vendors`));

  // Fonts (Font Awesome webfonts)
  gulp.src(paths.vendors.fonts, { allowEmpty: true })
    .pipe(gulp.dest(`${paths.scss.dest}/webfonts`));
    
  done();
}

// Servidor de desenvolvimento com BrowserSync
function serve() {
  browserSync.init({
    proxy: 'localhost/swagger/openapi-editor', // Ajuste conforme seu ambiente
    port: 3000,
    open: false,
    notify: false
  });

  // Observar mudanças
  gulp.watch(paths.scss.src, styles);
  gulp.watch(paths.js.src, scripts);
  gulp.watch('**/*.php').on('change', browserSync.reload);
}

// Observar mudanças
function watchFiles() {
  gulp.watch(paths.scss.src, styles);
  gulp.watch('assets/scss/home.scss', homeStyles);
  gulp.watch(paths.js.src, scripts);
  gulp.watch(paths.images.src, images);
}

// Tasks complexas
const build = gulp.series(
  clean,
  gulp.parallel(styles, scripts, homeScripts, images, vendors),
  gulp.parallel(minifyStyles, minifyScripts)
);

const dev = gulp.series(
  clean,
  gulp.parallel(styles, scripts, vendors),
  serve
);

const watch = gulp.parallel(watchFiles, serve);

// Exportar tasks
exports.clean = clean;
exports.styles = styles;
exports.homeStyles = homeStyles;
exports.scripts = scripts;
exports.homeScripts = homeScripts;
exports.images = images;
exports.vendors = vendors;
exports.serve = serve;
exports.watch = watch;
exports.dev = dev;
exports.build = build;
exports.buildHome = gulp.parallel(homeStyles, homeScripts);
exports.default = build;