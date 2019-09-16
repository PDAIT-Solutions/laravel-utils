<{{ $tcell::TAG }}
@foreach ($tcell->getAttrs() as $attr => $value)
    {{ $attr }}="{{ $value }}"
@endforeach
>
{!!  __($tcell->getText()) !!}
</{{ $tcell::TAG }}>