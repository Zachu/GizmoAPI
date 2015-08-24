var gulp = require('gulp');
var phpspec = require('gulp-phpspec');
var notify = require('gulp-notify');

gulp.task('test', function() {
  gulp.src('spec/**/*.php')
    .pipe(phpspec('', { notify: true })).
    on('error', notify.onError({
        title: 'Crap',
        message: 'Tests failed'
    }))
    .pipe(notify({
        title: 'Success',
        message: 'All tests passed',
        onLast: true
    }));
});

gulp.task('watch', function() {
  gulp.watch(['spec/**/*.php', 'src/**/*.php'], ['test']);
});

gulp.task('default', ['test', 'watch']);
