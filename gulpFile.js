const {src, dest, watch, parallel} = require('gulp');
const sass = require('gulp-sass')(require('sass'));
sass.compiler = require('node-sass');

const autoprefixer = require('gulp-autoprefixer');
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
<<<<<<< HEAD
    var course = src('scss/course-personal*.scss')
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(dest('dashboard/views/css'));
    return (common, frontend, dashboard, course);
=======

    var quiz = src('scss/quiz*.scss')
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(dest('application/views/css'));
    return (common, frontend, dashboard, quiz);
}

function svg() {
    var frontend = src('application/views/images/sprite/*.svg')
            .pipe(svgSprite(config))
            .pipe(dest('application/views/images'));
    var dashboard = src('dashboard/views/images/sprite/*.svg')
            .pipe(svgSprite(config))
            .pipe(dest('dashboard/views/images'));

    return merge(frontend, dashboard);
>>>>>>> develop_quiz
}

function watchFiles() {
    watch(['scss'], parallel(css));
}

exports.default = parallel(css);
exports.watch = watchFiles;
