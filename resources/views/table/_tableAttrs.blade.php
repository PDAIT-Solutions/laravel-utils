id="{{ $table->getId() }}"
data-url="{{ $table->getUrl() }}"

@if(!(array_key_exists('data-locale', $table->getAttrs())))
    data-locale="{{ strlen(app()->getLocale()) > 2  ?  str_replace('_', '-', app()->getLocale()) :  ( app()->getLocale() . '-' . strtoupper( app()->getLocale()))}}"
@endif

@if(!(array_key_exists('data-toggle', $table->getAttrs())))
    data-toggle="table"
@endif

@if(!(array_key_exists('data-data-field', $table->getAttrs())))
    data-data-field="data"
@endif

@if(!(array_key_exists('data-pagination', $table->getAttrs())))
    data-pagination="true"
@endif

@if(!(array_key_exists('data-side-pagination', $table->getAttrs())))
    data-side-pagination="server"
@endif

@if(!(array_key_exists('data-query-params', $table->getAttrs())))
    data-query-params="params"
@endif

@if(!(array_key_exists('data-row-style', $table->getAttrs())))
    data-row-style="rowStyle"
@endif

@if(!(array_key_exists('data-sort-order', $table->getAttrs())))
    data-sort-order="desc"
@endif

@if(!(array_key_exists('data-sort-name', $table->getAttrs())))
    data-sort-name="id"
@endif

@if(!(array_key_exists('data-page-size', $table->getAttrs())))
    data-page-size="25"
@endif

@if(!(array_key_exists('data-page-list', $table->getAttrs())))
    data-page-list="[10, 25, 50, 100]"
@endif

@foreach ($table->getAttrs() as $attr => $value)
    {{ $attr }}="{{ $value }}"
@endforeach


