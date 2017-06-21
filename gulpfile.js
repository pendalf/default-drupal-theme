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
var jade = require('gulp-jade');
var fs   = require('fs');

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


gulp.task('iconHtml', ['Iconfont'], function () {
    var re = new RegExp(/&--([^ ]*)/);

    fs.readFile('./sass/_icons.scss', 'utf8', function(err, data) {
        var icons = []
        if(err)
            return console.log(err);
        data.split('\r\n').forEach(function(icon) {
            var match = re.exec(icon);
            if(match)
                icons.push(match[1])
        })
        // the gulp-jade plugin expects template local data to be an object
        // such as:
        // {locals: YOUR_DATA_OBJECT_TO_BIND}
        bind({locals: {icons: icons}})
    });

    // method that will bind data to your template
    var bind = function(data) {     
        gulp.src('./sass/templates/template.jade')
            .pipe(jade(data))
            .pipe(gulp.dest('./sass/templates/icons/'))
    }
});

gulp.task('glyfs', ['Iconfont', 'iconHtml']);

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
        .pipe(gulp.dest('css'));
});

 
gulp.task('gcmq:watch', function () {
  gulp.watch('./css/style.css', ['gcmq']);
});

gulp.task('default', ['sass', 'sass:watch', 'gcmq:watch']);