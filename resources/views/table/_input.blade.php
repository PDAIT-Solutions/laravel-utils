@if($input::TAG == 'input')
    @if($input->getLabel())
        <label for="{{ $input->getName() }}"

        @foreach ($input->getLabelAttrs() as $attr => $value)
            {{ $attr }}="{{ $value }}"
        @endforeach
        >{{ __($input->getLabel()) }}</label>
    @endif
    <input


    {!! !array_key_exists('type', $input->getAttrs()) ? 'type="text"' : ''  !!}
    {!! !array_key_exists('class', $input->getAttrs()) ? 'class="form-control pda-table-form-input"' : ''  !!}
    {!! !array_key_exists('name', $input->getAttrs()) ? 'name="'.$input->getName().'"' :'' !!}
    {!! !array_key_exists('id', $input->getAttrs()) ? 'id="'.$table->getId().'-'.$input->getName().'"' :'' !!}
    {!! !array_key_exists('data-cookie-id', $input->getAttrs()) ? 'data-cookie-id="'.$table->getId().'-'.$input->getName().'-input"' :'' !!}
    @foreach ($input->getAttrs() as $attr => $value)
        {{ $attr }}="{{ $value }}"
    @endforeach
    value = "{{ array_key_exists($table->getId().'-'.$input->getName().'-input', $_COOKIE) ? $_COOKIE[$table->getId().'-'.$input->getName().'-input'] : ''}}"

    >
@endif
