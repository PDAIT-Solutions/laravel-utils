@if (count($table->getInputs()))
    <form id="{{ $table->getId() }}-form" class="{{ $table->getId() }}-form pda-table-form"
          onsubmit="return filter('{{ $table->getId() }}')">
        <div class="my-3 pda-table-form-container">
            <div class="row pda-table-form-row">
                @foreach ($table->getInputs() as $input)

                    <div class="form-group col pda-table-form-group
                    @if(array_key_exists('type', $input->getAttrs()) &&  $input->getAttrs()['type'] == 'hidden')
                            d-none
                    @endif
                            ">
                        @include('pdait::table/_input')
                        @include('pdait::table/_select')
                    </div>

                @endforeach
            </div>
            <div class="row">
                <div class="col">
                    <div class="pda-table-form-button-container text-left">
                        <button type="button"
                                class="btn btn-secondary pda-table-form-button pda-table-clear-form-button btn-clear-form">{{ __('pdait.clean_form') }}</button>
                    </div>
                </div>
                <div class="col">
                    <div class="pda-table-form-button-container text-right">
                        <button type="submit"
                                class="btn btn-primary pda-table-form-button">{{ __('pdait.filter') }}</button>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" name="table-id" value="{{ $table->getId() }}">
    </form>
@endif