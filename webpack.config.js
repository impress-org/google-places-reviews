/**
 * External Dependencies
 */
const path = require('path');

/**
 * WordPress Dependencies
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');

module.exports = {
    ...defaultConfig,
    ...{
        entry: {
            'google-widget-admin': path.resolve(process.cwd(), 'includes/legacy/assets/js', 'admin-main.js'),
            'google-widget-admin-styles': path.resolve(process.cwd(), 'includes/legacy/assets/css', 'admin-main.scss'),
            'google-widget-public-styles': path.resolve(process.cwd(), 'includes/legacy/assets/css', 'public-main.scss'),
            'google-block': path.resolve(process.cwd(), 'src', 'index.js'),
        },
    },
};
