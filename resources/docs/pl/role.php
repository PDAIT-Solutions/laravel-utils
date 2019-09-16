<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Role użytkowników</h1>
            <p>
                Paczka wychodzi z funkcjami pomoczniczymi do podstawowych rzeczy z rolami użytkowników. Praca z rolami z
                paczki odbywa się na modelu.
            </p>
            <h2>Konfiguracja</h2>
            <h4>Baza danych</h4>
            <p>W bazie danych do tabeli użytkowników dołączamy kolumnę <code>roles</code> - kolumna musi być <code>nullable</code>,
                oraz posiadać <code>type: json</code>.</p>
            <p>W migracji będzie to wyglądać w ten sposób:</p>
            <pre>$table->json('roles')->nullable();</pre>
            <p class="text-danger">#TODO: Dorobić automatyczne migracje.</p>
            <h4>Model</h4>
            <p>Pierwszym krokiem pracy na modelu jest dodanie do modelu użytkowników traita <code>PDAit\Base\Model\Roleable</code>:
            </p>
            <pre>
namespace App;

use PDAit\Base\Model\Roleable;

/**
 * Class User
 *
 * @package App
 */
class User extends Authenticatable
{
    use Roleable;
    ...
}</pre>
            <p>Następnym krokiem jest stworzenie ról oraz ich hierarchi:</p>
            <pre>
namespace App;

use PDAit\Base\Model\Roleable;

/**
 * Class User
 *
 * @package App
 */
class User extends Authenticatable
{
    const ROLES = [
            'ROLE_ADMIN'  => 'Administrator',
            'ROLE_SELLER' => 'Sprzedażowiec',
    ];

     const ROLES_HIERARCHY = [
            'ROLE_ADMIN' => [
                    'ROLE_SELLER',
                    'ROLE_BAR',
            ],

            'ROLE_BAR' => [
                    'ROLE_FOO',
            ],
    ];
    ...
}</pre>
            <div class="alert alert-info">
                Tablice ról hierarchi ról są zawsze jednokrotnie zagnieżdzonymi tablicami. Jeżeli potrzebujesz
                wielokrotnych zagnieżdzeń, wartośći tablic mogą być również kluczami innych tablic.
            </div>
            <h2>Praca z rolami</h2>
            <p>Dzięki dołączonemu traitowi praca z rolami zawsze opiera się na dwóch funkcjacjach:</p>
            <h4>isGranted()</h4>
            <p>
                <code>isGranted(string $role)</code> funkcja sprawdza hierarchi ról. </p>
            <p>W nawiązaniu do przykładu z góry:
                użytkownik w bazie danych ma tylko <code>ROLE_ADMIN</code>, natomiast <code>isGranted('ROLE_FOO')</code>
                zwróci <code>true</code> Ponieważ <code>ROLE_ADMIN</code> ma hierarchię nad <code>ROLE_FOO</code>
            </p>
            <h4>hasRole()</h4>
            <p>
                <code>hasRole(string $role)</code> czy użytkownik ma daną rolę.
            </p>
        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>