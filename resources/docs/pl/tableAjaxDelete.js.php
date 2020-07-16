<html lang="en">
    <?php include('head.php') ?>
    <body>
        <div class="container">
            <div class="row">

                <div class="col-lg-3 my-5">
                    <?php include('sidebar.php') ?>
                </div>

                <div class="col-lg-9 my-5">
                    <h1 id="toc_0">TableAjaxDelete.js</h1>

                    <p>Biblioteka pozwalająca na wykorzystanie przycisku w wierszu tabeli, w celu wywołania modala, który zapyta czy skasować rekord i skasowania go w momencie potwierdzenia przez użytkowanika.</p>

                    <h2 id="toc_1">Wykorzystanie</h2>

                    <p>W celu skorzystania z biblioteki należy jedynie dodać dwa atrybuty do elementu, który ma wywołać akcje (element musi znajdować się w <code>td &gt; div</code>).</p>

                    <table>
                        <thead>
                            <tr>
                                <th>Atrybut</th>
                                <th>Wartość</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>data-url</td>
                                <td>Adres, który ma być wywołany po zatwierdzeniu przez użytkownika</td>
                            </tr>
                            <tr>
                                <td>data-func</td>
                                <td>Musi być ustawiony na <code>delete-row</code></td>
                            </tr>
                        </tbody>
                    </table>

                    <p>Dla przykładu:
                        <code>
                            &lt;td&gt;
                            &lt;div&gt;
                            &lt;a
                            data-url=&quot;http://api.pl/usun/2&quot;
                            data-func=&quot;delete-row&quot;
                            &gt;Usuń&lt;/a&gt;
                            &lt;/div&gt;
                            &lt;/td&gt;
                        </code></p>
                </div>

            </div>

        </div>
    </div>
    <?php include('footer.php') ?>
</body>
</html>
