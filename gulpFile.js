const {src, dest, watch, series, parallel} = require('gulp');
const sass = require('gulp-sass');
sass.compiler = require('node-sass');
const babel = require('gulp-babel');

const autoprefixer = require('gulp-autoprefixer');
const svgSprite = require('gulp-svg-sprite');
const concat = require('gulp-concat');
const merge = require('merge-stream');

const config = {
    shape: {
        dimension: {
            maxWidth: 32,
            maxHeight: 32,
            precision: 2,
            attributes: false,
        }
    },
    mode: {
        symbol: {
            dest: './',
            sprite: 'sprite.svg'
        }
    },
    dest: './'
};


function css() {
    var common = src('scss/common*.scss')
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(dest('application/views/css'))
            .pipe(dest('dashboard/views/css'));
    var frontend = src('scss/frontend*.scss')
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(dest('application/views/css'));
    var dashboard = src('scss/dashboard*.scss')
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(dest('dashboard/views/css'));
    var course = src('scss/course-personal*.scss')
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(dest('dashboard/views/css'));
    return merge(common, frontend, dashboard, course);
}

function watchFiles() {
    watch(['scss'], parallel(css));
}

exports.default = parallel(css);
exports.watch = watchFiles;
