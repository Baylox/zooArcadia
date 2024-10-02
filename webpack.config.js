const Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for Symfony 4.0 and lower
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('global', './assets/scss/global.scss')
    // enables Sass/SCSS support
    .enableSassLoader()
    // enables source maps during development
    .enableSourceMaps(!Encore.isProduction())
    // enable versioning (file hashing) in production
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
