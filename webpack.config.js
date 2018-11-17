let path = require('path');

let MiniCssExtractPlugin = require('mini-css-extract-plugin');
let CleanPlugin = require('clean-webpack-plugin');
let CopyPlugin = require('copy-webpack-plugin');
let ImageminPlugin = require('imagemin-webpack-plugin').default;


module.exports = {

    mode: 'development',

    entry: {
        app: [
            path.resolve(__dirname, 'assets/js/main.js'),
            path.resolve(__dirname, 'assets/css/main.scss'),
        ]
    },

    output: {
        path: path.resolve(__dirname, 'public/assets'),
        filename: '[name].js'
    },

    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            // you can specify a publicPath here
                            // by default it use publicPath in webpackOptions.output
                            // publicPath: path.resolve(__dirname, 'public/css')
                        }
                    },
                    "css-loader", // translates CSS into CommonJS
                    "sass-loader" // compiles Sass to CSS, using Node Sass by default
                ]
            },
            {
                test: /\.(png|jpg|gif|svg)$/i,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 8192
                        }
                    }
                ]
            }
        ]
    },

    plugins: [

        new MiniCssExtractPlugin({
            filename: "[name].css",
        }),

        new CleanPlugin(['public/assets'], {
            root:     __dirname,
            verbose:  true,
            dry:      false
        }),

        new CopyPlugin([{
            from: path.resolve(__dirname, "assets/images"),
            to: path.resolve(__dirname, "public/assets/images"),
        }]),

        new ImageminPlugin({ test: /\.(jpe?g|png|gif|svg)$/i })

    ]
};
