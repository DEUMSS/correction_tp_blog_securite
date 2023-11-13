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
?>
<html lang="fr">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>


<body>
<header>
    <div class="row">
        <div class="col-12">
            <nav class="nav justify-content-end">
                <a class="nav-link" href="login.php">Se connecter</a>
                <a class="nav-link" href="account.php">M'inscrire</a>
            </nav>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1>Mon super blog ! S'inscrire</h1>
        </div>
    </div>
</header>


<?php
// Vérifier si le login existe déjà
$login = htmlspecialchars( $_POST['login'] );
$password = htmlspecialchars( $_POST['password'] );
$passwordConfirm = htmlspecialchars( $_POST['passwordConfirm'] );

$req = $db->prepare( 'select * from users where pseudo =:pseudo' );
$req->execute( [':pseudo'=>$login] );
if( $req->rowCount() ) {
    header( 'Location: createUserStep1.php?pseudo=1' );
    exit();
}

// Vérifier le mot de passe et la confirm
if( strlen( $password ) < 8 ) {
    header( 'Location: createUserStep1.php?invalidpass=1' );
    exit();
}
if( $password != $passwordConfirm ) {
    header( 'Location: createUserStep1.php?invalidconfirm=1' );
    exit();
} 


$passHash = sodium_crypto_pwhash_str(
    $password,
    SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
    SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
);

$req = $db->prepare( 
    "INSERT INTO users( pseudo, password ) VALUE( :pseudo, :password )"
 );
$isInsertOk = $req->execute([
    $_SESSION['id'] => $idUser,
    $_SESSION['login'] => $login
 ]);
 if( !$isInsertOk ) {
    echo "Erreur lors de l'enregistrement";
    die;
 } else {
    $idUser = $db->lastInsertId();
    $_SESSION['id'] -> $idUser;
    $_SESSION['login'] -> $login;
 }


?>


<section class="container mt-5">
    <div class="row">
        <div class="col-12">
            <form name="accesform" method="post" action="validUser.php" enctype="multipart/form-data">

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="photo" name="photo">
                        <label class="custom-file-label" for="photo">Choisissez votre photo de profil</label>
                    </div>
                </div>
                <div class="mb-3 row justify-content-end">
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary mb-3">Valider</button>
                        <a href="index.php" class="btn btn-secondary mb-3"> Ignorer </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</section>



<footer class="container">

</footer>


</body>