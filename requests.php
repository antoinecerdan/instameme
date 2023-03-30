<?php
if (isset($_SESSION["id"])) {
    session_start();
};


#use \loilo\fuse;

#require_once 'vendor/autoload.php';
try {
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
} catch (Exception $e) {
    echo "Erreur de connexion a la BDD : " . $e->getMessage();
}


$stmt = $db->prepare("SELECT utilisateurs.pseudo, utilisateurs.id AS idu FROM utilisateurs");
$stmt->execute();
$users = $stmt->fetchAll();


#$fuse = new Fuse($users, $options);

if (isset($_POST["searchresult"])) {
    foreach ($users as $user) {
        if ($user["pseudo"] == $_POST["searchresult"]) {
            $params = $user["idu"];
            header("Location: user.php?userid=$params");
        };
    };
};


function getMeme($ID)
{
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
    $stmt = $db->prepare("SELECT
    description,
    chemin_image,
    count(likes.id_contenu) as likes,
    utilisateurs.pseudo,
    utilisateurs.id AS idu,
    contenus.id AS idm
FROM
    contenus
    INNER JOIN likes ON likes.id_contenu = contenus.id
    INNER JOIN utilisateurs ON utilisateurs.id = contenus.id_utilisateur
WHERE
	contenus.id = :memeNB
GROUP BY
    contenus.id");
    $stmt->execute([
        "memeNB" => $ID
    ]);
    $result = $stmt->fetchAll();
    return $result;
};

function getComments($ID)
{
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
    $stmt = $db->prepare("SELECT
    commentaires.id_contenu AS idc,
    utilisateurs.pseudo,
    commentaires.message
FROM
    commentaires
    INNER JOIN utilisateurs ON utilisateurs.id = commentaires.id_utilisateur
WHERE
    commentaires.id_contenu = :memeNB
ORDER BY
    commentaires.id_contenu");
    $stmt->execute([
        "memeNB" => $ID
    ]);
    $result = $stmt->fetchAll();
    return $result;
};



function getUser($ID)
{
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
    $stmt = $db->prepare("SELECT
    chemin_image,
    utilisateurs.id,
    contenus.id AS idm
FROM
    contenus
    INNER JOIN utilisateurs ON utilisateurs.id = contenus.id_utilisateur
WHERE
	utilisateurs.id =:userNB

");
    $stmt->execute([
        "userNB" => $ID
    ]);
    $result = $stmt->fetchAll();
    return $result;
};

function canLike($ID)
{
    $db = new PDO(
        'mysql:host=localhost;dbname=instameme_dev',
        'root',
        ''
    );
    if (!isset($_SESSION["sid"])) {
        return false;
    };
    $stmt = $db->prepare("SELECT id_utilisateur,id_contenu FROM likes WHERE likes.id_contenu = :idc AND likes.id_utilisateur = :idu");
    $stmt->execute([
        "idc" => $ID,
        "idu" => $_SESSION["id"]
    ]);
    $check = $stmt->fetchAll();

    if (count($check) == 0) {
        return true;
    } else {
        return false;
    };
};

$articles = [];
$comments = [];
$users = [];

$stmt = $db->prepare("SELECT
description,
chemin_image,
count(likes.id_contenu) AS likes,
utilisateurs.pseudo,
utilisateurs.id AS idu,
contenus.id AS id
FROM
contenus
INNER JOIN likes ON likes.id_contenu = contenus.id
INNER JOIN utilisateurs ON utilisateurs.id = contenus.id_utilisateur
GROUP BY
contenus.id");
$stmt->execute();
$articles = $stmt->fetchAll();

$stmt = $db->prepare("SELECT
commentaires.id_contenu AS id,
utilisateurs.pseudo,
commentaires.message
FROM
commentaires
INNER JOIN utilisateurs ON utilisateurs.id = commentaires.id_utilisateur
ORDER BY commentaires.id_contenu");
$stmt->execute();
$comments = $stmt->fetchAll();
