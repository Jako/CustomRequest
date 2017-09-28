module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        modx: grunt.file.readJSON('_build/config.json'),
        sshconfig: grunt.file.readJSON('/Users/jako/Documents/MODx/partout.json'),
        banner: '/*!\n' +
        ' * <%= modx.name %> - <%= modx.description %>\n' +
        ' * Version: <%= modx.version %>\n' +
        ' * Build date: <%= grunt.template.today("yyyy-mm-dd") %>\n' +
        ' */\n',
        usebanner: {
            css: {
                options: {
                    position: 'top',
                    banner: '<%= banner %>'
                },
                files: {
                    src: [
                        'assets/components/customrequest/css/mgr/customrequest.min.css'
                    ]
                }
            },
            js: {
                options: {
                    position: 'top',
                    banner: '<%= banner %>'
                },
                files: {
                    src: [
                        'assets/components/customrequest/js/mgr/customrequest.min.js'
                    ]
                }
            }
        },
        uglify: {
            web: {
                src: [
                    'source/js/mgr/customrequest.js',
                    'source/js/mgr/widgets/configs.grid.js',
                    'source/js/mgr/widgets/home.panel.js',
                    'source/js/mgr/sections/home.js'
                ],
                dest: 'assets/components/customrequest/js/mgr/customrequest.min.js'
            }
        },
        sass: {
            options: {
                outputStyle: 'expanded',
                sourcemap: false
            },
            dist: {
                files: {
                    'source/css/mgr/customrequest.css': 'source/mgr/sass/customrequest.scss'
                }
            }
        },
        cssmin: {
            customrequest: {
                src: [
                    'source/css/mgr/customrequest.css'
                ],
                dest: 'assets/components/customrequest/css/mgr/customrequest.min.css'
            }
        },
        watch: {
            scripts: {
                files: [
                    'source/js/mgr/**/*.js'
                ],
                tasks: ['uglify', 'usebanner:js']
            },
            scss: {
                files: [
                    'source/sass/mgr/**/*.scss'
                ],
                tasks: ['sass', 'cssmin', 'usebanner:css']
            }
        },
        bump: {
            copyright: {
                files: [{
                    src: 'core/components/customrequest/model/customrequest/customrequestbase.class.php',
                    dest: 'core/components/customrequest/model/customrequest/customrequestbase.class.php'
                }],
                options: {
                    replacements: [{
                        pattern: /Copyright 2013(-\d{4})? by/g,
                        replacement: 'Copyright ' + (new Date().getFullYear() > 2013 ? '2013-' : '') + new Date().getFullYear() + ' by'
                    }]
                }
            },
            version: {
                files: [{
                    src: 'core/components/customrequest/model/customrequest/customrequestbase.class.php',
                    dest: 'core/components/customrequest/model/customrequest/customrequestbase.class.php'
                }],
                options: {
                    replacements: [{
                        pattern: /version = '\d+.\d+.\d+[-a-z0-9]*'/ig,
                        replacement: 'version = \'' + '<%= modx.version %>' + '\''
                    }]
                }
            },
            docs: {
                files: [{
                    src: 'mkdocs.yml',
                    dest: 'mkdocs.yml'
                }],
                options: {
                    replacements: [{
                        pattern: /&copy; 2013(-\d{4})?/g,
                        replacement: '&copy; ' + (new Date().getFullYear() > 2013 ? '2013-' : '') + new Date().getFullYear()
                    }]
                }
            }
        }
    });

    //load the packages
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.renameTask('string-replace', 'bump');

    //register the task
    grunt.registerTask('default', ['bump', 'uglify', 'sass', 'cssmin', 'usebanner']);
};
