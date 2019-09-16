<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Localization</h1>
            <p>The routing language pack checks if it was sent in the url.</p>
            <h2>How to install</h2>
            <h4>Step 1</h4>
            <p>In <code>config/app.php</code> we find the parameters <code>locale</code> and <code>fallback_locale</code>
                and edit to the selected default language</p>
            <h4>Step 2</h4>
            <p>In <code>config/app.php</code>
                we add an array of languages ​​used: </p>
            <pre>
'locales' => [
    'pl',
    'en',
],</pre>
            <h4>Step 3</h4>
            <p>
                We register the Service Provider in <code>config/app.php</code>:</p>
            <pre>
 'providers' => [
         ...
         \PDAit\Base\Providers\LocaleServiceProvider::class,
         ...
 ],</pre>
            <h4>Step 4</h4>
            <p>In <code>app/Providers/RouteServiceProvider.php</code> we change function <code>webMapRoutes:</code></p>
            <pre>
protected function mapWebRoutes()
{
    Route::middleware('web')
            ->namespace($this->namespace)
            ->prefix('{_locale}')
            ->group(base_path('routes/web.php'));
}</pre>
            <p>To create a welcome route (without prefix) in the folder <code>routes</code>
                create a file <code>home.php</code> You can create a route without prefix there <code>{_locale}</code>, eg.:</p>
            <pre>
Route::get(
    '/',
    function () {
        return redirect()->route('home');
    }
); </pre>
            <p>And that's all, from now on the language will be automatically added to everyone </p>

        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
