module.exports = function(grunt) {
    'use strict';

    var assetsPath = '../../assets/backend/';

    // configuração do projeto
    var gruntConfig = {

        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            target: {
                files: {
                    '../../public/backend/assets/js/login.min.js': [
                        assetsPath + 'js/ajaxform/jquery.form.js',
                        assetsPath + 'js/base.js',
                        assetsPath + 'js/ajaxform.js',
                        assetsPath + 'js/bootstrap/dist/js/bootstrap.min.js',
                    ],
                    '../../public/backend/assets/js/app.min.js': [
                        assetsPath + 'js/jquery-ui.js',
                        assetsPath + 'js/plupload/js/plupload.full.js',
                        assetsPath + 'js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js',
                        assetsPath + 'js/ajaxform/jquery.form.js',
                        assetsPath + 'js/jquery.select2/select2.min.js',
                        assetsPath + 'js/select2.js',
                        assetsPath + 'js/spectrum/spectrum.js',
                        assetsPath + 'js/daterangepicker/date.js',
                        assetsPath + 'js/daterangepicker/daterangepicker.js',
                        assetsPath + 'js/jquery.maskedinput/jquery.maskedinput.js',
                        assetsPath + 'js/counter/jquery.textareaCounter.plugin.js',
                        assetsPath + 'js/maskMoney/jquery.maskMoney.js',
                        assetsPath + 'js/postCodeAjax.js',
                        assetsPath + 'js/selectAjax.js',
                        assetsPath + 'js/base.js',
                        assetsPath + 'js/prefix.js',
                        assetsPath + 'js/form.js',
                        assetsPath + 'js/ajaxform.js',
                        assetsPath + 'js/list.js',
                        assetsPath + 'js/bootstrap/dist/js/bootstrap.min.js',
                        assetsPath + 'js/scripts.js',
                    ],
                }
            }
        },

        cssmin: {
            target: {
                files: {
                    '../../public/backend/assets/css/app.min.css': [
                        assetsPath + 'css/jquery-ui.css',
                        assetsPath + 'js/bootstrap/dist/css/bootstrap.css',
                        assetsPath + 'js/jquery.select2/select2.css',
                        assetsPath + 'js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css',
                        assetsPath + 'js/spectrum/spectrum.css',
                        assetsPath + 'css/style.css',
                    ],
                }
            }
        }

    };

    grunt.initConfig(gruntConfig);

    // plugins
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    // tarefas
    grunt.registerTask('default', ['uglify', 'cssmin']);
};