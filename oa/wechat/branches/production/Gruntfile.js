  "use strict";
  var LIVERELOAD_PORT, lrSnippet, mountFolder;

  LIVERELOAD_PORT = 35728;

  lrSnippet = require("connect-livereload")({
    port: LIVERELOAD_PORT
  });

  mountFolder = function(connect, dir) {
    return connect["static"](require("path").resolve(dir));
  };

  module.exports = function(grunt) {
    var yeomanConfig;
    require("load-grunt-tasks")(grunt);
    require("time-grunt")(grunt);
    yeomanConfig = {
      app: "src",
      dist: "dist"
    };
    try {
      yeomanConfig.app = require("./bower.json").appPath || yeomanConfig.app;
    } catch (_error) {}
    grunt.initConfig({
      yeoman: yeomanConfig,
      //设置监听文件
      watch: {
        less: {
              files: [
                  "<%= yeoman.app %>/apps/*/styles/**/*.less",
                  "<%= yeoman.app %>/styles/*.less",
                  "<%= yeoman.app %>/bower_components/bootstrap/less/**/*.less",
                  "<%= yeoman.app %>/bower_components/mobile-angular-ui/src/**/*.less"
              ],
              tasks: ["less:dev"]
        },
        livereload: {
          options: {
            livereload: LIVERELOAD_PORT
          },
          files: ["<%= yeoman.app %>/views/**/*.html",
              "<%= yeoman.app %>/apps/*/index.html",
              "<%= yeoman.app %>/apps/*/views/**/*.html",
              "<%= yeoman.app %>/apps/*/scripts/**/*.js",
              "<%= yeoman.app %>/apps/*/styles/**/*.less",
              "<%= yeoman.app %>/styles/*.less",
              "<%= yeoman.app %>/scripts/**/*.js",
              "<%= yeoman.app %>/bower_components/bootstrap/less/**/*.less",
              "<%= yeoman.app %>/bower_components/mobile-angular-ui/src/**/*.less"
          ]
        }
      },
      //创建服务器
      connect: {
        options: {
          port: 9000,
          hostname: "10.20.50.17",
          livereload: 35728
        },
        livereload: {
          options: {
              base: [
                  '.tmp',
                  yeomanConfig.app
              ]
          }
        },
        dist: {
          options: {
            middleware: function(connect) {
              return [mountFolder(connect, yeomanConfig.dist)];
            }
          }
        }
      },
      //打开浏览器
      open: {
        dev: {
          url: "http://10.20.50.17:<%= connect.options.port %>"
        }
      },
      //删除临时文件
      clean: {
        dist: {
          files: [
            {
              dot: true,
              src: [".tmp", "<%= yeoman.dist %>/*"]
            }
          ]
        },
        dev: ".tmp"
      },
      //根据html的设置，生成合并和压缩配置
      useminPrepare: {
        html: "<%= yeoman.app %>/apps/*/index.html",
        options: {
          dest: "<%= yeoman.dist %>",
          flow: {
            steps: {
              js: ["concat"],
              //js: ["concat","uglify"],
              css: ["concat"]
            },
            post: []
          }
        }
      },
      //替换合并后的script tag
      usemin: {
        html: ["<%= yeoman.dist %>/**/*.html", "!<%= yeoman.dist %>/bower_components/**"],
        css: ["<%= yeoman.dist %>/styles/**/*.css"],
        options: {
          dirs: ["<%= yeoman.dist %>"]
        }
      },
      //压缩和复制HTML到dist
      htmlmin: {
        dist: {
            options: {
                //removeComments: true,
                //collapseWhitespace: true
            },
            files: [
            {
              expand: true,
              cwd: "<%= yeoman.app %>",
              src: ["apps/*/*.html", "apps/*/views/**/*.html"],
              dest: "<%= yeoman.dist %>"
            }
            ]
        }
      },
      //复制文件到dist
      copy: {
        dist: {
          files: [
            {
              expand: true,
              dot: true,
              cwd: "<%= yeoman.app %>",
              dest: "<%= yeoman.dist %>",
              src: ["favicon.ico", "bower_components/font-awesome/css/*",
                  "bower_components/font-awesome/fonts/*",
                  "bower_components/weather-icons/css/*",
                  "bower_components/weather-icons/font/*",
                  "bower_components/mobiscroll/css/*",
                  "fonts/**/*", "i18n/**/*", "images/**/*",
                  "styles/bootstrap/**/*",
                  "styles/fonts/**/*",
                  "styles/img/**/*",
                  "styles/ui/images/**/*",
                  "views/**/*",
                  "bower_components/angular-carousel/dist/angular-carousel.min.css"]
            }, {
              expand: true,
              cwd: ".tmp",
              dest: "<%= yeoman.dist %>",
              src: ["styles/**"]
            }, {
              expand: true,
              cwd: ".tmp/images",
              dest: "<%= yeoman.dist %>/images",
              src: ["generated/*"]
            }, {
                  expand: true,
                  cwd: "<%= yeoman.app %>",
                  dest: "<%= yeoman.dist %>",
                  src: ["apps/*/styles/*.css","styles/*.css","apps/*/images/*"]
              }
          ]
        }
      },
      //同时执行耗时任务
      concurrent: {
        dev: ["less:dev"],
        dist: ["less:dist","htmlmin:dist"]
      },
      //合并文件
      concat: {
        options: {
          separator: grunt.util.linefeed + ';' + grunt.util.linefeed
        },
        vendordev:{
            src: [
                "<%= yeoman.app %>/bower_components/underscore/underscore.js",
                "<%= yeoman.app %>/bower_components/angular/angular.js",
                "<%= yeoman.app %>/bower_components/angular-route/angular-route.js",
                "<%= yeoman.app %>/bower_components/angular-resource/angular-resource.js",
                "<%= yeoman.app %>/bower_components/angular-animate/angular-animate.js",
                "<%= yeoman.app %>/bower_components/mobile-angular-ui/dist/js/mobile-angular-ui.js",
                "<%= yeoman.app %>/bower_components/angular-cookies/angular-cookies.js",
                "<%= yeoman.app %>/bower_components/mobile-angular-ui/dist/js/mobile-angular-ui.gestures.js",
                "<%= yeoman.app %>/bower_components/angular-loading-bar/build/loading-bar.js",
                "<%= yeoman.app %>/scripts/configurations/config.js",
                "<%= yeoman.app %>/scripts/configurations/config-local.js"
            ],
            dest: ".tmp/scripts/vendor.js"
        },
        vendordist:{
            src: [
                "<%= yeoman.app %>/bower_components/underscore/underscore-min.js",
                "<%= yeoman.app %>/bower_components/angular/angular.min.js",
                "<%= yeoman.app %>/bower_components/angular-route/angular-route.min.js",
                "<%= yeoman.app %>/bower_components/angular-resource/angular-resource.min.js",
                "<%= yeoman.app %>/bower_components/angular-animate/angular-animate.min.js",
                "<%= yeoman.app %>/bower_components/mobile-angular-ui/dist/js/mobile-angular-ui.min.js",
                "<%= yeoman.app %>/bower_components/angular-cookies/angular-cookies.min.js",
                "<%= yeoman.app %>/bower_components/mobile-angular-ui/dist/js/mobile-angular-ui.gestures.min.js",
                "<%= yeoman.app %>/bower_components/angular-loading-bar/build/loading-bar.min.js"
            ],
          dest: "<%= yeoman.dist %>/scripts/vendor.js"
        },
        configdevelop: {
          src: ["<%= yeoman.app %>/scripts/configurations/config.js","<%= yeoman.app %>/scripts/configurations/environments/config-develop.js"],
          dest: "<%= yeoman.dist %>/scripts/config.js"
        },
        configdemo: {
          src: ["<%= yeoman.app %>/scripts/configurations/config.js","<%= yeoman.app %>/scripts/configurations/environments/config-demo.js"],
          dest: "<%= yeoman.dist %>/scripts/config.js"
        },
        configpreproduction: {
          src: ["<%= yeoman.app %>/scripts/configurations/config.js","<%= yeoman.app %>/scripts/configurations/environments/config-preproduction.js"],
          dest: "<%= yeoman.dist %>/scripts/config.js"
        },
        configproduction: {
          src: ["<%= yeoman.app %>/scripts/configurations/config.js","<%= yeoman.app %>/scripts/configurations/environments/config-production.js"],
          dest: "<%= yeoman.dist %>/scripts/config.js"
        }
      },
      //压缩JS
      uglify: {
        options: {
          mangle: true
        }
      },
      less: {
        dev: {
            options: {
                paths: [".tmp/styles", '<%= yeoman.app %>/bower_components']
            },
            files: {
                ".tmp/styles/app.css": "<%= yeoman.app %>/styles/app.less",
                ".tmp/apps/directory/styles/app.css":"<%= yeoman.app %>/apps/directory/styles/app.less",
                ".tmp/apps/attendance/styles/app.css":"<%= yeoman.app %>/apps/attendance/styles/app.less",
                ".tmp/apps/approval/styles/app.css":"<%= yeoman.app %>/apps/approval/styles/app.less", 
                ".tmp/apps/conference/styles/app.css":"<%= yeoman.app %>/apps/conference/styles/app.less"
            }
        },
        dist: {
          options: {
              paths: [".tmp/styles", '<%= yeoman.app %>/bower_components']
          },
          files: {
              "<%= yeoman.dist %>/styles/app.css": "<%= yeoman.app %>/styles/app.less",
              "<%= yeoman.dist %>/apps/attendance/styles/app.css":"<%= yeoman.app %>/apps/attendance/styles/app.less",
              "<%= yeoman.dist %>/apps/directory/styles/app.css":"<%= yeoman.app %>/apps/directory/styles/app.less",
              "<%= yeoman.dist %>/apps/approval/styles/app.css":"<%= yeoman.app %>/apps/approval/styles/app.less", 
              "<%= yeoman.dist %>/apps/conference/styles/app.css":"<%= yeoman.app %>/apps/conference/styles/app.less"
          }
        }

      },
      replace: {
        dist: {
            options: {
                prefix:"",
                patterns: [
                    {
                        match: /\/apps\/[\w]+\/scripts/,
                        replacement: 'scripts'
                    }
                ]
            },
            files: [
                {expand: true, flatten: true, src: ['dist/apps/attendance/index.html'], dest: 'dist/apps/attendance'},
                {expand: true, flatten: true, src: ['dist/apps/directory/index.html'], dest: 'dist/apps/directory'},
                {expand: true, flatten: true, src: ['dist/apps/approval/index.html'], dest: 'dist/apps/approval'},
                {expand: true, flatten: true, src: ['dist/apps/leave/index.html'], dest: 'dist/apps/leave'},
                {expand: true, flatten: true, src: ['dist/apps/portal/index.html'], dest: 'dist/apps/portal'},
                {expand: true, flatten: true, src: ['dist/apps/knowledge/index.html'], dest: 'dist/apps/knowledge'},
                {expand: true, flatten: true, src: ['dist/apps/bbs/index.html'], dest: 'dist/apps/bbs'},                
                {expand: true, flatten: true, src: ['dist/apps/conference/index.html'], dest: 'dist/apps/conference'}
            ]
        }
      }
      });
    grunt.registerTask("dev", function(app) {
      grunt.config.merge({
          open:{
              dev: {
                  url: "http://10.20.50.17:<%= connect.options.port %>/apps/"+app
              }
          }
      })
      return grunt.task.run(["clean:dev", "concurrent:dev","concat:vendordev","connect:livereload", "open:dev", "watch"]);
    });
    grunt.registerTask("build",function(target){
        grunt.log.writeflags(grunt.config.get('concat')['config'+target]);
        if(!target){
            grunt.log.error("Please enter environment code.(eg: build:develop)");
            return false;
        }else if(!grunt.config.get('concat')['config'+target]){
            grunt.log.error("The environment \""+target+"\" does not exist");
            return false;
        }
        return grunt.task.run( ["clean:dist", "useminPrepare", "concurrent:dist","concat:vendordist","copy:dist", "concat:generated","concat:config"+target, "usemin","replace:dist"])
        //return grunt.task.run( ["clean:dist", "useminPrepare", "concurrent:dist","concat:vendordist","copy:dist", "concat:generated", "uglify","concat:config"+target, "usemin"])
    })
    grunt.registerTask("default", ["dev:approval"]);
  };
