// This is the configuration file for Gulp. Run with `gulp` in the terminal. This will run the tasks defined here like compiling the sass files.

// Site settings
var localDev = 'example.dev';
//var bowerLibraries = [];
var bowerLibraries = ['./bower_components/normalize.css/normalize.css'];

// Add libraries for the gulp file.

var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');

// Browser-sync (not a gulp specific thing.)
var browserSync = require('browser-sync').create();

// Compile scss to css.
gulp.task('sass', function () {
  var sources = bowerLibraries.concat(['./sass/styles.scss']);

  return gulp.src(sources)
    .pipe(sass({outputStyle : 'expanded'}))
    .pipe(concat('styles.css'))
    .pipe(gulp.dest('./css'));
});

// Compile JS.
gulp.task('js', function () {
  return gulp.src(['./js-src/*.js'])
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('./js'));
});

// Start the browser-sync tool.
gulp.task('browser-sync', function() {
  browserSync.init({
    proxy: localDev
  });
});

// Create a build task. This runs once and then shuts down.
gulp.task('build', ['sass', 'js']);

// Create a watch task. This continues to run and watches for updates.
gulp.task('watch', function () {
  gulp.watch('./sass/**/*.scss', ['sass']);
  gulp.watch('./js-src/*.js', ['js']);
});

// The default task.
gulp.task('default', ['build', 'watch']);

// Put the site into live edit mode.
gulp.task('live-edit', ['browser-sync', 'build'], function () {
  gulp.watch('./sass/**/*.scss', ['sass'], browserSync.stream);
  gulp.watch('./js-src/*.js', ['js'], browserSync.reload);
  gulp.watch('index.htm').on('change', browserSync.reload);
});
