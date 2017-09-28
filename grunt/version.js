/* jshint node:true */
//https://github.com/kswedberg/grunt-version
module.exports = {
    options: {
        pkg: {
            version: '<%= package.version %>'
        }
    },
    project: {
        src: [
            'package.json'
        ]
    },
    style: {
        options: {
            prefix: 'Version\\:\\s'
        },
        src: [
            'elementor-navmenu.php'

        ]
    },
    constants: {
	    options: {
		    prefix: 'ELEMENTOR_MENUS_VERSION\'\,\\s+\''
	    },
	    src: [
		    'elementor-navmenu.php',
	    ]
    }
};