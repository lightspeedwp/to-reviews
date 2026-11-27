
/**
 * External dependencies
 */
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	// CI sets SENTRY_SOURCEMAPS to upload maps to Sentry. hidden-source-map writes
	// .map files without changing the built JS, so it matches the committed build/.
	devtool: process.env.SENTRY_SOURCEMAPS ? 'hidden-source-map' : defaultConfig.devtool,
	entry: {
		...defaultConfig.entry(),
		// CSS files
		'style': path.resolve( process.cwd(), 'assets/css/to-reviews.scss' ),
		'admin': path.resolve( process.cwd(), 'assets/css/to-reviews-admin.scss' ),

		// JavaScript files
		'admin-script': path.resolve( process.cwd(), 'assets/js/to-reviews-admin.js' ),
		'frontend': path.resolve( process.cwd(), 'assets/js/to-reviews.js' ),
	},

	resolve: {
		alias: {
			'@utils': path.resolve( __dirname, 'src/utils/' ),
		},
	},

	plugins: [
		...defaultConfig.plugins,
		new RemoveEmptyScriptsPlugin(),
	],

	output: {
		...defaultConfig.output,
		path: path.resolve( process.cwd(), 'build' ),
	}
};
