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

if (count($_POST) != 0) {

    $stmt = $db->prepare("SELECT contenus.id FROM contenus ORDER BY contenus.id DESC LIMIT 1");
    $stmt->execute();
    $lastID = $stmt->fetchAll();
    $lastID[0]["id"] += 1;
    echo $lastID[0]["id"];

    var_dump($_FILES["file"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], "memes/" . $_FILES["file"]["name"]);
    var_dump($_FILES["file"]["error"]);

    $stmt = $db->prepare("INSERT INTO contenus (id,id_utilisateur,description,chemin_image,date_publication) VALUES (:id,:idu,:descr,:path,:ts)");
    $stmt->execute([
        "id" => $lastID[0]["id"],
        "idu" => $_SESSION["id"],
        "descr" => $_POST["descr"],
        "path" => $_FILES["file"]["name"],
        "ts" => date("Y-m-d h:i:s")
    ]);

    $stmt = $db->prepare("INSERT INTO likes (id_utilisateur,id_contenu) VALUES (:idu,:idc)");
    $stmt->execute([
        "idu" => $_SESSION["id"],
        "idc" => $lastID[0]["id"]
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
        <form id="new-post" enctype="multipart/form-data" method="post">
            <div id="post-top">
                <p> Meme : </p>
                <input type="file" id="file" name="file">
                <p> Description : </p>
                <input type="text" id="desc" name="descr">
            </div>
            <button id="upload">Poster</button>
        </form>
    </div>

</body>

</html>