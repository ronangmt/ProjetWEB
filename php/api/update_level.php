<?php
// api/update_level.php
header('Content-Type: application/json');
require_once '../Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode(['success' => false, 'message' => 'Méthode invalide']));
}

$input = json_decode(file_get_contents('php://input'), true);

// On attend : id_utilisateur, type_operation (ex: 'addition'), nouveau_niveau
if (!isset($input['id_utilisateur']) || !isset($input['type_operation']) || !isset($input['nouveau_niveau'])) {
    exit(json_encode(['success' => false, 'message' => 'Données manquantes']));
}

// Mapping pour sécuriser le nom des colonnes (pour éviter l'injection SQL)
$colonnesAutorisees = [
    'addition' => 'niveau_addition',
    'soustraction' => 'niveau_soustraction',
    'multiplication' => 'niveau_multiplication',
    'division' => 'niveau_division',
    'total' => 'niveau_total'
];

$typeOp = $input['type_operation'];

if (!array_key_exists($typeOp, $colonnesAutorisees)) {
    exit(json_encode(['success' => false, 'message' => 'Type d\'opération inconnu']));
}

$nomColonne = $colonnesAutorisees[$typeOp];

try {
    $db = Database::getInstance()->getConnection();

    // Mise à jour dynamique de la colonne spécifique
    // Note : On ne peut pas bindparam le nom de la colonne, d'où l'utilisation du tableau $colonnesAutorisees au-dessus
    $sql = "UPDATE Utilisateurs SET $nomColonne = :niveau WHERE id_utilisateur = :uid";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':niveau' => $input['nouveau_niveau'],
        ':uid' => $input['id_utilisateur']
    ]);

    echo json_encode(['success' => true, 'message' => "Niveau $typeOp mis à jour !"]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . $e->getMessage()]);
}
?>