var $$ = {
	jsFileNames: function (grunt) {
		var result = [];
		grunt.file.recurse('src/js', function (abspath, rootdir, subdir, filename) {
			if (typeof subdir !== 'undefined' && result.indexOf(subdir) === -1 && !subdir.match('_default')) {
				result.push(subdir);
			}
		});
		return result;
	},
	jsConcat: function ( names ) {
		var result = {}, name;
		for ( var i = 0, len = names.length; i < len; i++ ) {
			name = names[i];
			result[name] = {
				src: ['src/js/'+name+'/intro.js', 'src/js/'+name+'/define.js', 'src/js/'+name+'/functions.js', 'src/js/'+name+'/bind.js', 'src/js/'+name+'/init.js', 'src/js/'+name+'/outro.js'],
				dest: 'files/js/'+name+'.js'
			};
		}
		return result;
	},
	jsUglify: function ( names ) {
		var result = {}, name;
		for ( var i = 0, len = names.length; i < len; i++ ) {
			name = names[i];
			result[name] = {
				src: 'files/js/'+name+'.js',
				dest: 'files/js/'+name+'.min.js'
			};
		}
		return result;
	},
	jsJshintDist: function ( names ) {
		var result = [], name;
		for ( var i = 0, len = names.length; i < len; i++ ) {
			name = names[i];
			result.push('files/js/'+name+'.js');
		}
		return result;
	},
	isGruntContrib: function ( name ) {
		return name.substring(0, 6) === 'grunt-';
	},
	loadGruntContrib: function (grunt, pkg) {
		var taskName;
		for ( taskName in pkg.devDependencies ) {
			if (taskName.substring(0, 6) === 'grunt-') {
				grunt.loadNpmTasks(taskName);
			}
		}
	},
	cssExpandedFileNames: function (grunt) {
		var result = [];
		grunt.file.recurse('files/css/', function (abspath, rootdir, subdir, filename) {
			if ( !filename.match('.min.css') && !filename.match('.DS_Store') ) {
				result.push(filename);
			}
		});
		return result;
	}
};

module.exports = function(grunt) {
	var pkg = grunt.file.readJSON('package.json');
	var jsFileNames = $$.jsFileNames(grunt);

	grunt.initConfig({

		// javascript
		concat: $$.jsConcat( jsFileNames ),
		uglify: $$.jsUglify( jsFileNames ),

		// css
        compass: {
            compile: {
                options: {
					cssDir     : 'files/css',
					sassDir    : 'src/sass',
					imagesDir  : 'files/images',
					outputStyle: 'expanded'
                }
            }
        },
		cssmin: {
			target: {
				files: [{
					expand: true,
					cwd: 'files/css',
					src: ['*.css', '!*.min.css'],
					dest: 'files/css',
					ext: '.min.css'
				}]
			}
		},

		// checker
		jshint: {
			dist: $$.jsJshintDist( jsFileNames ),
			options: {
				jshintrc: true
			}
		},
        csslint: {
        	check: {
        		src: $$.cssExpandedFileNames(grunt)
        	}
        },

        // watch
		watch: {
			js: {
				files: ['src/js/*.js', 'src/js/**/*.js'],
				tasks: ['concat', 'uglify']
			},
			sass: {
				files: ['src/sass/*.scss', 'src/sass/*.sass'],
				tasks: ['compass', 'cssmin']
			}
		}
	});

	$$.loadGruntContrib(grunt, pkg);
	grunt.registerTask('default', 'watch');
};