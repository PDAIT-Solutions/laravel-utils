<html lang="en">
    <?php include('head.php') ?>
    <body>
        <div class="container">
            <div class="row">

                <div class="col-lg-3 my-5">
                    <?php include('sidebar.php') ?>
                </div>

                <div class="col-lg-9 my-5">
                    <h1 id="toc_0">TableWatcher.js</h1>

                    <p>TableWatcher.js to kod pozwalający na wykrycie zmian w tabelach. Jeżeli dzieci tabeli są aktualizowane przez ajax to należy zaktualizować triggery tj. na przykład te na przycisku do usuwania - wykorzystane w tableAjaxDelete.js</p>

                    <h2 id="toc_1">Wykorzystanie</h2>

                    <p>W celu zarejestrowania nowego callbacku, który będzie wołany jeżeli nastąpi zmiana w jakiejkolwiek tabeli (tbody) w DOMie, należy wywołać <code>registerTableWatcher(callback)</code></p>
                </div>

            </div>

        </div>
    </div>
    <?php include('footer.php') ?>
</body>
</html>
