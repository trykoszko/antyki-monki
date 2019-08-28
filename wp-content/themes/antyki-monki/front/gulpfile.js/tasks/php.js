const config = require('../config')
const browserSync = require('browser-sync')
const gulp = require('gulp')

const phpTask = () => gulp
  .src(config.root.phpSrc)
  .pipe(browserSync.stream())

gulp.task('php', phpTask)
module.exports = phpTask
