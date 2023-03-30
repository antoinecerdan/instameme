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
$stmt = $db->prepare("INSERT INTO likes (id_utilisateur,id_contenu) VALUES (:idu,:idc)");
$stmt->execute([
    "idu" => $_SESSION["id"],
    "idc" => $_GET["likeid"]
]);
header("Location: index.php");
