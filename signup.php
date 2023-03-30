<?php
if (isset($_SESSION["id"])) {
    session_start();
};
try {
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
} catch (Exception $e) {
    echo "Erreur de connexion a la BDD : " . $e->getMessage();
}

if (isset($_POST["signupusername"]) and isset($_POST["signuppassword"])) {
    $stmt = $db->prepare("SELECT utilisateurs.pseudo FROM utilisateurs WHERE utilisateurs.pseudo = :username");
    $stmt->execute(["username" => $_POST["signupusername"]]);
    $check = $stmt->fetchAll();
    if (count($check) == 0) {
        signupUser();
    } else {
        echo "Pseudo deja utilisé ! Etes vous deja inscrits ?";
    };
};

function signupUser()
{
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
    $stmt = $db->prepare("SELECT utilisateurs.id FROM utilisateurs ORDER BY utilisateurs.id DESC LIMIT 1");
    $stmt->execute();
    $lastID = $stmt->fetchAll();
    $lastID[0]["id"] += 1;
    echo $lastID[0]["id"];


    $stmt = $db->prepare("INSERT INTO utilisateurs (id,pseudo,mot_de_passe,date_inscription) VALUES (:id,:pseudo,:passw,:ts)");
    $stmt->execute([
        "id" => $lastID[0]["id"],
        "pseudo" => $_POST["signupusername"],
        "passw" => md5($_POST["signuppassword"]),
        "ts" => date("Y-m-d h:i:s")
    ]);
};
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">



    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instameme</title>
</head>

<body>
    <header>
        <div id="navbar-left">
            <a href="index.php"><img src="img/logo_dark.png" id="logo"></a>
            <a href="index.php" home"> Accueil </a>
        </div>
        <div id="center">
            <form id="search" method="post">
                <input id="searchbar" type="text" placeholder="Rechercher" name="searchresult">
                <button id="searchbutton">🔍</button>
            </form>
        </div>
        <div id="navbar-right">
            <?php if (!isset($_SESSION["sid"])) { ?>
                <a href="signup.php" id="post"> Inscription </a>
                <a href="signin.php" id="profile"> Connexion </a>
            <?php } else { ?>
                <a href="post.php" id="post"> Créer </a>
                <a href="user.php?userid=<?= $_SESSION["id"] ?>" id="profile"> Profil </a>
                <a href="signout.php" id="logout"> Déconnexion </a>
            <?php } ?>
        </div>
    </header>


    <div id="content">
        <form id="logform" method="post">
            <span> Inscription </span>
            <div id="form-top">
                <p> Nom : </p>
                <input type="text" id="logusername" name="signupusername">
                <p> Mot de passe : </p>
                <input type="password" id="logpassword" name="signuppassword">
            </div>
            <button id="upload">Inscription</button>
        </form>
    </div>
</body>
<script src="main.js"></script>

</html>