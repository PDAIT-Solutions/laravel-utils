<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Tłumaczenia</h1>
            <p>Paczka język do routingu sprawdza czy został wysłany w url'u. W razie potrzeby robi przekierowanie <code>301</code>.</p>
            <h2>Instalacja</h2>
            <h4>Krok 1</h4>
            <p>W <code>config/app.php</code> znajdujemy parametry <code>locale</code> oraz <code>fallback_locale</code> i edytujemy na wybrany język domyślny</p>
            <h4>Krok 2</h4>
            <p>W <code>config/app.php</code> dodajemy tablicę tablicę używanych języków: </p>
            <pre>
'locales' => [
    'pl',
    'en',
],</pre>
            <h4>Krok 3</h4>
            <p>Rejestrujemy Service Provider w <code>config/app.php</code>:</p>
            <pre>
 'providers' => [
         ...
         \PDAit\Base\Providers\LocaleServiceProvider::class,
         ...
 ],</pre>
            <h4>Krok 4</h4>
            <p>W <code>app/Providers/RouteServiceProvider.php</code> zmieniamy funkcję <code>webMapRoutes:</code></p>
            <pre>
protected function mapWebRoutes()
{
    Route::middleware('web')
            ->namespace($this->namespace)
            ->prefix('{_locale}')
            ->group(base_path('routes/web.php'));
}</pre>
            <p>Żeby stworzyć route powitalny (bez prefixu) w folderze <code>routes</code> utwórz plik <code>home.php</code> Możesz tam stworzyć routa bez prefixu <code>{_locale}</code>, np.:</p>
            <pre>
Route::get(
    '/',
    function () {
        return redirect()->route('home');
    }
); </pre>
            <p>I to wszystko, od teraz język będzie automatycznie dodany do każdego </p>

        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
