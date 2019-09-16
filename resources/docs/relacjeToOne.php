<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h2>Wstęp</h2>
            <p>Relacje toOne to relacje <code>belongsTo()</code> oraz <code>hasOne()</code>.
                Dobra zajomość relacji to podstawa tworzenia na relacyjnych bazach danych dobrego oprogramowania.
                Jeżeli nie czujesz się pewnie <a
                    href="https://laravel.com/docs/master/eloquent-relationships">poczytaj</a>.</p>
            <p>
                Laravel nie posiada automatycznego mechanimu do selectu relacji przypisujac go do modelu, do używania w
                tabelach, po to aby można było sortować, po kolumnach, aby utrzymać zgodność nazw w tabelach oraz nazw
                zmiennych
                i przy tym nie robić dodatkowych zapytań do bazy danych przy wyświetlaniu informacji w tabeli.
            </p>
            <p>
                Dlatego został stowrzony trait <code>PDAit\Base\Model\Relateable</code>.
            </p>
            <h2>Użycie</h2>
            <p>W modelu względem, którego będziemy robić join'y dodajemy trait <code>PDAit\Base\Model\Relateable</code>
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
            <p>Następnie przy tworzeniu zapytań mamy dostęp do scope'a <code>addJoin(ClassToJoin::class)</code></p>
            <pre>$examples = Example::addJoin(User::class)</pre>
            <p>Jeżeli chcemy dołaczyć relację przypisaną do już przyłączonego modelu innego niż tego wokół którego
                robimy budujemy nasze zapytanie możemy dodać do funkcji <code>addJoin()</code> drugi argument:</p>
            <pre>$examples = Example::addJoin(User::class)
                     ->addJoin(Phone::class, User::class)</pre>
            <h2>Strukura danych pozyskanych danych</h2>
            <img src="1.PNG" style="max-width: 100%">
        </div>
    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
