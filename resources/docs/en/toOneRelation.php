<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h2>Admission</h2>
            <p>
                ToOne relationships are <code> belongsTo() </code> and <code> hasOne() </code> relationships.
                Good relationship knowleadge is the basis for creating good software on relational databases.
                If you don't feel confident in relations <a
                    href="https://laravel.com/docs/master/eloquent-relationships">read</a>.</p>
            <p>

                Laravel does not have an out of the box mechanism for selecting relations, assigning it to a model for use in
                Resources
                tables so that they can be sorted, by columns to maintain the match of table names and names
                variables
                and at the same time do not make additional queries to the database when displaying information in the table.
            </p>
            <p>

                That's why  was created trait <code>PDAit\Base\Model\Relateable</code>.
            </p>
            <h2>
                Use
            </h2>
            <p>
                In the model with respect to which we will join, we add trait <code>PDAit\Base\Model\Relateable</code>
            </p>
            <pre>namespace App;

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
}
</pre>
            <p>Then, when creating queries, we have access to the scope <code>addJoin(ClassToJoin::class)</code></p>
            <pre>$examples = Example::addJoin(User::class)</pre>
            <p>

                If we want to attach a relation assigned to an already attached model other than the one around which
                we do we build our query we can add second argument to the function <code>addJoin()</code>:</p>
            <pre>$examples = Example::addJoin(User::class)
                     ->addJoin(Phone::class, User::class)</pre>
            <h2>Data structure of acquired data</h2>
            <img src="1.PNG" style="max-width: 100%">
        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
