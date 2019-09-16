@if($input::TAG == 'select')
    @if($input->getLabel())
        <label for="{{ $input->getName() }}"
        @foreach ($input->getLabelAttrs() as $attr => $value)
            {{ $attr }}="{{ $value }}"
        @endforeach
        >{{ __($input->getLabel()) }}</label>
    @endif


    <select
    {!! !array_key_exists('class', $input->getAttrs()) ? 'class="form-control pda-table-form-input pda-table-form-select"' : ''  !!}
    {!! !array_key_exists('name', $input->getAttrs()) ? 'name="'.$input->getName().'"' :'' !!}
    {!! !array_key_exists('id', $input->getAttrs()) ? 'id="'.$input->getName().'"' :'' !!}
    {!! !array_key_exists('data-cookie-id', $input->getAttrs()) ? 'data-cookie-id="'.$table->getId().'-'.$input->getName().'-input"' :'' !!}
    @foreach ($input->getAttrs() as $attr => $value)
        {{ $attr }}="{{ $value }}"
    @endforeach
    >

    @include('pdait::table/_options')
    </select>
@endif

