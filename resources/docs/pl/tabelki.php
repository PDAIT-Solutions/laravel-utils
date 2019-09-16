<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Tabelki</h1>
            <p>
                Tabelki otrzymały sporo usprawinień w stosunku do Base-bundle. Korzystają z natywnych funkcjonalności
                dotyczących serializacji <a href="https://laravel.com/docs/eloquent-resources" target="_blank">API
                    Resources</a>, chociaż ich używanie jest opcjonalne i nic nie stoi na przeszkodzie, abyś
                serializował sobie obiekty według swoich preferencji.
            </p>


            <h2>Factory</h2>
            <p>
                Zgodnie z dobrymi praktykami, chociaż nie jest to konieczne, tworzymy sobie nową klasę w <code>app/Factory</code>
                o nazwie <code> App\Factory\ExampleTableFactory</code>
            </p>
            <pre>
namespace App\Factory;

use PDAit\Base\Table\Builder\TableBuilder;
use PDAit\Base\Table\Model\Table;

/**
 * Class ExampleTableFactory
 *
 * @package App\Factory
 */
class ExampleTableFactory
{
    use TableBuilder;

    /**
     * @return Table
     */
    public function create(): Table
    {
        $thead = $this->prepare('example-table', route('example_table-ajax_index');

        $table = $this->getTable();

        return $table;
    }
}</pre>
            <p>Zauważ specyfincze flow tworzenia tabelki. </p>
            <p>
                Funkcja prepare pochodząca z traita <code>PDAit\Base\Table\Builder\TableBuilder</code>
                jako pierwszy argument przyjmuje id tabelki, jako drugi url do pobieranie danych typu <code>json</code>.
            </p>
            <div class="alert alert-info">Jak zwykle zachęcam do pogrzebania w klasach <code>PDAit\Base\Table\Model\Table</code>
                i <code> PDAit\Base\Table\Block\Thead</code>, ponieważ mają kilka przydatnych funkcji.
            </div>
            <h2>Kontroler</h2>
            <p>W kontrolerze tworzymy tabelki zgodnie z wzorcem projektowym, używając funkcji <code>$exampleTableFactory->create()</code>:
            </p>
            <pre>
/**
 * @param  ExampleTableFactory  $factory
 *
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
public function index(ExampleTableFactory $factory)
{
    $table = $factory->create();

    return view(
        'example.index',
        [
            'table' => $table,
        ]
    );
}
</pre>
            <h2>Model</h2>
            <p>Aby umożliwić w modelu sortowanie 'out of the box' dodajemy traita <code>PDAit\Base\Model\Sortable</code>
            </p>
            <pre>
namespace App;

use Illuminate\Database\Eloquent\Model;
use PDAit\Base\Model\Relateable;
use PDAit\Base\Model\Sortable;

class Example extends Model
{
    use Relateable;
    use Sortable;

    protected $fillable = [
        'title',
        'active',
        'user_id',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}</pre>
            <h2>Więcej niż jeden thead</h2>
            <p>
                Możliwe jest ułożenie kilku theadów wraz z <code>colspan</code> oraz <code>rowspan</code>.
                Aby to zrobić tworzymy nowy obiekt <code>PDAit\Base\Table\Block\Thead</code> na tabelce używamy funkcji
                <code>addThead(PDAit\Base\Table\Block\Thead $thead)</code>
            </p>
            <pre>$thead = $this->prepare('example-table', route('example_table-ajax_index'));
$thead->addChild(new Th('Wartość ważna', ['data-field' => 'val', 'rowspan' => 2]));
$thead->addChild(new Th('Grupa wartości', ['colspan' => 2]));

$table = $this->getTable();

$newThead = new Thead();
$newThead->addChild(new Th('Wartość 1', ['data-field' => 'val_1', 'data-sortable' => true]));
$newThead->addChild(new Th('Wartość 2', ['data-field' => 'val_2', 'data-sortable' => true]));

$table->addThead($newThead);</pre>
            <h2>Kolumny</h2>
            <p>Dodawnie kolumn do tabelki jest proste:</p>
            <pre>
use PDAit\Base\Table\Block\Th;

...

$thead->addChild(new Th('Id użytkownika', ['data-field' => 'user_id', 'data-sortable' => true]));
            </pre>
            <p>Konstruktor komórki jest prosty i zrozumiały: </p>
            <pre>__construct(?string $text = '', ?array $attrs = [])</pre>

            <p>Dostępna jest jeszcze bliźniacza klasa <code>PDAit\Base\Table\Block\Td</code> generująca tag
                <code>td</code>.</p>

            <h2>Filtry tabelki</h2>
            <p>Tworzenie filtrów w tabeli jeszcze nigdy nie było tak proste:</p>
            <pre>
use PDAit\Base\Table\Block\Input;
use PDAit\Base\Table\Block\Select;
use App\User; // dodany tylko dla przykładu

$table->addInput(new Input('query', 'example_table.search'));
$table->addInput(new Select('user_id', User::get()->pluck('name', 'id'), 'example_table.user_id')); </pre>
            <p>Konstruktor <code></code>PDAit\Base\Table\Block\Input</p>: </p>
            <pre>__construct(string $name, ?string $label = null, ?array $attrs = [])</pre>
            <p>Konstruktor <code></code>PDAit\Base\Table\Block\Select</code>: </p>
            <pre>__construct(string $name,  $options, ?string $label = null, ?array $attrs = [], bool $nullable = true)</pre>

            <div class="alert alert-info">
                Żeby dowiedzieć się więcej o funkcji <code>pluck</code> odsyłam do <a
                    href="https://laravel.com/docs/collections#method-pluck">dokumentacji</a> Laravela.
            </div>
            <div class="alert alert-info">
                Polecam przejrzenie konstruktorów i funkcji <code>PDAit\Base\Table\Block\Input</code> i <code>PDAit\Base\Table\Block\Select</code>
                ponieważ ciężko wszystko ująć w dokumentacji.
            </div>
            <h2>Wyświetlanie tabelki</h2>
            <p>Po wysłaniu tabelki z Factory do blade'a wystarczy wywołać dyrektywy <code>@table()</code>.</p>
            <p> Przykład użycia:</p>
            <pre>
@extends('base')

@section('content')
    @table($table)
@endsection
            </pre>


            <h2>API Resources</h2>
            <p>
                Jeżeli mamy już naszą tabelę, to nie wyświetla ona nam żadnych danych. Aby to zrobić użyjemy API
                Resources
                Odsyłam do dokumentacji laravela aby dowiedzieć się więcej o <a
                    href="https://laravel.com/docs/eloquent-resources" target="_blank">API
                    Resources</a>.
            </p>
            <p>
                Przykładowe API Resources:
            </p>
            <pre>
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Example extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
                'title'   => $this->title,
                'user_id' => $this->user_id,
                'phones.number'   => $this->number,
        ];
    }
}           </pre>
            <p>
                Po stworzeniu odpowiedniego API Resources, wchodzimy do kontrollera, serwsiu lub czegokolwiek innego po
                to aby na końcu w kontrolerze zwrócić obiekt
                <code>PDAit\Base\Table\Http\Resources\Collection</code> rozszerząjący natywne Collection API Resources o
                magiczne metody używania API Resources dla pojedynczych modeli.
            </p>
            <pre>
namespace App\Http\Controllers\Table;

use App\Example;
use App\Http\Resources\Example as ExampleCollection;
use Illuminate\Routing\Controller as BaseController;
use PDAit\Base\Table\Http\Controller\Paginationable;
use PDAit\Base\Table\Http\Resources\Collection;

/**
 * Class ExampleTableController
 *
 * @package App\Http\Controllers\Table
 */
class ExampleTableController extends BaseController
{

    use Paginationable;

    /**
     * @param Request $request
     *
     * @return Collection
     */
    public function ajaxIndex(Request $request): Collection
    {
        $model = Example::addSort();

        return $this->getCollection($model, ExampleCollection::class);
    }

}</pre>
            <p>
                Aby umożliwić sorotwanie używamy scope'a <code>addSort()</code> To wszystko, aby stworzyć podstawow
                tabelkę.
            </p>
            <h2>Dodatkowe dane w resources</h2>
            <p>
                Aby wyświetlić dodatkowe dane w resources wrócmy jeszcze do przykładu z poprzedniego punktu. Wysyłamy
                tam
                tablicę <code>$data</code>.
                Możemy ją użyć w stworzonym przez nas <code> App\Http\Resources\Example</code>, za pomocą automatycznego
                przekazywania parameterów do konstruktora tworzonych przez nas Resources. Bundle przekazuje
                automatycznie interfejs, jako drugi parameter konstruktora.
                <code>Table/Http/Resources/ResourceData</code>
            </p>
            <pre>namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\View;
use PDAit\Base\Table\Http\Resources\ResourceData;

class Example extends JsonResource
{
    <span style="color: #41e935">private $data;</span>

    public function __construct($resource, ResourceData $data)
    {
        parent::__construct($resource);
        $this-&gt;data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'actions'         =&gt; View::make('example._editBtns', ['id' =&gt; $this-&gt;example_id])-&gt;render(),
            'example_title'   =&gt; $this-&gt;example_title,
            'loopfix'         =&gt; $this-&gt;data-&gt;getLoopFix(),
            'example_user_id' =&gt; $this-&gt;example_user_id,
            'phones.number'   =&gt; $this-&gt;number,
            'tags_count'      =&gt; $this-&gt;tags_count,
        ];
    }
}
</pre>
            <p>Konstruktor musi dziedziczyć po swoim parencie.</p>
            <h2>Loop fix</h2>
            <p>
                Aby pobrać loop fix musimy tak jak w poprzednim przykładzie pobrać musimy pobrać interfejs <code>Table/Http/Resources/ResourceData</code>
                i użyć funkcji getLoopFix()
            </p>
            <pre>namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\View;
use PDAit\Base\Table\Http\Resources\ResourceData;

class Example extends JsonResource
{
    private $data;

    public function __construct($resource, ResourceData $data)
    {
        parent::__construct($resource);
        $this-&gt;data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'actions'         =&gt; View::make('example._editBtns', ['id' =&gt; $this-&gt;example_id])-&gt;render(),
            'example_title'   =&gt; $this-&gt;example_title,
            <span style="color: #41e935">'loopfix'         =&gt; $this-&gt;data-&gt;getLoopFix(),</span>
            'example_user_id' =&gt; $this-&gt;example_user_id,
            'phones.number'   =&gt; $this-&gt;number,
            'tags_count'      =&gt; $this-&gt;tags_count,
        ];
    }
}
</pre>
            <h2>
                Queryable trait
            </h2>
            <p>
                Po to aby ułatwić filtrowanie w tabeli postawł jeszcze jeden trait <code>PDAit\Base\Table\Http\Controller\Queryable</code>.
            </p>
            <p>
                Posiada on metodę do dodawania dowolnych warunków typu where do zapytania.
            </p>
            <p>
                Wyobraź sobie, że masz jeden input w GET z name <code>query</code>. Musi on przefiltrować po kilku
                polach jako LIKE z warunkiem OR na każde pole.
                Normalnie taki kod zająłby nie mniej niż 5 linijek + linijka na każdy warunek. Z traitem załatwiasz
                wszystko w jednej:
            </p>
            <pre>
use PDAit\Base\Table\Http\Controller\Queryable;

...

$model = $this->addWhere(
    $model,
    '%'.Request::input('query').'%',
    ['examples.title', 'phones.number', 'users.name', 'foos'.'bar'],
    'like'
);            </pre>
            <p>Funkcja <code>addWhere()</code> przyjmuje następujące parametry:</p>
            <ol>
                <li>$model - nasz model z bazy danych</li>
                <li>$input - wyszukiwany tekst lub liczba lub DateTime (jeżeli chcesz używać placeholderów dodaj je)
                </li>
                <li>$columns - tablica kolumn do sprawdzenia (akceptuje również sting w przypadku jednej kolumny)</li>
                <li>$check (default: <code>=</code>) - rodzaj porówniania - wszystko to co znajduje się w where w
                    dokumentacji laravela
                </li>
                <li>$columnsType (default: <code>or</code>) - rodzaj połącznia warunków - <code>or</code> lub
                    <code>and</code></li>
            </ol>

        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
