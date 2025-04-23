const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = (env, argv) => {
    const isEnvDevelopment = argv.mode === 'development';
    const isEnvProduction = argv.mode === 'production';

    const resourcesPath = './bundle/Resources';
    const buildPath = isEnvProduction ? 'public' : 'public/dev';

    return {
        entry: `${resourcesPath}/es6/app.js`,
        output: {
            path: path.resolve(__dirname, `${resourcesPath}/${buildPath}`),
            filename: 'js/admin/app.js',
        },
        devtool: isEnvDevelopment ? 'cheap-module-source-map' : '',
        resolve: {
            symlinks: false,
        },
        module: {
            rules: [
                // First, run the linter.
                // It's important to do this before Babel processes the JS.
                {
                    test: /\.(js|mjs|jsx)$/,
                    enforce: 'pre',
                    use: [
                        {
                            options: {
                                eslintPath: require.resolve('eslint'),
                            },
                            loader: require.resolve('eslint-loader'),
                        },
                    ],
                    exclude: /node_modules/,
                },
                {
                    oneOf: [
                        {
                            test: /\.(js|mjs|jsx)$/,
                            loader: require.resolve('babel-loader'),
                            options: {
                                cacheDirectory: true,
                                cacheCompression: isEnvProduction,
                                compact: isEnvProduction,
                            },
                        }
                    ],
                },
            ],
        },
        optimization: {
            minimizer: [
                new TerserPlugin({
                    extractComments: false,
                    terserOptions: {
                        output: {
                            comments: false,
                        },
                    },
                }),
            ],
        },
        plugins: [
            new CleanWebpackPlugin({
                cleanOnceBeforeBuildPatterns: ['**/*', '!icons', '!dev', '!icons/**', '!dev/**'],
            }),
        ],
    };
};
