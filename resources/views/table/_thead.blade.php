<thead>
@foreach($table->getTheads() as $thead)

    <tr  @foreach ($thead->getAttrs() as $attr => $value)
        {{ $attr }}="{{ $value }}"
    @endforeach
    >
    @foreach ($thead->getChildren() as  $tcell)
    @include('pdait::table/_tcell')
    @endforeach
    </tr>
@endforeach

</thead>

