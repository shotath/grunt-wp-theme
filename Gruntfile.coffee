module.exports = ( grunt ) ->
    pkg         = grunt.file.readJSON 'package.json'
    taskNames   = Object.keys pkg.devDependencies

    grunt.initConfig

        # javascript
        coffee:
            compile:
                options:
                    bare: true
                    sourceMap: true
                    sourceMapDir: 'files/js/maps'
                files: [
                    expand: true
                    cwd: 'src/coffee'
                    src: [ '{,*/}*.coffee' ]
                    dest: 'files/js'
                    ext: '.js'
                    extDot: 'last'
                ]
        uglify:
            target:
                files: [
                    expand: true
                    cwd: 'files/js'
                    src: [ '*.js' ]
                    dest: 'files/js'
                    ext: '.min.js'
                    extDot: 'last'
                ]

        # css
        compass:
            compile:
                options:
                    cssDir: 'files/css'
                    sassDir: 'src/sass'
                    imagesDir: 'files/images'
                    outputStyle: 'expanded'
        cssmin:
            target:
                files: [
                    expand: true
                    cwd: 'files/css'
                    src: [ '*.css' ]
                    dest: 'files/css'
                    ext: '.css'
                ]

        # images
        imagemin:
            dynamic:
                files: [
                    expand: true
                    cwd: 'files/images/'
                    src: [ '*.{png,jpg,gif}' ]
                    dest: 'files/images/'
                ]
        copy:
            main:
                files: [
                    expand: true
                    cwd: 'src/images/'
                    src: [ '**' ]
                    dest: 'files/images/'
                ]

        # checker
        jshint:
            dist: [ 'files/js/*.js' ]
            options:
                jshintrc: true
        csslint:
            check:
                src: [ 'files/css/*.css' ]

        # compress
        compress:
            main:
                options:
                    mode: 'gzip'
                expand: true
                cwd: 'files/'
                src: [ '**/*.{css,js,png,jpg,gif,svg}' ]
                dest: 'files/'

        # watch
        watch:
            coffee:
                files: [ 'src/coffee/*.coffee' ]
                tasks: [ 'coffee' ]
            sass:
                files: [ 'src/sass/*.sass', 'src/sass/**/*.sass' ]
                tasks: [ 'compass' ]
            images:
                files: [ 'src/images/*' ]
                tasks: [ 'copy' ]


    taskNames.forEach ( item ) ->
        if item.substring( 0, 6 ) is 'grunt-'
            grunt.loadNpmTasks item
        return

    grunt.registerTask 'default', 'watch'
    grunt.registerTask 'releace', [ 'cssmin', 'uglify', 'imagemin' ]

    return