// ScoreManager.php

// Connexion à la DB
$db = Database::getInstance();
$pdo = $db->getConnection();

function enregistrerScore($userId, $mode, $scoreValue) {
    global $pdo;
    
    // 1. Définir la requête SQL avec des marqueurs de position (?) ou nommés (:nom)
    $sql = "INSERT INTO Scores (id_utilisateur, mode_jeu, score_solo) 
            VALUES (:user_id, :mode, :score_val)";
    
    // 2. Préparer la requête (le serveur de base de données la précompile)
    $stmt = $pdo->prepare($sql);
    
    // 3. Exécuter la requête en liant les valeurs de manière sécurisée
    $stmt->execute([
        'user_id' => $userId,
        'mode' => $mode,
        'score_val' => $scoreValue
    ]);

    return $pdo->lastInsertId(); // Retourne l'ID du score inséré
}

// Utilisation :
// $nouveauScoreId = enregistrerScore(12, 'Solo', 45, 5);

function getClassementSolo() {
    global $pdo;

    $sql = "SELECT U.nom_utilisateur, S.score_solo 
            FROM Scores S
            JOIN Utilisateurs U ON S.id_utilisateur = U.id_utilisateur
            WHERE S.mode_jeu = 'Solo'
            ORDER BY S.score_solo DESC
            LIMIT 10"; 
            
    $stmt = $pdo->query($sql); // Pas de données externes, donc on peut utiliser query()
    
    return $stmt->fetchAll(); // Retourne le résultat sous forme de tableau associatif
}

// Utilisation :
// $classement = getClassementSolo();
// print_r($classement);