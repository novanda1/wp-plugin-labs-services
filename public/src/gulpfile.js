const gulp = require("gulp");
const { parallel, series } = require("gulp");

const imagemin = require("gulp-imagemin");
const htmlmin = require("gulp-htmlmin");
const uglify = require("gulp-uglify");
const sass = require("gulp-sass");
const concat = require("gulp-concat");
const browserSync = require("browser-sync").create(); //https://browsersync.io/docs/gulp#page-top
const nunjucksRender = require("gulp-nunjucks-render");
const autoprefixer = require("gulp-autoprefixer");
const babel = require("gulp-babel");
const postcss = require("gulp-postcss");
const webpackStream = require("webpack-stream");
const cleanCSS = require("gulp-clean-css");
const rename = require("gulp-rename");


// webpack config
const config = require("./webpack.config.js");

// Scripts
function js(cb) {
  gulp
    .src("assets/js/*js")
    .pipe(
      babel({
        presets: ["@babel/preset-env"],
      })
    )
    .pipe(webpackStream(config))
    .pipe(concat("eresources-services-public.js"))
    .pipe(uglify())
    .pipe(gulp.dest("../js"));
  cb();
}

// Compile Sass
function css(cb) {
  if (process.env.NODE_ENV === "production") {
    gulp
      .src("assets/sass/*.scss")
      .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
      .pipe(
        postcss([
          require("tailwindcss")("./tailwind.config.js"),
          require("autoprefixer"),
        ])
      )
      .pipe(
        autoprefixer({
          browserlist: ["last 2 versions"],
          cascade: false,
        })
      )
      .pipe(cleanCSS({ compatibility: "ie8" }))
      .pipe(rename("eresources-services-public.css"))
      .pipe(gulp.dest("../css"))
      .pipe(browserSync.stream());
  } else if (process.env.NODE_ENV === "development") {
    gulp
      .src("assets/sass/*.scss")
      .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
      .pipe(
        postcss([
          require("tailwindcss")("./tailwind.config.js"),
          require("autoprefixer"),
        ])
      )
      .pipe(
        autoprefixer({
          browserlist: ["last 2 versions"],
          cascade: false,
        })
      )

      .pipe(rename("eresources-services-public.css"))
      .pipe(gulp.dest("../css"))
      .pipe(browserSync.stream());
  }
  cb();
}

// Watch Files
function watch_files() {
  gulp.watch("assets/sass/**/*.scss", css);
  gulp.watch("assets/js/*.js", js);
}

// Default 'gulp' command with start local server and watch files for changes.
exports.default = series(css, js, watch_files);

// 'gulp build' will build all assets but not run on a local server.
exports.build = parallel(css, js,);
