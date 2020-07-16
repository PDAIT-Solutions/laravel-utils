<html lang="en">
    <?php include('head.php') ?>
    <body>
        <div class="container">
            <div class="row">

                <div class="col-lg-3 my-5">
                    <?php include('sidebar.php') ?>
                </div>

                <div class="col-lg-9 my-5">
                    <h1 id="toc_0">Modal.js</h1>

                    <p>Modal.js to prosta biblioteka pozwalająca na programowalne wyświetlenie modala Bootstrap bez konieczności umieszczania kodu <em>html</em> w szablonie blade. Biblioteka pozwala na ustawienie: tytułu, wiadomości, koloru przycisku zatwierdzającego akcje. Funcja zwraca <em>Promise</em>, który resolve&#39;uje się z parametrem, który przybiera wartość boolean - true: akcja zatwierdzona, false: anulowana.</p>

                    <h2 id="toc_1">Wykorzystanie</h2>

                    <p>Wykorzystanie jest bardzo proste i wymaga wywołania tylko jednej funckji, która jest dostępna globalnie.</p>

                    <div><pre><code class="language-javascript">modal(config = {})</code></pre></div>

                    <h3 id="toc_2">Obiekt config</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Parametr</th>
                                <th>Możliwe wartości</th>
                                <th>Opis</th>
                                <th>Domyślna wartość</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>title</td>
                                <td>string</td>
                                <td></td>
                                <td>Potwierdzenie wprowadzenia zmian</td>
                            </tr>
                            <tr>
                                <td>message</td>
                                <td>string</td>
                                <td></td>
                                <td>Czy na pewno chcesz kontynuować operacje?</td>
                            </tr>
                            <tr>
                                <td>type</td>
                                <td>&#39;primary&#39;, &#39;danger&#39;</td>
                                <td>Typ przycisku zatwierdzającego akcje</td>
                                <td>primary</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 id="toc_3">Przykładowe wykorzystanie</h3>

                    <div><pre><code class="language-javascript">modal({
                        title: &#39;Usuwanie klienta&#39;,
                        message: &#39;Czy na pewno chcesz usunać tego klienta?&#39;,
                        type: &#39;danger&#39;
                    }).then(decision =&gt; {
                        if (decision) {
                        // Usuwamy
                    } else {
                        // Nie robimy nic
                    }
                    })</code></pre></div>

                    <h3 id="toc_4">Informacje dodatkowe</h3>

                    <p>Modal zostanie zamknięty automatycznie po podjęciu przez użytkownika decyzji. Nie jest wymagana żadna dodatkowa akcja.</p>
                </div>

        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
