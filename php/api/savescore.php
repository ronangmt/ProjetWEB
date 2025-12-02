<?php
// api/save_score.php
header('Content-Type: application/json');
require_once '../Database.php';

// Vérifier la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode invalide']);
    exit;
}

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validation des données
if (!isset($input['id_utilisateur']) || !isset($input['mode_jeu']) || !isset($input['score_solo'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Requête SQL adaptée à ta capture d'écran "Scores"
    $sql = "INSERT INTO Scores (id_utilisateur, mode_jeu, score_solo) 
            VALUES (:uid, :mode, :score)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':uid' => $input['id_utilisateur'],
        ':mode' => $input['mode_jeu'],
        ':score' => $input['score_solo']
    ]);

    echo json_encode(['success' => true, 'message' => 'Score sauvegardé !']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . $e->getMessage()]);
}
?>