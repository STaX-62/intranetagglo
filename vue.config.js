const { defineConfig } = require('@vue/cli-service')

module.exports = {
    pages: {
        intranetagglo300: {
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
        }
    }
}