var $$ = {
	isCoffeeDir: function ( dir, grunt ) {
		var isCoffee = false;
		grunt.file.recurse(dir, function (abspath, rootdir, subdir, filename) {
			if ( filename.match('coffee') ) {
				isCoffee = true;
			}
		});
		return isCoffee;
	},
	jsFileNames: function ( grunt ) {
		var result = [];
		grunt.file.recurse('src/scripts', function (abspath, rootdir, subdir, filename) {
			if (typeof subdir !== 'undefined' && result.indexOf(subdir) === -1 && !subdir.match('_default')) {
				result.push(subdir);
			}
		});
		return result;
	},
	jsConcat: function ( names, grunt ) {
		var result, name, isCoffee;
		result = {};
		for ( var i = 0, len = names.length; i < len; i++ ) {
			name = names[i];
			isCoffee = this.isCoffeeDir( 'src/scripts/'+name, grunt );
			if ( isCoffee ) {
				result[name] = {
					src: ['src/scripts/'+name+'/*.coffee'],
					dest: 'src/scripts/'+name+'.coffee'
				};
			}
			else {
				result[name] = {
					src: ['src/scripts/'+name+'/*.js'],
					dest: 'files/js/'+name+'.js'
				};
			}
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
	var pkg         = grunt.file.readJSON('package.json');
	var jsFileNames = $$.jsFileNames(grunt);

	grunt.initConfig({

		// javascript
		concat: $$.jsConcat( jsFileNames, grunt ),
		coffee: {
			compile: {
				files: [{
					expand: true,
					cwd: 'src/scripts',
					src: ['*.coffee'],
					dest: 'files/js',
					ext: '.js'
				}]
			}
		},
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
				files: ['src/scripts/**/*.js', 'src/scripts/**/*.coffee'],
				tasks: ['concat', 'coffee', 'uglify']
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