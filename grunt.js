var path = require( "path" );
/*global module:false*/
module.exports = function(grunt) {

    grunt.registerMultiTask( "copy", "Copy files to destination folder and replace @VERSION with pkg.version", function() {
        function replaceVersion( source ) {
            return source.replace( /@VERSION/g, grunt.config( "pkg.version" ) );
        }

        function copyFile( src, dest ) {
            if ( /(js|css)$/.test( src ) ) {
                grunt.file.copy( src, dest, {
                    process: replaceVersion
                });
            } else {
                grunt.file.copy( src, dest );
            }
        }

        var files = grunt.file.expandFiles( this.file.src ),
            target = this.file.dest + "/",
            strip = this.data.strip,
            renameCount = 0,
            fileName;
        if ( typeof strip === "string" ) {
            strip = new RegExp( "^" + grunt.template.process( strip, grunt.config() ).replace( /[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&" ) );
        }
        files.forEach(function( fileName ) {
            var targetFile = strip ? fileName.replace( strip, "" ) : fileName.replace( /src/ , 'jorbtwelve' )
            copyFile( fileName, target + targetFile );
        });
        grunt.log.writeln( "Copied " + files.length + " files." );
        for ( fileName in this.data.renames ) {
            renameCount += 1;
            copyFile( fileName, target + grunt.template.process( this.data.renames[ fileName ], grunt.config() ) );
        }
        if ( renameCount ) {
            grunt.log.writeln( "Renamed " + renameCount + " files." );
        }
    });


  // Project configuration.
  grunt.initConfig({
    meta: {
      version: '0.1.0',
      banner: '/*! JorbinTwelve- v<%= meta.version %> - ' +
        '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
        '* http://aaron.jorb.in/\n' +
        ' Unless otherwise specified, \n' +
        '* Copyright (c) <%= grunt.template.today("yyyy") %> ' +
        'Aaron Jorbin; Licensed GPLv2+ */'
    },
    files: {
        dist: "<%= meta.version %>",
    },
    lint: {
      files: [ 'src/**/*.js']
    },
    concat: {
      dist: {
        src: ['<banner:meta.banner>', '<file_strip_banner:src/js/app.js>'],
        dest: 'dist/js/app.js'
      }
    },
    min: {
      dist: {
        src: ['<banner:meta.banner>', '<config:concat.dist.dest>'],
        dest: 'build/js/app.min.js'
      }
    },
    watch: {
      files: '<config:lint.files>',
      tasks: 'lint'
    },
    copy: {
        dist:{
            src: ['build/js/app.min.js', 'src/**.php', 'src/style.css', 'src/img/**.svg'],
            renames : {
                'build/js/app.min.js' : 'jorbtwelve/js/app.min.js',
            },
            dest: "dist/<%= files.dist %>"
        }
    },
    jshint: {
      options: {
        curly: true,
        eqeqeq: true,
        immed: true,
        latedef: true,
        newcap: true,
        noarg: true,
        sub: true,
        undef: true,
        boss: true,
        eqnull: true,
        browser: true
      },
      globals: {}
    },
    uglify: {}
  });

  // Default task.
  grunt.registerTask('default', 'lint concat min copy');

};
