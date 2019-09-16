<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-9 my-5">

            <h1>PDAit Larvel Utils</h1>
            <p>
                Paczka rozszerza podstawowe funkcjonalności Laravel'a o te używane w codziennej pracy.
            </p>

            <h2>Instalacja</h2>
            <h4>Krok 1</h4>
            <pre>composer require pdait/laravel-utils "^1.0"</pre>

            <h4>Krok 2</h4>
            <p>
                W pliku <code>config\app.php</code> zarejestruj providery, o ile nie stało się to samo:
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
                Po to aby zarejestrować paczkę.
            </p>
            <h4>Krok 3</h4>
            <p>Instalujemy assety. Nasze aplikacje będą obsługiwane przez webpacka i to w nim będzie siedzieć cała
                logika js'a.:</p>
            <p>Aby przerzucić pliki publiczne do folderu <code>resources</code>, użyj komendy:</p>
            <pre>php artisan vendor:publish --tag=pdait --force</pre>
            <div class="alert alert-info">
                <h3>PRO TIP</h3>
                <p>
                    Skrypty wymagają jQuery. Konfiguracja Webpack'a sama w sobie jest dość skomplikowana. Minimum
                    konfiguracyjne to stworzenie globalnego pluginu do użycia jQuery w każdym pliku.
                </p>
                <p>Pokaże mini-tutorial w jaki sposób najskuteczniej skonfiguować środowisko. Oczywiście odsyłam do
                    dokumentacji laravela po więcej.</p>
                <h5>Krok 1</h5>
                <p>
                    Ponieważ nie używamy Vue.js zalecam rozpoczecie konfiguracji od usunięcia niepotrzebnych plików:
                </p>
                <pre>php artisan preset none</pre>

                <h5>Krok 2</h5>
                <p>Instalujemy paczki:</p>
                <pre>npm install</pre>
                <h5>Krok 3</h5>
                <p>Instalujemy jQuery:</p>
                <pre>npm i jquery</pre>
                <h5>Krok 4</h5>
                <p>Konfigurujemy webpacka. Minimum konfiguracyjne:</p>
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
                <h5>Krok 5</h5>
                <p>Instalujemy Bootstrap'a:</p>
                <pre>
npm install popper.js --save
npm i bootstrap</pre>
                <h5>Krok 6</h5>
                <p>Instalujemy Bootsrap Table's:</p>
                <pre>npm i bootstrap-table</pre>
                <h5>Krok 7</h5>
                <p>Włączamy observer'a npm:</p>
                <pre>npm run watch</pre>
                <h5>Krok 8</h5>
                <p>Importujemy niezbędne pliki <code>.js</code> w <code>resources/js/app.js</code></p>
                <pre>
import 'bootstrap'
import 'bootstrap-table'
import 'bootstrap-table/dist/bootstrap-table-locale-all.min'
import './pdait/pdait'</pre>
                <h5>Krok 8</h5>
                <p>Importujemy niezbędne pliki <code>.scss</code> w <code>resources/sass/app.scss</code></p>
                <pre>
@import "~bootstrap/scss/bootstrap";
@import "~bootstrap-table/dist/bootstrap-table";                </pre>
                <h5>Krok 9</h5>
                <p>Osadzamy assety w blade:</p>
                <p>CSS:</p>
                <pre>&lt;link rel="stylesheet" type="text/css" href="{{asset(mix('css/app.css'))}}"&gt;</pre>
                <p>JS:</p>
                <pre>&lt;script src="{{asset(mix('js/app.js'))}}"&gt;&lt;/script&gt;</pre>
            </div>
            <h4>Krok 4</h4>
            <p>Instalujemy Bootsrap Table's (jeżeli nie zrobiliśmy tego wcześniej w mini-tutorialu):</p>
            <pre>npm i bootstrap-table</pre>
            <h4>Krok 5</h4>
            <p>Czyścimy cache'a:</p>
            <pre>php artisan cache:clear</pre>
        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
