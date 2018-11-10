let path = require('path');

module.exports = {

	mode: 'development',

	entry: {
		app: path.resolve(__dirname, 'assets/js/main.js')
	},

	output: {
		path: path.resolve(__dirname, 'public/js'),
		filename: '[name].js'
	}
}
