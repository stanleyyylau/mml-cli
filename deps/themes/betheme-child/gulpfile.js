'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var ejs = require("gulp-ejs");
var sourcemaps = require('gulp-sourcemaps');
var config = require('./config.json')
const chalk = require('chalk');

gulp.task('browser-sync', ['sass', 'ejs', 'copy-img', 'copy-font'], function() {
        browserSync.init({
            proxy: config.proxyUrl,
            port: config.proxyPort
        });

    gulp.watch('./src/sass/**/*.scss', ['sass']);
    gulp.watch('./src/views/**/*.ejs', ['ejs']);
    gulp.watch('./src/img/**/*', ['copy-img']);
    gulp.watch('./src/fonts/**/*', ['copy-font']);
    gulp.watch('./**/*.php', browserSync.reload);
})

gulp.task('sass', function () {
 return gulp.src('./src/sass/main.scss')
   .pipe(sourcemaps.init())
   .pipe(sass().on('error', sass.logError))
   .pipe(sourcemaps.write())
   .pipe(gulp.dest('./dist/css'))
   .pipe(browserSync.stream());
});

gulp.task('ejs', function() {
    return gulp.src('./src/views/pages/index.ejs')
    .pipe(ejs({}, {}, {ext: '.html'}))
    .pipe(gulp.dest('./dist'))
    .pipe(browserSync.stream());
})

gulp.task('copy-img', function(){
    gulp.src(['./src/img/**/*']).pipe(gulp.dest('./dist/img'))
    .pipe(browserSync.stream());
})

gulp.task('copy-font', function(){
    gulp.src(['./src/fonts/**/*']).pipe(gulp.dest('./dist/fonts'))
    .pipe(browserSync.stream());
})

// This is the entry point
gulp.task('dev', function () {
    gulp.watch('./src/sass/**/*.scss', ['sass']);
    gulp.watch('./src/views/**/*.ejs', ['ejs']);
    gulp.watch('./src/img/**/*', ['copy-img']);
    gulp.watch('./src/fonts/**/*', ['copy-font']);
 //gulp.watch('./**/*.php', browserSync.reload);
});

