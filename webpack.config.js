const Encore = require('@symfony/webpack-encore');

Encore
    // Configuración de la entrada
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // Entrada
    .addEntry('app', './assets/app.js')

    // Habilitar un solo runtime chunk
    .enableSingleRuntimeChunk()

    // Habilitar soporte para Sass
    .enableSassLoader()

    // Habilitar React si estás usando React
    //.enableReactPreset()

    // Otras configuraciones...
;

module.exports = Encore.getWebpackConfig();
