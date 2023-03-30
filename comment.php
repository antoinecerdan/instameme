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

if (count($_GET) != 0) {
    $stmt = $db->prepare("SELECT commentaires.id FROM commentaires ORDER BY commentaires.id DESC LIMIT 1");
    $stmt->execute();
    $lastID = $stmt->fetchAll();
    $lastID[0]["id"] += 1;
    echo $lastID[0]["id"];


    $stmt = $db->prepare("INSERT INTO commentaires (id,id_contenu,id_utilisateur,message,date_publication) VALUES (:id,:idc,:idu,:msg,:ts)");
    $stmt->execute([
        "id" => $lastID[0]["id"],
        "idc" => $_GET["memeid"],
        "idu" => $_SESSION["id"],
        "msg" => $_GET["msg"],
        "ts" => date("Y-m-d h:i:s")
    ]);
    echo $lastID[0]["id"];
    echo $_GET["memeid"];
    echo $_SESSION["id"];
    echo $_GET["msg"];
    echo date("Y-m-d h:i:s");
    header("Location: index.php");
};
