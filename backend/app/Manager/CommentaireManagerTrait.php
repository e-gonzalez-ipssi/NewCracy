<?php 

namespace App\Manager;

use Exception;

Trait CommentaireManagerTrait {

    public function createCommentaire(int $propositionId, int $userId, string $message){
        $time = time();
        $query = "INSERT INTO `PropositionComment` VALUES ('$propositionId','$userId','$message','$time') ";
        $this->setQuery($query);
        $this->querySet();   
    }


    public function readCommentaire(int $propositionId){
        $query = "SELECT * FROM `PropositionComment` WHERE `id_Proposition`= '$propositionId' ";
        $this->setQuery($query);
        $this->querySet();   
    }

    public function updateCommentaire(int $propositionId, string $message){
        $time = time();
        $query = "UPDATE `PropositionComment` SET `commentaire` = '$message' AND `date` = '$time'  WHERE `id_Proposition` = '$propositionId'  ";
        $this->setQuery($query);
        $this->querySet();   
    }


    public function deleteCommentaire(int $propositionId){
        $query = "DELETE FROM `PropositionComment` WHERE `id_Proposition` = '$propositionId'";
        $this->setQuery($query);
        $this->querySet();   
    }
}
