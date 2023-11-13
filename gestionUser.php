<?php
session_start();

if( strstr($_SERVER['HTTP_HOST'], '51.178.86.117') ){
    $dbname = 'damien';
    $dblogin = 'damien';
    $dbpassword = 'Cei7Thi&';
} else {
    $dbname = 'blog';
    $dblogin = 'root';
    $dbpassword = '';
}

try
{
    $db = new PDO('mysql:host=localhost;dbname='.$dbname.';charset=utf8', $dblogin, $dbpassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On Ã©met une alerte Ã  chaque fois qu'une
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}


$sql = 'SELECT pseudo, roles FROM users';

$result = $db->query($sql);
$tabUser = $result->fetchAll(PDO::FETCH_ASSOC);

var_dump($tabUser);
?>

<div class="row">
    <div class="col-12">
        <h2>Gestion des utilisateurs</h2>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>pseudos</th>
            <th>roles</th>
        </th>
    </thead>
    <tbody>
        <?php
            foreach($tabUser as $user){
        ?>
        <tr>
            <td><a href="modifUser"><?=$user['pseudo']?></a></td>
            <td><?=$user['roles']?></td>
        </tr>
        <?php
            }
        ?>
    </tbody>
</table>