@if($input->getNullable())
    <option value=""></option>
@endif
@foreach ($input->getOptions() as $key => $option)

    <option value="{{ $key }}"

            @if (array_key_exists($table->getId().'-'.$input->getName().'-input', $_COOKIE) && $_COOKIE[$table->getId().'-'.$input->getName().'-input'] !== '' &&
            $_COOKIE[$table->getId().'-'.$input->getName().'-input'] == $key )
            selected
            @endif

            @if (
            array_key_exists(str_replace('[]', '',$table->getId().'-'.$input->getName()), $_COOKIE) &&
            is_iterable($_COOKIE[str_replace('[]', '',$table->getId().'-'.$input->getName())]) &&
            array_key_exists('multiple', $input->getAttrs()) &&
            in_array( $key,  explode(',',$_COOKIE[str_replace('[]', '',$table->getId().'-'.$input->getName())][0]))
            )

            selected
            @endif

    >
        {{ $option }}
    </option>
@endforeach


