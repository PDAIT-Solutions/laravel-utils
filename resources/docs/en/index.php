<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-9 my-5">

            <h1>PDAIT Larvel Utils</h1>
            <p>
                The package extends Laravel's basic functionalities with those used in everyday work.
            </p>

            <h2>Installation</h2>
            <h4>Step 1</h4>
            <pre>composer require pdait/laravel-utils "^1.0"</pre>

            <h4>Step 2</h4>
            <p>
                In file <code>config\app.php</code>
                register providers, unless it happened:
            </p>
            <pre>
'providers' => [
    ...

    /*
     * Package Service Providers...
     */
     ...
     PDAit\Base\Providers\BaseServiceProvider::class,
     PDAit\Base\Providers\TableServiceProvider::class,
     ...

],</pre>
            <p>
                To register a package.
            </p>
            <h4>Step 3</h4>
            <p>We install assets. Our applications will be supported by the webpack and it will sit in it
                js logic .</p>
            <p>
                To move public files to the <code> resources </code> folder, use the command:
            </p>
            <pre>php artisan vendor:publish --tag=pdait --force</pre>
            <div class="alert alert-info">
                <h3>PRO TIP</h3>
                <p>
                    Scripts require jQuery. The configuration of Webpack itself is quite complicated. Minimum
                    configuration is to create a global plugin to use jQuery in each file.
                </p>
                <p>
                    I will show a mini-tutorial on how to best configure the environment. Of course, I refer to
                    Laravel documentation for more.</p>
                <h5>Step 1</h5>
                <p>

                    If you do not use Vue.js, I recommend starting the configuration by removing unneeded files:
                </p>
                <pre>php artisan preset none</pre>

                <h5>Step 2</h5>
                <p>
                    We install packages:</p>
                <pre>npm install</pre>
                <h5>Step 3</h5>
                <p>Install jQuery:</p>
                <pre>npm i jquery</pre>
                <h5>Krok 4</h5>
                <p>
                    We configure the webpack. Configuration minimum:</p>
                <pre>
const mix = require('laravel-mix')

/*
|--------------------------------------------------------------------------
| Mix Asset Management
|--------------------------------------------------------------------------
|
| Mix provides a clean, fluent API for defining some Webpack build steps
| for your Laravel application. By default, we are compiling the Sass
| file for the application as well as bundling up all the JS files.
|
*/

mix.autoload({
jquery: ['$', 'window.$', 'jQuery', 'window.jQuery']
})

mix.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css')
.version() </pre>
                <h5>Step 5</h5>
                <p>
                    We install Bootstrap:</p>
                <pre>
npm install popper.js --save
npm i bootstrap</pre>
                <h5>Step 6</h5>
                <p>We install Bootsrap Table's:</p>
                <pre>npm i bootstrap-table</pre>
                <h5>Step 7</h5>
                <p>
                    We turn on the observer npm:</p>
                <pre>npm run watch</pre>
                <h5>Step 8</h5>
                <p>We import necessary <code> .js </code> files in<code>resources/js/app.js</code></p>
                <pre>
import 'bootstrap'
import 'bootstrap-table'
import 'bootstrap-table/dist/bootstrap-table-locale-all.min'
import './pdait/pdait'</pre>
                <h5>Step 9</h5>
                <p>
                    We import necessary files <code>.scss</code> in <code>resources/sass/app.scss</code></p>
                <pre>
@import "~bootstrap/scss/bootstrap";
@import "~bootstrap-table/dist/bootstrap-table";</pre>
                <h5>Step 9</h5>
                <p>We embed assets in blades:</p>
                <p>CSS:</p>
                <pre>&lt;link rel="stylesheet" type="text/css" href="{{asset(mix('css/app.css'))}}"&gt;</pre>
                <p>JS:</p>
                <pre>&lt;script src="{{asset(mix('js/app.js'))}}"&gt;&lt;/script&gt;</pre>
            </div>
            <h4>Step 4</h4>
            <p>
                We install Bootsrap Table's (if we haven't done it earlier in the mini-tutorial):</p>
            <pre>npm i bootstrap-table</pre>
            <h4>Step 5</h4>
            <p>We clean the cache :</p>
            <pre>php artisan cache:clear</pre>
        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
