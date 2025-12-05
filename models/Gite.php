<?php

class Gite  {
    private $conn;

    public function __construct($database){
        $this->conn=$database;
    }

    public function insertGite($data){
        $req="INSERT into gites 
        values (?, ?, ?, ?, ?, ?, ?, ?, ?);";

        $stmt=$this->conn->prepare($req);

        return $stmt->execute([
            $data['nom'],
            $data['adresse'],
            $data['ville'],
            $data['codePostal'],
            $data['description'],
            $data['capacite'],
            $data['prixNuit'],
            $data['disponibilite'],
            $data['idProprietaire']
        ]);
    }

    public function selectAllGites(){

        $req="SELECT * from gites";

        $stmt=$this->conn->prepare($req);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectGitesByUser($idUser){

        $req="SELECT * from gites where idUser = ?";

        $stmt=$this->conn->prepare($req);

        $stmt->execute([$idUser]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectGite($idGite){

        $req="SELECT * from gites where idGite = ?";

        $stmt=$this->conn->prepare($req);

        return $stmt->execute([$idGite]);
    }

    public function deleteGite($idGite){
        $req="DELETE from gites where idGite = ?";

        $stmt=$this->conn->prepare($req);

        return $stmt->execute([$idGite]);
    } 
}
?>