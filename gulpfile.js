'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');
var sassGlob = require('gulp-sass-glob');
var browserSync = require('browser-sync').create();
var bourbon = require('node-bourbon').includePaths;
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');
var gcmq = require('gulp-group-css-media-queries');

var fontName  = 'Icons';
var fontGlyfs = 'img/glyfs/*.svg';
 
gulp.task('serve', ['sass', 'sass:ckeditor'], function() {

    // browserSync.init({
    //   // server: "http://localhost:8080"
    //   port: "3000",
    // });

    gulp.watch('./sass/**/*.sass', ['sass', 'sass:ckeditor']);
});


var runTimestamp = Math.round(Date.now()/1000);
 
gulp.task('Iconfont', function(){
  return gulp.src([fontGlyfs])

    .pipe(iconfontCss({
      fontName: fontName,
      path: './sass/templates/_icons.scss',
      targetPath: '../../sass/_icons.scss',
      fontPath: 'fonts/icomoon',
      cssClass: 'icons-fonts'
    }))

    .pipe(iconfont({
      fontName: 'icomoon', // required 
      prependUnicode: true, // recommended option 
      formats: ['ttf', 'eot', 'svg', 'woff', 'woff2'], // default, 'woff2' and 'svg' are available 
      timestamp: runTimestamp, // recommended to get consistent builds when watching files 
      centerHorizontally: true,
      // fixedWidth: true,
      normalize: true,
    }))
    .on('glyphs', function(glyphs, options) {
      // CSS templating, e.g. 
      console.log(glyphs, options);
    })
    .pipe(gulp.dest('fonts/icomoon'));
});

gulp.task('glyfs', ['Iconfont']);


gulp.task('sass', function () {
  return gulp.src('./sass/*.scss')
    // .pipe(sassGlob())
    .pipe(sourcemaps.init())
    .pipe(sass.sync({
      includePaths: ['styles'].concat(bourbon)
    }).on('error', sass.logError))
    .pipe(sourcemaps.write('.'))
    .pipe(rename('style.css'))
    .pipe(gulp.dest('./css'))
    .pipe(browserSync.stream());
});

 
gulp.task('sass:watch', function () {
  gulp.watch('./sass/**/*.scss', ['sass']);
});

gulp.task('gcmq', ['sass'], function () {
  return gulp.src('./css/*.css')
        .pipe(gcmq())
        .pipe(gulp.dest('/.css'));
});

 
gulp.task('gcmq:watch', function () {
  gulp.watch('./css/style.css', ['gcmq']);
});

gulp.task('default', ['sass', 'sass:watch', 'gcmq:watch']);