<?php
if (isset($_SESSION["id"])) {
    session_start();
};
require_once("requests.php");

$x = $_GET["memeid"];
$memecontent = getMeme($x);

$memecomments = getComments($x);
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
                <input type="hidden" name="memeid" value="<?= $_GET["memeid"]; ?>">
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
        <article class="meme-big">
            <div class="meme-big-left">
                <a href="meme.php?memeid=<?= $memecontent[0]["idm"] ?>"><img class="meme" src="memes/<?= $memecontent[0]["chemin_image"] ?>"></a>
            </div>
            <div class="meme-big-right">
                <span class="username"><a href="user.php?userid=<?= $memecontent[0]["idu"] ?>"><?= $memecontent[0]["pseudo"] ?></a></span>
                <p class="description"><?= $memecontent[0]["description"] ?></p>
                <div class="actions">
                    <span class="like<?php if (!canLike($memecontent[0]["idm"])) { ?>d<?php } ?>"><a disabled <?php if (canLike($memecontent[0]["idm"])) { ?>href="like.php?likeid=<?= $memecontent[0]["idm"] ?>" <?php } ?>>Aimer</a></span>
                    <span class="repost"><a <?php if (isset($_SESSION["id"])) { ?> href="repost.php?memeid=<?= $memecontent[0]["idm"] ?>" <?php } ?>>Reposter</a></span>
                </div>
                <p class="likes">Aimé par <?= $memecontent[0]["likes"] ?> personnes</p>
                <div class="comments">
                    <span>Commentaires</span>
                    <ul>
                        <?php foreach ($memecomments as $comment) { ?>
                            <?php if ($comment["idc"] == $memecontent[0]["idm"]) { ?>
                                <li><?= $comment["pseudo"] ?> : <?= $comment["message"] ?></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </article>


    </div>
</body>

</html>