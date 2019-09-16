<html lang="en">
<?php include('head.php') ?>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-3 my-5">
            <?php include('sidebar.php') ?>
        </div>
        <div class="col-lg-9 my-5">
            <h1>Users role</h1>
            <p>
                The package comes with auxiliary functions for basic things with user roles. Work with roles from
                packages take place on the model.
            </p>
            <h2>Configuration</h2>
            <h4>Database</h4>
            <p>
                In the database, we attach the <code> roles </code> column to the users table - the column must be
                <code> nullable </code>,
                and have <code> type: json </code>.
            </p>
            <p>
                In migration it will look like this:
            </p>
            <pre>$table->json('roles')->nullable();</pre>
            <p class="text-danger">#TODO: Make additional automatic migrations.</p>
            <h4>Model</h4>
            <p>
                The first step of working on the model is to add trait users to the model <code>PDAit\Base\Model\Roleable</code>:
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
            <p>
                The next step is to create roles and their hierarchy:
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
                Role arrays of role hierarchies are always once nested arrays. If you need
                multiple nests, array values ​​can also be the keys of other arrays.
            </div>
            <h2>Working with roles</h2>
            <p>Thanks to the attached trait, work with roles is always based on two functions:</p>
            <h4>isGranted()</h4>
            <p>
                <code>isGranted(string $role)</code> the function checks the role hierarchy. </p>
            <p>
                With reference to the example from above:
                the user in the database only has <code>ROLE_ADMIN</code>, while <code>isGranted('ROLE_FOO')</code>
                returns <code>true</code> Because <code>ROLE_ADMIN</code> has a hierarchy over <code>ROLE_FOO</code>
            </p>
            <h4>hasRole()</h4>
            <p>
                <code>hasRole(string $role)</code> whether the user has the role not hierarchy.
            </p>
        </div>

    </div>
</div>
<?php include('footer.php') ?>
</body>
</html>
