/* jshint node:true */
// https://github.com/blazersix/grunt-wp-i18n
module.exports = {
    fixDomains: {
        options: {
            textdomain: '<%= package.textdomain %>',
            updateDomains: ['elementor']
        },
        files: {
            src: [
                './modules/branding/widgets/elementor-branding.php'
            ]
        }
    },
};
