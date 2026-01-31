'use strict';

module.exports = function ( grunt ) {

	// load all grunt tasks matching the `grunt-*` pattern
	// Ref. https://npmjs.org/package/load-grunt-tasks
	require( 'load-grunt-tasks' )( grunt );

	grunt.initConfig( {
		// Compile Sass to CSS
		// Ref. https://www.npmjs.com/package/grunt-contrib-sass
		sass: {
			expanded: {
				options: {
					style: 'expanded' // nested / compact / compressed / expanded
				},
				files: {
					'style-expanded.css': 'sass/style.scss' // 'destination': 'source'
				}
			},
			minify: {
				options: {
					style: 'compressed' // nested / compact / compressed / expanded
				},
				files: {
					'style.css': 'sass/style.scss' // 'destination': 'source'
				}
			}
		},
		// autoprefixer
		autoprefixer: {
			options: {
				browsers: [ 'last 2 versions', 'ie 9', 'ios 6', 'android 4' ],
				map: true
			},
			files: {
				expand: true,
				flatten: true,
				src: '*.css',
				dest: ''
			}
		},
		// Ref. https://npmjs.org/package/grunt-contrib-uglify
		uglify: {
			options: {
				banner: '/*! \n * foodmania JavaScript Library \n * @package Foodmania \n */'
			},
			build: {
				src: [
					'js/vendor/jquery.cycle2.js',
					'js/vendor/jquery.meanmenu.js',
					'js/vendor/jquery.slicknav.js',
					'js/vendor/foundation.js',
					'js/vendor/foundation.reveal.js',
					'js/vendor/foundation.dropdown.js',
					'js/main.js'
				],
				dest: 'js/rtp-package-min.js'
			}
		},
		//https://www.npmjs.com/package/grunt-checktextdomain
		checktextdomain: {
			options: {
				text_domain: 'foodmania', //Specify allowed domain(s)
				keywords: [ //List keyword specifications
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d'
				]
			},
			target: {
				files: [ {
						src: [
							'*.php',
							'**/*.php',
							'!admin/lib/rt-themes-edd-license/**',
							'!node_modules/**',
							'!tests/**'
						], //all php
						expand: true
					} ]
			}
		},
		addtextdomain: {
			options: {
				//i18nToolsPath: '', // Path to the i18n tools directory.
				textdomain: 'foodmania', // Project text domain.
				updateDomains: [ 'buddypress', 'InspireBook', 'customizer-library', 'textdomain', 'rtmedia', 'rtPhotography' ]  // List of text domains to replace.
			},
			target: {
				files: {
					src: [
						'*.php',
						'**/*.php',
						'!node_modules/**',
						'!tests/**'
					]
				}
			}
		},
		// Internationalize WordPress themes and plugins
		// Ref. https://www.npmjs.com/package/grunt-wp-i18n
		//
		// IMPORTANT: `php` and `php-cli` should be installed in your system to run this task
		makepot: {
			target: {
				options: {
					cwd: '.', // Directory of files to internationalize.
					domainPath: 'languages/', // Where to save the POT file.
					exclude: [ 'node_modules/*' ], // List of files or directories to ignore.
					mainFile: 'index.php', // Main project file.
					potFilename: 'foodmania.pot', // Name of the POT file.
					potHeaders: { // Headers to add to the generated POT file.
						poedit: true, // Includes common Poedit headers.
						'Last-Translator': 'rtCamp <support@rtcamp.com>',
						'Language-Team': 'rtCampers <support@rtcamp.com>',
						'report-msgid-bugs-to': 'http://community.rtcamp.com/c/premium-themes',
						'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
					},
					type: 'wp-theme', // Type of project (wp-plugin or wp-theme).
					updateTimestamp: true // Whether the POT-Creation-Date should be updated without other changes.
				}
			}
		},
		// Watch for hanges and trigger compass and uglify
		// Ref. https://npmjs.org/package/grunt-contrib-watch
		watch: {
			sass: {
				files: [ '**/*.{scss,sass}' ],
				tasks: [ 'sass' ]
			},
			css: {
				files: [ '**/*.{scss,sass}' ],
				options: {
					livereload: true
				}
			},
			uglify: {
				files: '<%= uglify.build.src %>',
				tasks: [ 'uglify' ]
			},
			livereload: {
				options: { livereload: true },
				files: [ '*' ]
			}
		}
	} );

	// Register Task
	grunt.registerTask( 'default', [ 'sass', 'autoprefixer', 'checktextdomain', 'makepot', 'uglify', 'watch' ] );
	//grunt.registerTask( 'default', [ 'sass', 'autoprefixer', 'addtextdomain', 'checktextdomain', 'makepot', 'uglify', 'watch' ] );

};
