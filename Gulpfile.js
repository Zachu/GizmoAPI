var gulp = require('gulp');
var phpspec = require('gulp-phpspec');

gulp.task('test', function() {
  gulp.src('spec/**/*.php')
    .pipe(phpspec())
});

gulp.task('watch', function() {
  gulp.watch(['spec/**/*.php', 'src/**/*.php'], ['test']);
});

gulp.task('default', ['test', 'watch']);
