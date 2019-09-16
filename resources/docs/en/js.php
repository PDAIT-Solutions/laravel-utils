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
                The package comes with auxiliary functions for basic things in js. The functions are registered
                global. They can be used throughout the entire project:
            </p>
            <h4>dump(mixed object)</h4>
            <p>Disable console.log. To enable it in <code> base.blade.php </code> add: </p>
            <pre>
&lt;script&gt;
  global.debug = {{ config('app.debug') }};
&lt;/script&gt;</pre>
            <h4> cleanForm (object $el)</h4>
            <p>
                Clears the form. Example of use:
            </p>
            <pre>cleanForm($('#form'))</pre>

            <h4>makeId()</h4>
            <p>
                Returns a 10-letter string of random characters</p>
            <h4>showAlert(message, type, strong = false)</h4>
            <p>
                Shows the information for 5 seconds, then it disappears
            </p>
            <ul>
                <li>message - message strnig</li>
                <li>type - one of <i>info, danger, success, warning</i></li>
                <li>strong - message with a stronger font at the beginning of the message</li>
            </ul>
            <h4>registerTableLink (id, route)</h4>
            <div class="alert alert-danger"><b>WARNING!</b> So that you can use the function
                <code>registerTableLink()</code>
                to an Ajax table must be sent <code>id</code></div>
            <p>
                Registers the table clicker. All columns become clickable outside <b><i>actions</i></b>. </p>
            <p>
                The <i> route </i> string automatically substitutes '<i> __id__ </i>' for the id that is sent to the table
            </p>
            <ul>
                <li>id - ID table</li>
                <li>route -
                    URL to which the browser should go after clicking the link</li>
            </ul>
            <h2>
                Form events</h2>
            <p>                The forms contain several events with the help of which we can perform any actions in js.</p>
            <h4>ajaxify:process_start</h4>
            <p>Always fired. Example of use:</p>
            <pre>$(document).on('ajaxify:process_start', '#form', function(){})</pre>
            <h4>ajaxify:process_finish</h4>
            <p>
                Always fired. Example of use:</p>
            <pre>$(document).on('ajaxify:process_finish', '#form', function(){})</pre>
            <h4>ajaxify:form_success</h4>
            <p>
                Launched when we receive <code> 'status' => 'success' </code> in response. Example of use:</p>
            <pre>$(document).on('ajaxify:process_finish', '#form', function(data){})</pre>
            <h4>ajaxify:form_errors</h4>
            <p>Fired when it's not come to <code> ajaxify: form_success </code>. Example of use:</p>
            <pre>$(document).on('ajaxify:form_errors', '#form', function(data, responseData){})</pre>
            <h4>ajaxify:form_error_el_not_found</h4>
            <p>
                Fired when the validation element was not found after its id attribute. Example of use:</p>
            <pre>$(document).on('ajaxify:form_error_el_not_found', '#form', function(value){})</pre>
            <h4>ajaxify:form_success_no_action</h4>
            <p>Runs when <code> data-no-success-action = "true" </code> is used in the configuration. Example of use:
            </p>
            <pre>$(document).on('ajaxify:form_success_no_action', '#form', function(){})</pre>
            <h4>ajaxify:form_success_no_redirect</h4>
            <p>
                Launched when there is no configuration attribute in the forum forum configuration. Example of use:</p>
            <pre>$(document).on('ajaxify:form_success_no_redirect', '#form', function(){})</pre>
        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
