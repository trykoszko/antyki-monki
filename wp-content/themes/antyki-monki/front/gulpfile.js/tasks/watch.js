const config = require('../config')
const gulp = require('gulp')
const path = require('path')
const watch = require('gulp-watch')

const watchTask = () => {
  const watchableTasks = ['images', 'html', 'css', 'fonts']

  watchableTasks.forEach(taskName => {
    const task = config.tasks[taskName]
    if (task) {
      const glob = path.join(config.root.src, task.src, '**/*.{' + task.extensions.join(',') + '}')
      watch(glob, () => {
        require('./' + taskName)()
      })
    }
  })

  const phpGlob = path.join(config.root.phpSrc, config.tasks.php.src, '**/*.php')
  watch(phpGlob, () => {
    require('./php')()
  })
}

gulp.task('watch', ['browserSync'], watchTask)
module.exports = watchTask
