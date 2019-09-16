<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Tables</h1>
            <p>

                The tables use native functionalities
                of Laravel serialization <a href="https://laravel.com/docs/eloquent-resources" target="_blank">API
                    Resources</a>,
                although their use is optional and nothing prevents you from seralizing objects by your own functions.
            </p>


            <h2>Factory</h2>
            <p>

                In accordance with good practices, although not necessary, we are creating a new class in <code>app/Factory</code>

                named <code> App\Factory\ExampleTableFactory</code>
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
            <p>
                Note the specific flow of the table creation. </p>
            <p>

                The <code>prepare</code> function from trait <code>PDAit\Base\Table\Builder\TableBuilder</code>

                as the first argument it takes the table id, as the second url to retrieve the data type <code>json</code>.
            </p>
            <div class="alert alert-info">
                As usual, I encourage you to checkout body of class  <code>PDAit\Base\Table\Model\Table</code>
                and <code> PDAit\Base\Table\Block\Thead</code>,
                because they have some useful functions.
            </div>
            <h2>Controller</h2>
            <p>In the controller, we create tables according to the design pattern, using the function <code>$exampleTableFactory->create()</code>:
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
            <p>
                To enable 'out of the box' sorting in the model, we add a trait <code>PDAit\Base\Model\Sortable</code>
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
            <h2>
                More than one thead</h2>
            <p>

                It is possible to arrange several theads together with  <code>colspan</code> and <code>rowspan</code>.
                To do this, we create a new object  <code>PDAit\Base\Table\Block\Thead</code> we use the function on the table
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
            <h2>Columns</h2>
            <p>
                Adding columns to the table is simple:
            </p>
            <pre>
use PDAit\Base\Table\Block\Th;

...

$thead->addChild(new Th('Id użytkownika', ['data-field' => 'user_id', 'data-sortable' => true]));
            </pre>
            <p>
                The cell constructor is simple and understandable:
            </p>
            <pre>__construct(?string $text = '', ?array $attrs = [])</pre>

            <p>A twin class is  available<code>PDAit\Base\Table\Block\Td</code>
                generating the tag
                <code>td</code>.</p>

            <h2>Filtry tabelki</h2>
            <p>
                Creating filters in a table has never been easier:</p>
            <pre>
use PDAit\Base\Table\Block\Input;
use PDAit\Base\Table\Block\Select;
use App\User; // added only for an example

$table->addInput(new Input('query', 'example_table.search'));
$table->addInput(new Select('user_id', User::get()->pluck('name', 'id'), 'example_table.user_id')); </pre>
            <p>Constructor <code></code>PDAit\Base\Table\Block\Input</p>: </p>
            <pre>__construct(string $name, ?string $label = null, ?array $attrs = [])</pre>
            <p>Constructor <code></code>PDAit\Base\Table\Block\Select</code>: </p>
            <pre>__construct(string $name,  $options, ?string $label = null, ?array $attrs = [], bool $nullable = true)</pre>

            <div class="alert alert-info">

                To learn more about the function  <code>pluck</code> here a link to  Laravel <a
                    href="https://laravel.com/docs/collections#method-pluck">documentation</a>.
            </div>
            <div class="alert alert-info">
                I recommend looking at constructors and functions <code>PDAit\Base\Table\Block\Input</code> and <code>PDAit\Base\Table\Block\Select</code>

                because it's hard to put everything in the documentation.
            </div>
            <h2>
                Displaying the table</h2>
            <p>
                After sending the table from Factory to blade it is enough to call the directives <code>@table()</code>.</p>
            <p> Example of use:</p>
            <pre>
@extends('base')

@section('content')
    @table($table)
@endsection
            </pre>


            <h2>API Resources</h2>
            <p>

                Poznaj wymowę
                If we already have our table, it does not show us any data. To do this we will use the API
                Resources
                I refer to the laravel documentation to learn more about <a
                    href="https://laravel.com/docs/eloquent-resources" target="_blank">API
                    Resources</a>.
            </p>
            <p>

                Sample API Resources:
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

                After creating the appropriate API Resources, we enter the controller, service or anything else after
                to finally return the object   <code>PDAit\Base\Table\Http\Resources\Collection</code> in the controller

                extending native Collection API Resources by
                magic methods of using API Resources for individual models.
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

                To enable sorting, we use scope  <code>addSort()</code>
                That's all to create a basic  table.
            </p>
            <h2>Additional data in resources
            </h2>
            <p>
                To get additional data in resources, let's return to the example from the previous point. Let's send aditional parameter <code>$data</code>.

                We can use it in the one created <code> App\Http\Resources\Example</code>,
                using automatic
                passing parameters to the constructor of the Resources we create. Bundle passes
                automatically interface   <code>Table/Http/Resources/ResourceData</code> as the second parameter of the constructor.

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
            <p>
                The constructor must inherit from his parent.</p>
            <h2>Loop fix</h2>
            <p>
                To get the loop fix we have to download the interface as in the previous example <code>Table/Http/Resources/ResourceData</code>

                and use the function getLoopFix()
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

                To make filtering easier, he put another trait in the table <code>PDAit\Base\Table\Http\Controller\Queryable</code>.
            </p>
            <p>

                It has a method to add any where conditions to the query.
            </p>
            <p>

                Imagine you have one input in GET with name <code>query</code>.
                It must filter after a few
                fields as LIKE with an OR condition for each field.
                Normally this code would take no less than 5 lines + a line for each condition. You deal with trait
                all in one:
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
            <p>Function <code>addWhere()</code> takes the following arguments:</p>
            <ol>
                <li>$model -  our database model</li>
                <li>$input -
                    search text or number or DateTime (if you want to use DB placeholders, add them)
                </li>
                <li>$columns -
                    array of columns to check (also accepts string for one column)</li>
                <li>$check (default: <code>=</code>) -
                    type of comparison - all that is in where in
                    Laravel documentation
                </li>
                <li>$columnsType (default: <code>or</code>) -
                    type of combination of conditions - <code>or</code> lub
                    <code>and</code></li>
            </ol>

        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
