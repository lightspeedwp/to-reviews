const gulp         = require('gulp');
const gettext      = require('gulp-gettext');
const jshint       = require('gulp-jshint');
const plumber      = require('gulp-plumber');
const rename       = require('gulp-rename');
const sort         = require('gulp-sort');
const uglify       = require('gulp-uglify');
const wppot        = require('gulp-wp-pot');

/**
 * Default gulp task - displays available commands.
 *
 * @return {void}
 */
gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp wordpress-lang to compile the to-reviews.pot, to-reviews-en_EN.po and to-reviews-en_EN.mo');
});

/**
 * Generates the WordPress .pot file for translations.
 *
 * @param {Function} done Callback function to indicate task completion.
 * @return {Stream}
 */
gulp.task('wordpress-pot', function(done) {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-reviews',
			package: 'to-reviews',
			bugReport: 'https://bitbucket.org/feedmycode/to-reviews',
			team: 'LightSpeed <webmaster@lightspeedwp.agency>'
		}))
		.pipe(gulp.dest('languages/to-reviews.pot')),
		done();
});

/**
 * Generates the WordPress .po file for translations.
 *
 * @param {Function} done Callback function to indicate task completion.
 * @return {Stream}
 */
gulp.task('wordpress-po', function(done) {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-reviews',
			package: 'to-reviews',
			bugReport: 'https://bitbucket.org/feedmycode/to-reviews',
			team: 'LightSpeed <webmaster@lightspeedwp.agency>'
		}))
		.pipe(gulp.dest('languages/to-reviews-en_EN.po')),
		done();
});

/**
 * Converts .po files to .mo files for WordPress.
 *
 * @param {Function} done Callback function to indicate task completion.
 * @return {Stream}
 */
gulp.task('wordpress-po-mo', gulp.series( ['wordpress-po'], function(done) {
	return gulp.src('languages/to-reviews-en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages')),
		done();
}));

/**
 * Main language compilation task - generates all translation files.
 *
 * @param {Function} done Callback function to indicate task completion.
 * @return {void}
 */
gulp.task('wordpress-lang', gulp.series( ['wordpress-pot', 'wordpress-po-mo'] , function(done) {
	done();
}));
