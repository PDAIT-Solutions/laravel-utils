<div id="{{ $table->getId() }}-container">

    @include('pdait::table/_form')

    <table
            @include('pdait::table/_tableAttrs')
    >
        @if ($table->getTheads()->count())

            @include('pdait::table/_thead')

        @endif

    </table>
</div>
