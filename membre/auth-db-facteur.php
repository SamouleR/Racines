<?php
class Database {
    private $host = "localhost";
    private $db_name = "u237218091_racine";
    private $username = "u237218091_racine";
    private $password = "racineSSJJ1234";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            throw new Exception("Erreur de connexion à la base de données: " . $exception->getMessage());
        }

        return $this->conn;
    }
}

class AuthDBFacteur {
    private $conn;
    private $table_name = "facteurs";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Connexion du facteur
    public function login($email, $password) {
        // Nettoyer les entrées
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));

        // Requête pour trouver le facteur
        $query = "SELECT id, nom, prenom, email, password, actif FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérifier le mot de passe
            if (password_verify($password, $row['password'])) {
                // Vérifier si le compte est actif
                if ($row['actif'] == 1) {
                    // Retourner les infos du facteur (sans le mot de passe)
                    unset($row['password']);
                    return $row;
                } else {
                    throw new Exception("Ce compte facteur est désactivé.");
                }
            }
        }
        throw new Exception("Email ou mot de passe incorrect.");
    }

    // Créer un nouveau facteur
    public function createFacteur($nom, $prenom, $email, $password, $zone_livraison) {
        // Valider les entrées
        if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($zone_livraison)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Vérifier si l'email existe déjà
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            throw new Exception("Cet email est déjà utilisé.");
        }

        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insérer dans la base de données
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nom = :nom, prenom = :prenom, email = :email, 
                  password = :password, zone_livraison = :zone_livraison, 
                  date_creation = NOW(), actif = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':zone_livraison', $zone_livraison);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        throw new Exception("Erreur lors de la création du facteur.");
    }

    // Mettre à jour un facteur
    public function updateFacteur($id, $nom, $prenom, $email, $zone_livraison, $actif = null) {
        // Valider les entrées
        if (empty($id) || empty($nom) || empty($prenom) || empty($email) || empty($zone_livraison)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Vérifier si l'email existe déjà pour un autre facteur
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            throw new Exception("Cet email est déjà utilisé par un autre facteur.");
        }

        // Construire la requête de mise à jour
        $query = "UPDATE " . $this->table_name . " 
                  SET nom = :nom, prenom = :prenom, email = :email, 
                  zone_livraison = :zone_livraison";
        
        // Ajouter le champ actif si fourni
        if ($actif !== null) {
            $query .= ", actif = :actif";
        }
        
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':zone_livraison', $zone_livraison);
        
        if ($actif !== null) {
            $stmt->bindParam(':actif', $actif, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            return true;
        }

        throw new Exception("Erreur lors de la mise à jour du facteur.");
    }

    // Changer le mot de passe d'un facteur
    public function changePassword($id, $current_password, $new_password) {
        // Valider les entrées
        if (empty($id) || empty($current_password) || empty($new_password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Récupérer le mot de passe actuel
        $query = "SELECT password FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérifier le mot de passe actuel
            if (password_verify($current_password, $row['password'])) {
                // Hasher le nouveau mot de passe
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                // Mettre à jour le mot de passe
                $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    return true;
                }
                throw new Exception("Erreur lors de la mise à jour du mot de passe.");
            }
            throw new Exception("Mot de passe actuel incorrect.");
        }
        throw new Exception("Facteur non trouvé.");
    }

    // Récupérer un facteur par son ID
    public function getFacteur($id) {
        $query = "SELECT id, nom, prenom, email, zone_livraison, date_creation, actif 
                  FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // Lister tous les facteurs (pour l'administration)
    public function getAllFacteurs() {
        $query = "SELECT id, nom, prenom, email, zone_livraison, date_creation, actif 
                  FROM " . $this->table_name . " ORDER BY nom ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Exemple d'utilisation
try {
    // Initialisation de la connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Initialisation de l'authentification des facteurs
    $authFacteur = new AuthDBFacteur($db);

    // Exemple: Connexion d'un facteur
    $facteur = $authFacteur->login('facteur@example.com', 'motdepasse');
    print_r($facteur);

    // Exemple: Création d'un nouveau facteur
    // $newFacteurId = $authFacteur->createFacteur('Dupont', 'Jean', 'jean@example.com', 'motdepasse', 'Zone 1');
    // echo "Nouveau facteur créé avec l'ID: " . $newFacteurId;

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}