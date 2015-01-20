funcs = ( grunt ) ->

    isCoffeeDir = ( dir ) ->
        isCoffee = false
        grunt.file.recurse dir, ( abspath, rootdir, subdir, filename ) ->
            isCoffee = true if filename.match '.coffee'
            return
        return isCoffee

    jsFileNames = ->
        result = []
        grunt.file.recurse 'src/scripts', ( abspath, rootdir, subdir, filename ) ->
            if ( subdir )
                if ( not (subdir in result) )
                    if ( not subdir.match '_default' )
                        result.push subdir
            return
        return result

    jsConcat = ( names ) ->
        result = {}
        for name in names
            isCoffee =  isCoffeeDir( 'src/scripts/'+name )
            type     = if isCoffee then 'coffee' else 'js'
            destPath = if isCoffee then 'src/scripts/' else 'files/js/'
            result[name] =
                src: ['src/scripts/'+name+'/*.'+type]
                dest: destPath+name+'.'+type
        return result

    jsUglify = ( names ) ->
        result = {}
        for name in names
            result[name] =
                src: 'files/js/'+name+'.js'
                dest: 'files/js/'+name+'.js'
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
                    src: ['*.css']
                    dest: 'files/css'
                    ext: '.css'
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
                tasks: ['concat', 'coffee']
            sass:
                files: ['src/sass/*.scss', 'src/sass/*.sass']
                tasks: ['compass']
            images:
                files: 'src/images/*.{png,jpg,gif}'
                tasks: ['imagemin']


    taskNames.forEach ( item ) ->
        if item.substring(0, 6) is 'grunt-'
            grunt.loadNpmTasks item
        return

    grunt.registerTask 'default', 'watch'
    grunt.registerTask 'releace', ['cssmin', 'uglify']

    return