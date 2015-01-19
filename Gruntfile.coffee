funcs = ( grunt ) ->
    isCoffeeDir = ( dir ) ->
        isCoffee = false
        grunt.file.recurse dir, ( abspath, rootdir, subdir, filename ) ->
            isCoffee = true if filename.match '.coffee'
            return
        return isCoffee

    jsFileNames = ->
        result = []
        grunt.file.recurse 'src/scripts', ( abspath, rootdir, subdir, filename) ->
            if ( typeof subdir isnt 'undefined' ) and ( -1 is result.indexOf subdir ) and ( not subdir.match '_default' )
                result.push subdir
            return
        return result

    jsConcat = ( names ) ->
        result = {}
        names.reduce ( result, item ) ->
            isCoffee = isCoffeeDir 'src/scripts/'+item
            type     = if isCoffee then 'coffee' else 'js'
            destPath = if isCoffee then 'src/scripts/' else 'files/js/'
            result[item] =
                src: ['src/scripts/'+item+'/*.'+type]
                dest: destPath+item+'.'+type
            return
        , result
        return result

    jsUglify = ( names ) ->
        result = {}
        names.reduce ( result, item ) ->
            result[item] =
                src: 'files/js/'+item+'.js'
                dest: 'files/js/'+item+'.min.js'
            return
        , result
        return result

    jshintDist = ( names ) ->
        paths = names.map ( item ) ->
            'files/js/'+item+'.js'
        return paths

    csslintSrc = ->
        result = []
        grunt.file.recurse 'files/css/', ( abspath, rootdir, subdir, filename ) ->
            if ( not filename.match '.min.css' ) and ( not filename.match '.DS_Store' )
                result.push filename
            return
        return result

    fn =
        jsFileNames: jsFileNames
        jsConcat   : jsConcat
        jsUglify   : jsUglify
        jshintDist : jshintDist
        csslintSrc : csslintSrc

    return fn



module.exports = ( grunt ) ->
    fn          = funcs grunt
    pkg         = grunt.file.readJSON 'package.json'
    jsFileNames = fn.jsFileNames()
    taskNames   = Object.keys pkg.devDependencies

    grunt.initConfig

        # javascript
        concat: fn.jsConcat jsFileNames
        coffee:
            compile:
                files: [
                    expand: true,
                    cwd: 'src/scripts'
                    src: ['*.coffee']
                    dest: 'files/js'
                    ext: '.js'
                ]
        uglify: fn.jsUglify jsFileNames

        # css
        compass:
            compile:
                options:
                    cssDir     : 'files/css'
                    sassDir    : 'src/sass'
                    imagesDir  : 'files/images'
                    outputStyle: 'expanded'
        cssmin:
            target:
                files: [
                    expand: true
                    cwd: 'files/css'
                    src: ['*.css', '!*.min.css']
                    dest: 'files/css'
                    ext: '.min.css'
                ]

        # images
        imagemin:
            dynamic:
                files: [
                    expand: true
                    cwd: 'src/images/'
                    src: ['*.{png,jpg,gif}']
                    dest: 'files/images/'
                ]

        # checker
        jshint:
            dist: fn.jshintDist jsFileNames
            options:
                jshintrc: true
        csslint:
            check:
                src: fn.csslintSrc()

        # watch
        watch:
            js:
                files: ['src/scripts/**/*.js', 'src/scripts/**/*.coffee']
                tasks: ['concat', 'coffee', 'uglify']
            sass:
                files: ['src/sass/*.scss', 'src/sass/*.sass']
                tasks: ['compass', 'cssmin']
            images:
                files: 'src/images/*.{png,jpg,gif}'
                tasks: ['imagemin']


    taskNames.forEach ( item ) ->
        if item.substring(0, 6) is 'grunt-'
            grunt.loadNpmTasks item
        return

    grunt.registerTask 'default', 'watch'

    return