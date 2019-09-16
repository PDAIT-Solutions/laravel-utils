<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Forms</h1>
            <p>
                The forms in the package are actually a set of jQuery functions for their efficient handling by Ajax</p>
            <p>

                There are plenty of libraries for rendering HTML. There is no reason to reinvent the wheel. After
                resarch you can figure that
                most people recommend this:
            </p>
            <pre> <a
                    href="https://github.com/netojose/laravel-bootstrap-4-forms?fbclid=IwAR0SZFkM1ZGa8_SEzbrCOtn2XvoFGUpCpaeYf506-GvLK_8rPuGEpT3KOCk"
                    target="_blank">https://github.com/netojose/laravel-bootstrap-4-forms?fbclid=IwAR0SZFkM1ZGa8_SEzbrCOtn2XvoFGUpCpaeYf506-GvLK_8rPuGEpT3KOCk </a></pre>
            <p>
                You can always change or write your own library so it shouldn't be a problem.
            </p>
            <h2>Usage</h2>
            <p>
                The easiest and most practical way to present the whole process is an example.
                Our sample model will be edited in the Bootstrap 4 modal window, but it does not matter - it can be done
                in a separate
                view
            </p>
            <h4>Controller</h4>
            <p>

                As usual, the controller has two functions: loading the modal and processing the form (+ validation).
                Laravel validation is fundamentally different from Symfony and Codeigniter -
                in Laravel we don't validate the model, but the request object that comes to the controller. Of course I recommend
                transfer of validation to a separate class
                (the documentation is very legible).
            </p>
            <p>
                Function responsible for the modal window (one for adding and editing):
            </p>
            <pre>
use App\Example;
use Illuminate\Http\Request;

public function ajaxModalEdit(Request $request)
{
    $example = Example::findOrNew($request->id);

    return view(
            'example._edit',
            [
                    'example' => $example,
            ]
    );
}</pre>
            <p>
                The second action in the controller is form processing:</p>
            <pre>
public function ajaxEdit(Request $request)
{
    $request->validate(
            [
                    'title'   => 'required|max:255',
                    'user_id' => 'required|exists:users,id|unique:examples,user_id,'.$request->input('id', 0),
            ]
    );

    $example = Example::findOrNew($request->id);

    $example->fill($request->all());
    $example->save();

    return JsonResource::make(['status' => 'success']);
}            </pre>
            <p>
                As a bonus I add a removal action:
            </p>
            <pre>
public function delete($l, $id)
{
    $example = Example::findOrFail($id);
    $example->delete();

    return redirect()->route('example_table-index');
}            </pre>
            <h4>Views</h4>
            <p>
                When it comes to controllers, that's all. As for the view <code>example._edit:</code></p>
            <pre>
{{-- resources\views\example\_edit.blade.php --}}

{!! Form::open()-&gt;id('form')-&gt;fill($example)-&gt;locale('example')-&gt;post()-&gt;route('example_table-ajax_edit', ['l' =&gt; app()-&gt;getLocale()])-&gt;attrs(['data-ajax' =&gt; 'true']) !!}
&lt;div class="modal-header"&gt;
    &lt;h5 class="modal-title" id="exampleModalLabel"&gt;  {{ __('example.example') }}&lt;/h5&gt;
    &lt;button type="button" class="close" data-dismiss="modal" aria-label="Close"&gt;
        &lt;span aria-hidden="true"&gt;&times;&lt;/span&gt;
    &lt;/button&gt;
&lt;/div&gt;
&lt;div class="modal-body"&gt;
    {!! Form::hidden('id') !!}
    {!! Form::text('title', 'title') !!}
    {!! Form::select('active', 'active', [true =&gt; __('example.yes'), false =&gt; __('example.no')]) !!}
    {!! Form::select('user_id', 'user_id', \App\User::get()-&gt;pluck('name', 'id')-&gt;toArray()) !!}
&lt;/div&gt;
&lt;div class="modal-footer"&gt;
    &lt;button type="button" class="btn btn-secondary"
            data-dismiss="modal"&gt;  {{ __('app.close_modal') }}&lt;/button&gt;
    &lt;button type="submit" class="btn btn-primary"&gt; {{ __('example.save') }}&lt;/button&gt;
&lt;/div&gt;
{!!Form::close()!!}
</pre>
            <p>
                It remains for us to call the modal
            </p>
            <pre>
{{-- resources\views\example\index.blade.php --}}

@extends('base')

@section('content')
    &lt;div class="text-right mt-4"&gt;
        &lt;button type="button" class="btn btn-primary" data-modal="#exampleModal"  data-ajax-load="{{ route('example_table-ajax_modal_edit', ['l'=&gt; app()-&gt;getLocale()])  }}"&gt;
            {{ __('example.example') }}
        &lt;/button&gt;
    &lt;/div&gt;

    &lt;div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true"&gt;
        &lt;div class="modal-dialog" role="document"&gt;
            &lt;div class="modal-content"&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;hr&gt;
    @table($table)
@endsection</pre>
            <p>Note that calling the modal is not a standard call of the bootstrap modal. It comes from pdait.js</p>
            <h4>
                Changes in the table
            </h4>
            <p>
                To enable editing, we must add a column <code>actions</code> in our <a href="tables.php">factory</a>:
            </p>
            <pre> $thead->addChild(new Th('actions', ['data-field' => 'actions', 'data-class' => 'text-center', 'data-width' => 100]));</pre>
            <p>

                And in API Resources:</p>
            <pre>
public function toArray($request)
{
    return [
            'actions'         => View::make('example._editBtns', ['id'=>$this->example_id])->render(),
            'example_title'   => $this->example_title,
            'example_user_id' => $this->example_user_id,
            'phones.number'   => $this->number,
            'tags_count'      => $this->tags_count,
    ];
}</pre>
            <p>
                And view of buttons for editing (and deleting):
            </p>
            <pre>
{{--resources/views/example/_editBtns.blade.php--}}

{{--USUWANIE--}}
&lt;button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#exampleModalDelete{{$id}}"&gt;
    &lt;i class="far fa-trash-alt"&gt;&lt;/i&gt;
&lt;/button&gt;

&lt;div class="modal fade" id="exampleModalDelete{{$id}}" tabindex="-1" role="dialog" aria-labelledby=exampleModalDelete{{$id}}Label" aria-hidden="true"&gt;
    &lt;div class="modal-dialog" role="document"&gt;
        &lt;div class="modal-content"&gt;
            &lt;div class="modal-header"&gt;
                &lt;h5 class="modal-title" id="exampleModalLabel"&gt;Modal title&lt;/h5&gt;
                &lt;button type="button" class="close" data-dismiss="modal" aria-label="Close"&gt;
                    &lt;span aria-hidden="true"&gt;&times;&lt;/span&gt;
                &lt;/button&gt;
            &lt;/div&gt;
            &lt;div class="modal-body"&gt;
                {{ __('app.are_you_sure') }}
            &lt;/div&gt;
            &lt;div class="modal-footer"&gt;
                &lt;button type="button" class="btn btn-secondary" data-dismiss="modal"&gt;Close&lt;/button&gt;
                &lt;a href="{{ route('example-delete', ['id'=&gt; $id]) }}" class="btn btn-primary"&gt;Save changes&lt;/a&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;

{{--EDYCJA--}}
&lt;button type="button" class="btn btn-sm btn-primary" data-modal="#exampleModal"
        data-ajax-load="{{ route('example_table-ajax_modal_edit', ['id'=&gt; $id])  }}"&gt;
    &lt;i class="far fa-edit"&gt;&lt;/i&gt;
&lt;/button&gt;</pre>
            <p>
                That's all. We haven't written a single js line so far, some html and php.
            </p>
            <h2>Configuration</h2>
            <p>
                The package provides several behavioral patterns in relation to how the application may behave after successful processing of the form (i.e. when in <code>JsonResource</code>
                is located
                <code>'status'=>'success'</code>).
            <h4>
                Redirect URL from Ajax</h4>
            <h4><code>data-redirect-from-ajax="true"</code></h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true', 'data-redirect-from-ajax' => 'true']) !!}</pre>
            <p>
                After adding this attributes to <code>&lt;form&gt;</code>
                success will expect an argument from the responder
                <code>redirect</code>
                with the link to which he has to correct. Example of response in the controller:</p>
            <pre>
/**
 * @param ItemRequest $itemRequest
 *
 * @return JsonResource
 * @throws \Throwable
 */
public function update(ItemRequest $itemRequest)
{
    $item = $itemRequest->save();

    return JsonResource::make(
        [
            'status'   => 'success',
            'redirect' => route(
                'item_show',
                [
                    'id' => $item->id,
                ]
            ),
        ]
    );
}</pre>
            <h4>Redirect read from the form</h4>
            <h4><code>data-success-action="..."</code></h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true', 'data-success-action' => route('item_index')]) !!}</pre>
            <p>After adding this attributes to  <code>&lt;form&gt;</code>
                success will redirect you to the link provided in
                attribute</p>
            <h4>
                Nothing happens</h4>
            <h4><code>data-no-success-action="true"</code></h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true', 'data-no-success-action' => 'true']) !!}</pre>
            <p>
                After using this attribute, nothing will happen when the form is submitted</p>
            <div class="alert alert-info">

                They're still firing <a href="js.php">form events</a>
            </div>
            <h4>
                No arugment</h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true') !!}</pre>
            <p>
                The code that does several things is firing:
            <ol>
                <li>
                    It is checked if there is a global js variable <code>trans</code></li>
                <li>
                    If the variable is declared, the translation value trans ['form_success'] is taken</li>
                <li>
                    If the variable is not declared, the translation value is set to 'Zapis został
                    przetworzony pomyślnie!'
                </li>
                <li>
                    The showAleret function is started (See section <a href="js.php">js</a>)
                    with previously prepared
                    translation
                </li>
                <li>
                    If the form is in the boosttrap modal, the modal is closed</li>
                <li>
                    All bootrstrap tables are reloaded</li>
                <li>
                    If the form has an attribute <code>data-clean-after-success="true"</code>
                    it is cleaned</li>
            </ol>
            </p>
            <div class="alert alert-info">
                If you use translations, the global variable for translations is in our blade file basics
                (eg. <code>base.blade.php</code>) eg.:
                <pre>&lt;script&gt;
  trans = {
    items_imported: "{{__('app.items_imported')}}",
    boxes_imported: "{{__('app.items_imported')}}",
    form_success: "{{__('app.form_success')}}"
  }
&lt;/script&gt;</pre>
            </div>

        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
