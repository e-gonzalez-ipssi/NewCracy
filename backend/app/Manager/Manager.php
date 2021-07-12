<?php

namespace App\Manager;

use App\Entity\bd ;
use App\Entity\Organization ;
use App\Entity\Post;
use App\Entity\User;
use Exception;

abstract class  Manager
{
    private bd $bd;
    private string $query;
    protected array $inventory = [];
    
    public function __construct (bd $bd)  {
        $this->bd = $bd;
    }

    /**
     * Cette fonction permet de préparer la requete qui sera effectuer en la stokant dans l'objet Manager
     * 
     * @param string $requete la requete que l'on souhaite mettre en recherche
     * 
     * @return void
     */
    protected function setQuery (string $requete): void {
        $this->query = $requete;
    }

    /**
     * Cette fonction permet de renvoyer le resultat de la requête faite a la bd sous la forme d'une array exploitable
     * 
     * @return array
     */
    protected function querySelect(): array {
        try {
            $result = $this->bd->query($this->query);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        return $result->fetchAll();
    }
    
    /**
     * Cette fonction permet de renvoyer le resultat de la requête faite a la bd sous la forme d'une array exploitable
     * 
     * @return array
     */
    protected function querySet(): void {
        try {
            $result = $this->bd->query($this->query);
        } catch (Exception $e) {
        }
    }

    /**
     * Permet de créer un retour indiquant que la fonction c'est bien dérouler comme prévu
     * 
     * @param string $message Message a renvoyer a l'utilisateur
     * 
     * @return array
     */
    protected function ack(string $message): array {
        return [
            "code" => 200,
            "message" => $message,
        ];
    }

    /**
     * Permet de créer un retour indiquant que la fonction c'est bien dérouler comme prévu
     * 
     * @param int $code le code d'erreur a renvoyer a l'utilisateur
     * @param string $message Message a renvoyer a l'utilisateur
     * 
     * @return array
     */
    protected function error(int $code, string $message): array {
        return [
            "code" => $code,
            "message" => $message,
        ];
    }

    protected function fromQueryToOrganisation($result): Organization {
        return new Organization(
            $result[0]["id"],
            $result[0]["orgName"],
            $result[0]["content"],
            $result[0]["orgUrl"],
            $result[0]["orgMail"],
            $result[0]["phone"],
            $result[0]["backImg"],
        );
    }

    protected function fromQueryToOrganisations($result): array {
        $organisations = [];
        foreach($result as $organisation) {
            $newOrganisation = new Organization(
                $organisation["id"],
                $organisation["orgName"],
                $organisation["content"],
                $organisation["orgUrl"],
                $organisation["orgMail"],
                $organisation["phone"],
                $organisation["backImg"],
            );
            array_push($organisations, $newOrganisation);
        }
        return $organisations;
    }

    protected function fromQueryToUser(array $result): User {
        return new User(
            $result[0]["id"],
            $result[0]["lastName"],
            $result[0]["firstName"],
            $result[0]["mail"],
            $result[0]["phone"],
            $result[0]["img"],
            $result[0]["isAdmin"]
        );
    }
    
    protected function fromQueryToUsers(array $result): array {
        $users = [];
        foreach($result as $user) {
            $newUser = new User(
                $user["id"],
                $user["lastName"],
                $user["firstName"],
                $user["mail"],
                $user["phone"],
                $user["img"],
                $user["isAdmin"]
            );
            array_push($users, $newUser);
        }
        return $users;
    }

    protected function fromQueryToPropositions(array $result, User $author, array $tags, int $like, int $dislike): array {
        $return = [];
        foreach($result as $post) {
            $newPost = new Post(
                $post["id"],
                $author,
                $post["title"],
                $post["content"],
                $post["img"],
                $tags,
                $post["date"],
                $like,
                $dislike,
            );
            array_push($return, $newPost);
        }
        return $return;
    }
}
