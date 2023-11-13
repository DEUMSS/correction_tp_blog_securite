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



if( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {
    $login = $_POST['login'];
    try {
        $db = new PDO('mysql:host=localhost;dbname='.$dbname.';charset=utf8', $dblogin, $dbpassword);
    } catch( Exception $e) {
        die( 'Erreur : ' . $e->getMessage() );
    }
    $sql = 'SELECT * FROM users WHERE login=:login';
    $reponse = $db->prepare( $sql );
    $reponse->execute( [':login'=>$login] );

    if( $acces = $reponse->fetch(PDO::FETCH_ASSOC) ) {
        if(/*sodium_crypto_pwhash_str_verify(*/$acces['password'] == $_POST['password'] )/*)*/ {
            $_SESSION['login'] = $login;
            $_SESSION['roles'] = $acces['roles'];
            header('Location:index.php?error=0');
            die;
        } else {
            header('Location:login.php?error=1&passerror=1&login='.$login);
            die;
        }
    } else {
        header('Location:login.php?error=1&loginerror=1');
    }
}



