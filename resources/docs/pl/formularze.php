<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Formularze</h1>
            <p>Formularze w paczce są tak naprawdę zbiorem funkcji jQuery do ich sprawnej obsługi przez ajax'a</p>
            <p>
                Do rendorowania HTML'ów jest mnóstwo bibliotek. Nie ma co wymyślać koła od nowa. Pogrzebałem trochę i
                najwięcej ludzi poleca to:
            </p>
            <pre> <a
                    href="https://github.com/netojose/laravel-bootstrap-4-forms?fbclid=IwAR0SZFkM1ZGa8_SEzbrCOtn2XvoFGUpCpaeYf506-GvLK_8rPuGEpT3KOCk"
                    target="_blank">https://github.com/netojose/laravel-bootstrap-4-forms?fbclid=IwAR0SZFkM1ZGa8_SEzbrCOtn2XvoFGUpCpaeYf506-GvLK_8rPuGEpT3KOCk </a></pre>
            <p>
                Bibliotekę zawsze można zmienić, lub dopisać swoją więc nie powinno to być problemem.
            </p>
            <h2>Użytkownie</h2>
            <p>
                Najprościej i najprakyczniej cały proces przedstawić na przykładzie (potem będzie można sobie kopiować z
                dokumentacji zamiast szukać po plikach).
                Nasz przykładowy model będzie edytowany w okienku modalnym ale nie ma to znaczenia - może być w osobnym
                widoku
            </p>
            <h4>Kontroler</h4>
            <p>
                Kontroler to jak zwykle dwie funkcje: ładowanie modala i przetwarzanie formualrza (+ validacja).
                Validacja w laravelu zasadniczo różni się od od Smyfony i Codeignitera -
                w Laravelu nie validujemy modelu, a obiekt requesta który przychodzi do kontrolera. Oczywiście zalecam
                przeniesienie validacji do osobnej klasy
                (w dokumentacji jest bardzo czytelny przkyład).
            </p>
            <p>
                Funkcja odpowiedzialna za okienko modalne (jedna dla dodawania i edycji):
            </p>
            <pre>
use App\Example;
use Illuminate\Http\Request;

public function ajaxModalEdit(Request $request)
{
    $example = Example::findOrNew($request->input('id', 0));

    return view(
            'example._edit',
            [
                    'example' => $example->toArray(),
            ]
    );
}</pre>
            <p>Drugą akcją w kontrolerze jest przetwarzanie forumlarza:</p>
            <pre>
public function ajaxEdit(Request $request)
{
    $request->validate(
            [
                    'title'   => 'required|max:255',
                    'user_id' => 'required|exists:users,id|unique:examples,user_id,'.$request->input('id', 0),
            ]
    );

    $example = Example::findOrNew($request->input('id', 0));

    $example->fill($request->all());
    $example->save();

    return JsonResource::make(['status' => 'success']);
}            </pre>
            <p>
                Jako bonus dodaje akcję do usuwania:
            </p>
            <pre>
public function delete($l, $id)
{
    $example = Example::findOrFail($id);
    $example->delete();

    return redirect()->route('example_table-index', ['l' => $l]);
}            </pre>
            <h4>Widoki</h4>
            <p>Jeżeli chodzi o kontrollery to wszystko. Jeżeli chodzi o widok <code>example._edit:</code></p>
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
            <p>Pozostaje nam kwestia wywołania modala"</p>
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
            <p>Zwróć uwagę, że wywołanie modala nie jest standarowdym wywołaniem modala bootstrapa.</p>
            <h4>Zmiany w tabeli</h4>
            <p>Aby umożliwić edycję musimy dodać kolumnę <code>actions</code> w naszym <a href="tabelki.php">factory</a>:
            </p>
            <pre> $thead->addChild(new Th('actions', ['data-field' => 'actions', 'data-class' => 'text-center', 'data-width' => 100]));</pre>
            <p>Oraz w API Resources:</p>
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
            <p>I widok buttonów do edycji (oraz usuwania):</p>
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
                Czy na pewno chcesz usunąć?
            &lt;/div&gt;
            &lt;div class="modal-footer"&gt;
                &lt;button type="button" class="btn btn-secondary" data-dismiss="modal"&gt;Close&lt;/button&gt;
                &lt;a href="{{ route('example-delete', ['l' =&gt; app()-&gt;getLocale(), 'id'=&gt; $id]) }}" class="btn btn-primary"&gt;Save changes&lt;/a&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;

{{--EDYCJA--}}
&lt;button type="button" class="btn btn-sm btn-primary" data-modal="#exampleModal"
        data-ajax-load="{{ route('example_table-ajax_modal_edit', ['l'=&gt; app()-&gt;getLocale(), 'id'=&gt; $id])  }}"&gt;
    &lt;i class="far fa-edit"&gt;&lt;/i&gt;
&lt;/button&gt;</pre>
            <p>To wszystko. Jak do tej pory nie napisaliśmy ani jednej linijki js'a, trochę htmla i php. </p>
            <h2>Konfiguracja</h2>
            <p>Bundle udostępnia kilka wzorców zachowań w stosunku do tego jak może zachowywać się aplikacji po udanym
                przetworzeniu formularza (czyli takiego, kótry w <code>JsonResource</code> znajduje sie
                <code>'status'=>'success'</code>).
            <h4>Redirect przychodzący z ajaxa</h4>
            <h4><code>data-redirect-from-ajax="true"</code></h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true', 'data-redirect-from-ajax' => 'true']) !!}</pre>
            <p>Po dodaniu tego atrybuty do <code>&lt;form&gt;</code> sukces będzie oczekiwał od responsa argumentu
                <code>redirect</code> z linkiem do którego ma poprawdzić. Przykład odpowiedzi w kontrolerze:</p>
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
            <h4>Redirect odczytywany z formularza</h4>
            <h4><code>data-success-action="..."</code></h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true', 'data-success-action' => route('item_index')]) !!}</pre>
            <p>Po dodaniu tego atrybuty do <code>&lt;form&gt;</code> sukces będzie przekierowywał do linku podanego w
                atrybucie</p>
            <h4>Nic się nie dzieje</h4>
            <h4><code>data-no-success-action="true"</code></h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true', 'data-no-success-action' => 'true']) !!}</pre>
            <p>Po użyciu tego atrybutu nic się nie stanie przy wysłaniu formularza</p>
            <div class="alert alert-info">
                W dalszym ciągu odpalane są <a href="js.php">eventy formularzy </a>
            </div>
            <h4>Brak arugmentu</h4>
            <pre> {!! Form::open()->id('form')->fill($brand)->post()->route('brand_post_edit')->attrs(['data-ajax' => 'true') !!}</pre>
            <p>Odpalny jest kod robiący kilka rzeczy:
            <ol>
                <li>Sprawdzane jest czy istenieje globalna zmienna js'a <code>trans</code></li>
                <li>Jeżeli zmienna jest zadeklarowana, pobierana jest wartość tłumaczenia trans['form_success']</li>
                <li>Jeżeli zmienna jest nie jest zadeklarowana, wartość tłumaczenia ustawiana jest na 'Zapis został
                    przetworzony pomyślnie!'
                </li>
                <li>Odapalana jest funkcja showAleret (Zobacz sekcję <a href="js.php">js</a>) z wcześniej przygotowanym
                    tłumaczeniem
                </li>
                <li>Jeżeli formularz znajduje się w modalu boosttrapowym modal jest zamykany</li>
                <li>Wszystkie bootrstrapowe tabeli są przeładowywane</li>
                <li>Jeżeli formularz ma attrybut <code>data-clean-after-success="true"</code> jest on czyszczony</li>
            </ol>
            </p>
            <div class="alert alert-info">
                Jeżeli korzystasz z tłumaczeń zmienną globalną do tłumaczeń towrzymy w naszym podstawowywm pliku blade
                (np. <code>base.blade.php</code>) np.:
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
