const { defineConfig } = require('@vue/cli-service')

module.exports = {
    pages: {
        intranetagglo92: {
            // entry for the page
            entry: 'src/main.js',
        },
        // when using the entry-only string format,
        // template is inferred to be `public/subpage.html`
        // and falls back to `public/index.html` if not found.
        // Output filename is inferred to be `subpage.html`.
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