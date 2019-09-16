<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Javascript</h1>
            <p>
                Paczka wychodzi z funkcjami pomoczniczymi do podstawowych rzeczy w js'ie. Funkcje są zarejestrowane
                globalnie. Można ich używać w całym projekcie
            </p>
            <h4>dump(mixed object)</h4>
            <p>Wyłączalny console.log. Aby włączyć go w <code>base.blade.php</code> dodaj: </p>
            <pre>
&lt;script&gt;
  global.debug = {{ config('app.debug') }};
&lt;/script&gt;</pre>
            <h4> cleanForm (object $el)</h4>
            <p>Czyści formularz. Przykład użycia: </p>
            <pre>cleanForm($('#form'))</pre>

            <h4>makeId()</h4>
            <p>Zwraca 10 literowy ciąg losowych znaków</p>
            <h4>showAlert(message, type, strong = false)</h4>
            <p>Pokazuje na 5 sekund informację, potem ona znika</p>
            <ul>
                <li>message - wiadomość</li>
                <li>type - jedno z <i>info, danger, success, warning</i></li>
                <li>strong - wiadomość mocniejszą czcionką na początku wiadomości</li>
            </ul>
            <h4>registerTableLink (id, route)</h4>
            <div class="alert alert-danger"><b>UWAGA!</b> Żeby można było korzystać z funkcji
                <code>registerTableLink()</code> do tabelki w ajaxie musi być wysyłany <code>id</code></div>
            <p>Rejestruje klikacz tabelki. Wszystkie kolumny stają się klikalne poza <b><i>actions</i></b>. </p>
            <p>String parametru <i>route</i> automatycznie podmienia '<i>__id__</i>' na id które jest wysłane do tabelki
            </p>
            <ul>
                <li>id - ID tabelki</li>
                <li>route - url pod który ma przejść przeglądarka po kliknięciu linka</li>
            </ul>
            <h2>Eventy formularzy</h2>
            <p>Formularze zawierają kilka eventów z pomocą których możemy wykonać dowolne akcje w js'ie.</p>
            <h4>ajaxify:process_start</h4>
            <p>Odpalany zawsze. Przykład użycia:</p>
            <pre>$(document).on('ajaxify:process_start', '#form', function(){})</pre>
            <h4>ajaxify:process_finish</h4>
            <p>Odpalany zawsze. Przykład użycia:</p>
            <pre>$(document).on('ajaxify:process_finish', '#form', function(){})</pre>
            <h4>ajaxify:form_success</h4>
            <p>Odpalany kiedy otrzymamy w odpowiedzi <code>'status'=>'success'</code>. Przykład użycia:</p>
            <pre>$(document).on('ajaxify:process_finish', '#form', function(data){})</pre>
            <h4>ajaxify:form_errors</h4>
            <p>Odpalany kiedy nie jest odpalany <code>ajaxify:form_success</code>. Przykład użycia:</p>
            <pre>$(document).on('ajaxify:form_errors', '#form', function(data, responseData){})</pre>
            <h4>ajaxify:form_error_el_not_found</h4>
            <p>Odpalany kiedy element validacyjny nie został znaleziony po jego atrybucie id. Przykład użycia:</p>
            <pre>$(document).on('ajaxify:form_error_el_not_found', '#form', function(value){})</pre>
            <h4>ajaxify:form_success_no_action</h4>
            <p>Odpalany kiedy w konfiguracji użwany jest <code>data-no-success-action="true"</code>. Przykład użycia:
            </p>
            <pre>$(document).on('ajaxify:form_success_no_action', '#form', function(){})</pre>
            <h4>ajaxify:form_success_no_redirect</h4>
            <p>Odpalany kiedy w konfiguracji forumularza nie ma żadnego attrybutu konfiguracyjnego. Przykład użycia:</p>
            <pre>$(document).on('ajaxify:form_success_no_redirect', '#form', function(){})</pre>
        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
