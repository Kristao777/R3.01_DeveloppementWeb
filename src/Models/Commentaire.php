<?php

require_once __DIR__ . 'Database.php';

class Commentaire {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findAll() {
        $query = "SELECT * FROM commentaires";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $query = "SELECT * FROM commentaires WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBy($params) {
        $query = "SELECT * FROM commentaires WHERE " . implode(' AND ', array_map(function($key) {
            return "$key = :$key";
        }, array_keys($params)));
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($pseudo, $recetteId, $commentaire) {
        $query = "INSERT INTO commentaires (pseudo, recette_id, commentaire) VALUES (:pseudo, :recetteId, :commentaire)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':recetteId', $recetteId);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function update($id, $pseudo, $recetteId, $commentaire) {
        $query = "UPDATE commentaires SET pseudo = :pseudo, recette_id = :recetteId, commentaire = :commentaire WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':recetteId', $recetteId);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function delete($id) {
        $query = "DELETE FROM commentaires WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

}