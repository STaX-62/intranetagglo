const webpack = require('webpack');

module.exports = {
    pages: {
        intranetagglo355: {
            entry: 'src/main.js',
        },
        'intranetagglo-dashboard': 'src/dashboard.js'
    },
    chainWebpack: config => {
        if (config.plugins.has('extract-css')) {
            const extractCSSPlugin = config.plugin('extract-css')
            extractCSSPlugin && extractCSSPlugin.tap(() => [{
                filename: '[name].css'
            }])
        }
    },
    configureWebpack: {
        output: {
            filename: '[name].js'
        },
        optimization: {
            splitChunks: false
        },
        plugins: [
            new webpack.ProvidePlugin({
                'introJs': ['intro.js']
            })
        ]
    }
}