<?php

class User {
    private $conn;

    public function __construct($database) {
        $this->conn=$database;
    }

    public function insertUser($data){


        $req="INSERT into users (prenomUser, nomUser, usernameUser , passwordUser, mailUser, roleUser) values (?,?,?,?,?,?);";
        $stmt=$this->conn->prepare($req);

        return $stmt->execute([
            $data['prenom'],
            $data['nom'],
            $data['username'],
            $data['password'],
            $data['mail'],
            $data['role'],
        ]);
    }

    public function selectUser($username){
        $req="SELECT * from users where usernameUser = ? or mailUser = ?";

        $stmt=$this->conn->prepare($req);

        $stmt->execute([$username, $username]);

        return $res=$stmt->fetch(PDO::FETCH_ASSOC);

    }
}

?>