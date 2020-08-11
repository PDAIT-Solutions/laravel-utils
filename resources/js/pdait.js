/* ---------------------------------------------------------------------------------------------------------------------
|
|  TABELE
|
--------------------------------------------------------------------------------------------------------------------- */
/**
 * Funkcja która pobiera parmetry z formularza wyszukwiania i wrzuca do query tabelki
 *
 * @param params
 * @returns {*}
 */
global.params = function params (params) {
  $('.pda-table-form').find('input, select').each(function () {
    params[$(this).attr('name')] = $(this).val()
  })

  return params
}

/**
 * Funkcja która pobiera
 *
 * @param id
 * @returns {boolean}
 */
global.filter = function filter (id) {
  $('#' + id).bootstrapTable('refresh')

  return false
}

/**
 * Event na dodący cookies z formularza
 */
$('.pda-table-form').submit(function () {
  var $fields = $(this).find('input, select') // Znajdowanie pól formularza

  $fields.each(function () {
    var name = $(this).data('cookie-id')
    var val = $(this).val()

    if (typeof name !== 'undefined') {

      var d = new Date()
      d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000))
      var expires = 'expires=' + d.toUTCString()

      let cookie = name + '=' + val + ';' + expires

      document.cookie = cookie // Dodawnie cookies domyślnie na tydzień

    }
  })
})

$('body').on('page-change.bs.table', function () {
  $('.page-jump-to, .fixed-table-pagination li, .fixed-table-pagination ul, .fixed-table-pagination a').addClass('pe-none')
  $('.page-jump-to, .fixed-table-pagination a').addClass('disabled-pagination-item')
})

$('body').on('load-success.bs.table', function () {
  $('.page-jump-to, .fixed-table-pagination li, .fixed-table-pagination ul, .fixed-table-pagination a').removeClass('pe-none')
  $('.page-jump-to, .fixed-table-pagination a').removeClass('disabled-pagination-item')
})

/**
 * Dzięki tej funkcji przy wysłaniu w ajaxie class zostanie dodana klasa do rowa tabeli
 *
 * @param row
 * @param index
 * @returns {{classes: *}}
 */
global.rowStyle = function rowStyle (row, index) {
  return { classes: row.class }
}

/**
 * Zarejestruj klikacz tabelkowy
 *
 * @param id
 * @param route
 * @param excludedColumns
 */
global.registerTableLink = function registerTableLink (id, route, excludedColumns = []) {
  $(id).on('click-cell.bs.table', function (field, value, row, element) {
    if (value != 'actions' && value != '0' && !excludedColumns.includes(value)) {
      document.location = route.replace('__id__', element.id)
    }
  })
  $(id).css('cursor', 'pointer')
}

/* ---------------------------------------------------------------------------------------------------------------------
|
|  FORMULARZE
|
--------------------------------------------------------------------------------------------------------------------- */

/**
 * Event na wyczyszczenie formularza
 */
$('body').on('click', '.btn-clear-form', function () {
  var $form = $(this).closest('form')

  var tableId = $form.attr('id')
  var $table = $('#' + tableId.substring(0, tableId.length - 5))
  var options = $table.bootstrapTable('getOptions')
  options.sortName = $table.data('sort-name')

  $table.bootstrapTable('refreshOptions', options)

  cleanForm($form)
  $form.find('button[type="submit"]').click()
})

$('table').on('sort.bs.table', function (e, name, order) {
  // The table column that we are sorting by
  var field = $('table').find('th[data-field="' + name + '"] .sortable')
  // If it's not the field we are currently sorting by
  if (!field.is('.asc, .desc')) {
    // Change the sort order that's set in data-order ('asc' by default)
    var options = $(this).bootstrapTable('getOptions')
    options.sortOrder = options.columns[0].find(function (option) { return option.field == name }).order
    // Now the table will be sorted using the order that we set
  }
})

/**
 * Funkcja do czyszczenie formularza
 *
 * @param $el
 */
global.cleanForm = function cleanForm ($el) {
  $el.find('input, textarea')
    .not(':button, :submit, :reset, :hidden')
    .val('')

  $el.find('select option')
    .prop('selected', false)
    .prop('selected', false)

  $el.find('input,select,textarea').trigger('change')

  $el.find('.select2-selection__choice__remove').click()
  $el.find('.select2[multiple]').select2('close')
}

$(function () {
  activeAjaxForm()
})

/**
 * Event na wysłanie formularza
 */
global.activeAjaxForm = function activeAjaxForm () {
  $('body').on('submit', 'form[data-ajax="true"]', function (e) {
    // Wyłącznie domyślnych
    e.preventDefault()

    // Wywołanie funkcji ajaxa
    ajaxify_submitForm(this)
  })
}

$(document).on('ajaxify:process_start', 'form', function () {
  $(this).find('button[type="submit"],input[type=submit]').append(' <i class="fas fa-spinner fa-spin"></i>').attr('disabled', 'disabled')
})
$(document).on('ajaxify:process_finish', 'form', function () {
  $(this).find('button[type="submit"],input[type=submit]').removeAttr('disabled').find('.fa-spinner').remove()
})

/**
 * Funkcja inicjująca wysłanie formularza przez ajaxa
 *
 * @param el
 */
global.ajaxify_submitForm = function ajaxify_submitForm (el) {
  var $el = $('#' + $(el).attr('id'))

  var action = $el.attr('action') // Pobiera akcje
  var method = $el.attr('method') // Pobiera metode
  var formData = new FormData(el) // Pobiera wszystkie pola formularza

  $el.trigger('ajaxify:process_start')

  // Wysłanie ajaxa
  ajaxify_sendAjaxRequest(action, method, formData, function (data) {

    ajaxify_processData($el, data)
    $el.trigger('ajaxify:process_finish', data)
  })
}

/**
 * Funckja wysyłająca formularz przez ajaxa
 *
 * @param action
 * @param method
 * @param data
 * @param doneCallback
 */
global.ajaxify_sendAjaxRequest = function ajaxify_sendAjaxRequest (action, method, data, doneCallback) {
  $.ajax({
    url: action,
    method: method,
    processData: false, // Bez tego nie działa obiekt FormData
    contentType: false, // Bez tego nie działa obiekt FormData
    data: data
  }, 'json').always(doneCallback)
}

/**
 * Funkcja do przetwarzania wyniku
 *
 * @param $el
 * @param data
 */
global.ajaxify_processData = function ajaxify_processData ($el, data) {
  // Przetwarzanie wyniku
  if (typeof data.data !== 'undefined' && data.data.status == 'success') {
    $el.trigger('ajaxify:form_success', [data.data]) // Wysłanie customowego eventa
  } else {
    $el.trigger('ajaxify:form_errors', [data.responseJSON]) // Wysłanie customowego eventa
  }
}

/**
 * Funkcja dumpująca
 *
 * @param data
 */
global.dump = function dump (data) {
  if (debug) {
    console.log(data)
  }
}

/**
 * W chwili błędu formularza
 */
$(document).on('ajaxify:form_errors', 'form', function (e, data) {
  console.log(data)
  var $form = $(this)
  $(this).find('button[type="submit"]').removeAttr('disabled').find('.fa-spinner').remove()

  var animated = false

  $.each(data.errors, function (key, value) {

    html = '<div class="text-danger form-error">'

    $.each(value, function (keyvalue, error) {
      html = html + '<span class="text-lowercase">' + error + '</span><br>'
    })

    html = html + '</div>'

    $el = getFormElem(key, $form)

    if ($el.length == 0) {
      $($form).trigger('ajaxify:form_error_el_not_found', value)
    } else if ($el.length != 0 && !animated) {
      showAlert(value, 'danger')
      animated = true
      animateToElem($el)
    }

    $parent = $el.parent()

    $parent.append(html)
    $parent.find('label').addClass('text-danger')
    $el.addClass('is-invalid')
  })
})

/**
 * Pobiera element na podstawie nazwy
 *
 * @param key
 *
 * @returns {*}
 */
global.getFormElem = function getFormElem (key, $form) {
  $el = $form.find('[name="' + key + '"]')

  if ($el.length == 0) {
    var name = key.replace(/\.[0-9a-zA-Z]+$/, '')
    var index = key.match(/[0-9a-zA-Z]+$/)

    $el = $form.find('[name="' + name + '\[' + index + '\]"]')
  }

  return $el
}

/**
 * Funkcja w prosty sposób animuje scrolla do id
 *
 * @param id
 */
global.animateToElem = function animateToElem ($el) {
  var idOffset = $el.closest('form').offset().top
  var htmlOffset = $(document).scrollTop()
  if (idOffset < htmlOffset) {
    $('html, body').animate({
      scrollTop: idOffset - 100
    }, 200)
  }
}

/**
 * Event ajaxify:process_start
 */
$(document).on('ajaxify:process_start', 'form', function (e) {
  $el = $(this)
  $el.find('label').removeClass('text-danger')
  $el.find('.a2lix_translations .nav-link').removeClass('text-danger')
  $el.find('.is-invalid').removeClass('is-invalid')
  $('.form-error').remove()
})

/**
 * Event ajaxify:form_success
 */
$(document).on('ajaxify:form_success', 'form', function (e, data) {
  if ($(this).data('redirect-from-ajax')) {
    succesAction = data.redirect
    window.location.href = succesAction
  } else if ($(this)[0].hasAttribute('data-success-action') && $(this).data('success-action')) {
    succesAction = $(this).data('success-action')
    window.location.href = succesAction
  } else if ($(this)[0].hasAttribute('data-no-success-action') && $(this).data('no-success-action')) {
    $(this).trigger('ajaxify:form_success_no_action', data)
  } else {
    $(this).trigger('ajaxify:form_success_no_redirect', data)
  }
})

/**
 * Event na bład formularza kiedy nie znaleziono pola
 */
$(document).on('ajaxify:form_error_el_not_found', 'form', function (e, message) {
  showAlert(message, 'danger')
})

/**
 * Event na sukces formularza  bez redirectu
 */
$(document).on('ajaxify:form_success_no_redirect', 'form', function () {
  if (typeof trans !== 'undefined') {
    showAlert(trans.form_success, 'success')
  } else {
    showAlert('Zapis został przetworzony pomyślnie!', 'success')
  }
  $(this).closest('.modal').modal('hide')
  $('table').bootstrapTable('refresh')
  if ($(this)[0].hasAttribute('data-clean-after-success')) {
    cleanForm($(this))
  }
})

/* ---------------------------------------------------------------------------------------------------------------------
|
|  ALERTY
|
--------------------------------------------------------------------------------------------------------------------- */

/**
 * Dość felxi funkcja do dodawania alertów, myślę, że na nasze potrzeby wystarczy
 *
 * @param message
 * @param type
 * @param strongd
 */
global.showAlert = function showAlert (message, type, strong = false) {
  var id = makeId()

  var html =
    '<div id="' + id + '" class="alert pdait-alert alert-' + type + ' alert-dismissible fade show m-0" role="alert" style="position: fixed; width: 100%; left: 0; bottom: 0; z-index: 100000; border-radius: 0">' +
    '<div class="container position-relative">' +
    '  <strong>' + (strong ? strong : '') + '</strong> ' +
    message +
    '  <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="top: -12px">' +
    '    <span aria-hidden="true">&times;</span>' +
    '  </button>' +
    '</div>' +
    '</div>'
  $('body').append(html)
  $('#' + id).hide()
  $('#' + id).slideDown('fast')
  setTimeout(function () {
    $('#' + id).fadeOut(500)
    setTimeout(function () {
      $('#' + id).remove()
    }, 500)
  }, 5000)
}

/* ---------------------------------------------------------------------------------------------------------------------
|
|  Funkcje pomocnicze
|
--------------------------------------------------------------------------------------------------------------------- */

/**
 * Funkcja do tworzenia losowego ciągu znaków
 *
 * @returns {string}
 */
global.makeId = function makeId () {
  var text = ''
  var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'

  for (var i = 0; i < 10; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length))

  return text
}

$('body').on('click', '[data-modal]', function () {
  $modal = $($(this).data('modal')).modal('show')
  $modal.find('.modal-content').html('').load($(this).data('ajax-load'), function () {
    $(window).trigger('modal-loaded')
  })
})

String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

/**
 *Funkcja do obsługi API
 */
window.api= function api(url, body, handleData) {
    body = JSON.stringify(body);

    if (typeof body == "undefined" || body == "") {
        body = "{}";
    }

    $.ajax(url, {
        crossDomain: true,
        contentType: 'application/json',
        dataType: "json",
        type: "POST",
        data: body,
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
        },
        success: function (data) {
            if (data.action === 'redirect' && typeof data.url !== 'undefined') {
                document.location.href = data.url
            }

            if (data.status === true && data.action === 'refresh')
            {
                location.reload();
            }

            if (data.status === false && typeof data.message != 'undefined')
            {
                showAlert(data.message, 'danger');
            }

            if(data.status === true && typeof data.message != 'undefined'){
                showAlert(data.message, 'success');
            }

            handleData(data);
        },
        error: function (error) {

            if (typeof error.responseJSON.message !== 'undefined')
            {
                showAlert(error.responseJSON.message, 'danger');
            }

        }
    })
}


/**
 * Programowalne modale
 */
$(() => {
    let modal
    let title
    let body
    let closeBtn
    let cancelBtn
    let okBtn
    let okTriggers
    let cancelTriggers
    let inputNodes = []
    const labelTemplate = `<label for="{{name}}" style="margin-top:20px;">{{label}}</label>`
    const inputTemplate = `<input type="{{type}}" name="{{name}}" placeholder="{{placeholder}}" value="{{value}}" class="form-control">`
    const selectTemplate = `<select type="text" name="{{name}}" class="form-control"></select>`
    const optionTemplate = `<option value="{{value}}">{{name}}</option>`

    function setTitle(text) {
        $(title).text(text)
    }

    function setMessage(text) {
        $(body).text(text)
    }

    function setType(type) {
        if (type === 'primary') {
            $(okBtn).addClass('btn-primary')
        } else if (type === 'danger') {
            $(okBtn).addClass('btn-danger')
        }
    }

    function createOption(value, name) {
        let option = optionTemplate.repeat(1)
        option = option.replace('{{value}}', value)
        option = option.replace('{{name}}', name)

        return $(option)
    }

    function createLabel(labelFor, text) {
        let label = labelTemplate.repeat(1)
        label = label.replace('{{name}}', labelFor)
        label = label.replace('{{label}}', text)

        return $(label)
    }

    function createInput(data) {
        const id = data.id
        const placeholder = data.placeholder || ''
        const value = data.value || ''
        const label = data.label || ''
        const type = data.type || 'text'
        let input = inputTemplate.repeat(1)
        input = input.replace(/{{name}}/g, id)
        input = input.replace('{{placeholder}}', placeholder)
        input = input.replace('{{value}}', value)
        input = input.replace('{{label}}', label)
        input = input.replace('{{type}}', type)
        input = $(input)
        $(input).val(value)

        return input
    }


    function createSelect(data) {
        let select = selectTemplate.replace('{{name}}', data.id)
        select = $(select)

        data.options.map(option => select.append(createOption(option.value, option.name)))

        return select
    }

    function setInputs(inputs) {
        for (const input of inputs) {
            const type = input.type || 'input'
            const label = input.label || false
            const body = $('#programmable-modal .modal-body')
            let inputNode

            if (type === 'select') {
                inputNode = createSelect(input)
            } else {
                inputNode = createInput(input)
            }

            inputNodes.push(inputNode)

            if (label) {
                body.append(createLabel(input.id, label))
            }

            body.append(inputNode)
        }
    }

    function buildModal() {
        return $(`<div class="modal fade" id="programmable-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Potwierdzenie wprowadzenia zmian</h5>
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Czy na pewno chcesz kontynuować operacje?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-cancel-btn">Anuluj</button>
                <button type="button" class="btn btn-primary modal-ok-btn">Tak</button>
            </div>
        </div>
    </div>
</div>`)
    }

    function assignNodes() {
        modal = $('#programmable-modal')
        title = modal.find('.modal-title')
        body = modal.find('.modal-body p')
        closeBtn = modal.find('.close')
        cancelBtn = modal.find('.modal-cancel-btn')
        okBtn = modal.find('.modal-ok-btn')
        okTriggers = [okBtn];
        cancelTriggers = [cancelBtn, closeBtn]
    }

    function injectModal() {
        $('html, body').append(buildModal())
        assignNodes()
    }

    function modalPresent() {
        return modal.length !== 0
    }

    function openModal() {
        $(modal).modal({
            show: true,
            focus: true,
            keyboard: false,
            backdrop: 'static'
        })
    }

    function reset() {
        setTitle('Potwierdzenie wprowadzenia zmian')
        setMessage('Czy na pewno chcesz kontynuować operacje?')
        $(okBtn).removeClass('btn-primary').removeClass('btn-danger')
        inputNodes.splice(0, inputNodes.length)
        $('#programmable-modal .modal-body').html('<p>Czy na pewno chcesz kontynuować operacje?</p>')
    }

    function closeModal() {
        $(modal).modal('hide')
        reset()
    }

    function setup(config) {
        assignNodes()

        if (!modalPresent()) {
            injectModal()
        }

        if (config.hasOwnProperty('title')) {
            setTitle(config.title)
        }

        if (config.hasOwnProperty('message')) {
            setMessage(config.message)
        }

        if (config.hasOwnProperty('type')) {
            setType(config.type)
        }

        if (config.hasOwnProperty('inputs')) {
            setInputs(config.inputs)
        }
    }

    function gatherInputsData() {
        let data = {}

        for (const node of inputNodes) {
            const name = $(node).attr('name')
            data[name] = $(`#programmable-modal [name='${name}']`).val()
        }

        return data
    }

    /**
     * Always resolves to true if OK clicked or false if closed or dismissed
     * @param config Object
     * @returns {Promise<boolean>}
     */
    window.modal = function(config = {}) {
        setup(config)
        openModal()
        return new Promise((resolve) => {
            for (const trigger of okTriggers) {
                $(trigger).click(() => {
                    const inputsData = gatherInputsData()
                    closeModal()
                    $(trigger).off('click')
                    resolve({ ok: true, data: inputsData })
                })
            }

            for (const trigger of cancelTriggers) {
                $(trigger).click(() => {
                    closeModal()
                    $(trigger).off('click')
                    resolve({ ok: false, data: {} })
                })
            }
        })
    }

})

/**
 * TableWatcher.js
 */
$(() => {
    const tables = document.querySelectorAll('tbody')
    const callbacks = []

    if (tables.length > 0) {
        const config = { attributes: false, childList: true, subtree: false }
        const callback = (mutationList) => {
            let targetId = ''
            if (mutationList[0].target.parentNode !== null) {
                targetId = mutationList[0].target.parentNode.id
            }

            for (let callback of callbacks) {
                callback(targetId, mutationList)
            }
        }
        const observer = new MutationObserver(callback)

        for (const table of tables) {
            observer.observe(table, config)
        }
    }

    window.registerTableWatcher = function (callback) {
        if (typeof callback === 'function') {
            callbacks.push(callback)
        } else {
            throw new Error('Callback must be a function')
        }
    }
})

/**
 * TableAjaxDelete.js Łatwe usuwanie wiersza z tabelki po Ajaxie
 */
$(() => {

    let modalJs

    if (typeof window.api !== 'function') {
        throw new Error('API is not available')
    }

    if (typeof window.modal !== 'function') {
        console.warn('Modal.js not available, fallback to confim()')
        modalJs = false
    } else {
        modalJs = true
    }

    function register(e) {
        const target = $(e.currentTarget)
        const endpointUrl = target.attr('data-url') || target.parent().attr('data-url')
        const parentTable = target.parent().parent().parent().parent().parent()
        const title = 'Usuwanie wpisu'
        const message = 'Czy na pewno chcesz usunąć ten wpis?'

        function refreshTable() {
            $(parentTable).bootstrapTable('refresh', {
                silent: true
            })
        }

        function makeApiCall() {
            $(target).prop('disabled', true)
            api(endpointUrl, {}, () => {
                refreshTable()
                $(target).prop('disabled', false)
            })
        }

        function displayConfirmModal() {
            if (modalJs) {
                modal({
                    title,
                    message,
                    type: 'danger'
                }).then(data => {
                    if (data.ok) {
                        makeApiCall()
                    }
                })
            } else {
                const ok = confirm(message)
                if (ok) {
                    makeApiCall()
                }
            }

        }

        displayConfirmModal()
    }

    function updateTriggers() {
        $('[data-func=delete-row]').click(register)
    }

    registerTableWatcher(() => {
        updateTriggers()
    })

})

/**
 * TableParametersInjector.js
 * Automatyczne wstrzykiwanie  parametrow z GET do filtrow tabeli
 */
$(() => {
    function getParameters() {
        const rawParams = window.location.search.substring(1).split('&')
        const params = []

        for (const rawParam of rawParams) {
            const split = rawParam.split('=')
            const id = split[0]
            const value = split[1]

            // Fix na to jak nie ma żadnych parametrów
            if (id === '') {
                continue
            }

            params.push({
                id,
                value
            })
        }

        return params
    }

    function injectIfExists(param) {
        $(`#${param.id}`).val(param.value)
    }

    function refreshTables() {
        $('table').bootstrapTable('refresh', {
            silent: true
        })
    }

    const params = getParameters()

    for (const param of params) {
        injectIfExists(param)
    }

    refreshTables()
})