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
