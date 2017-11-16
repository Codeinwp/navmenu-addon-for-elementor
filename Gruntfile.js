/* jshint node:true */
/* global require */
module.exports = function (grunt) {
	'use strict';

	var loader = require( 'load-project-config' ),
		config = require( 'grunt-plugin-fleet' );
	config     = config();
	config.files.php.push( '!includes/class-tgm-plugin-activation.php' );
	// TODO: Fix javascript files and remove line below.
	config.files.js.push( '!assets/js/*.js' );
	loader( grunt, config ).init();
};
