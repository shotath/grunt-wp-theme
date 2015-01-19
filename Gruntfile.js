function funcs( grunt ) {
	function isCoffeeDir( dir ) {
		var isCoffee = false;
		grunt.file.recurse(dir, function (abspath, rootdir, subdir, filename) {
			if ( filename.match('.coffee') ) {
				isCoffee = true;
			}
		});
		return isCoffee;
	}
	var module = {
		jsFileNames: function () {
			var result = [];
			grunt.file.recurse('src/scripts', function (abspath, rootdir, subdir, filename) {
				if (typeof subdir !== 'undefined' && result.indexOf(subdir) === -1 && !subdir.match('_default')) {
					result.push(subdir);
				}
			});
			return result;
		},
		jsConcat: function ( names ) {
			var result = {};
			names.reduce(function ( result, item ) {
				var isCoffee = isCoffeeDir( 'src/scripts/'+item, grunt );
				var type     = isCoffee ? 'coffee' : 'js';
				var destPath = isCoffee ? 'src/scripts/' : 'files/js/';
				result[item] = {
					src: ['src/scripts/'+item+'/*.'+type],
					dest: destPath+item+'.'+type
				};
			}, result);
			return result;
		},
		jsUglify: function ( names ) {
			var result = {};
			names.reduce(function ( result, item ) {
				result[item] = {
					src: 'files/js/'+item+'.js',
					dest: 'files/js/'+item+'.min.js'
				};
			}, result);
			return result;
		},
		jshintDist: function ( names ) {
			var paths;
			paths = names.map(function ( item ) {
				return 'files/js/'+item+'.js';
			});
			return paths;
		},
		csslintSrc: function () {
			var result = [];
			grunt.file.recurse('files/css/', function (abspath, rootdir, subdir, filename) {
				if ( !filename.match('.min.css') && !filename.match('.DS_Store') ) {
					result.push( filename );
				}
			});
			return result;
		}
	};
	return module;
};

module.exports = function( grunt ) {
	var $fn         = funcs( grunt );
	var pkg         = grunt.file.readJSON('package.json');
	var jsFileNames = $fn.jsFileNames();
	var taskNames   = Object.keys( pkg.devDependencies );

	grunt.initConfig({

		// javascript
		concat: $fn.jsConcat( jsFileNames ),
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
		uglify: $fn.jsUglify( jsFileNames ),

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

		// images
		imagemin: {
			dynamic: {
				files: [{
					expand: true,
					cwd: 'src/images/',
					src: ['*.{png,jpg,gif}'],
					dest: 'files/images/'
				}]
			}
		},

		// checker
		jshint: {
			dist: $fn.jshintDist( jsFileNames ),
			options: {
				jshintrc: true
			}
		},
        csslint: {
        	check: {
        		src: $fn.csslintSrc()
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
			},
			images: {
				files: 'src/images/*.{png,jpg,gif}',
				tasks: ['imagemin']
			}
		}
	});

	taskNames.forEach(function ( item ) {
		if ( item.substring(0, 6) === 'grunt-' ) {
			grunt.loadNpmTasks( item );
		}
	});
	grunt.registerTask('default', 'watch');
};