<?php 

namespace App\Manager;

use App\Entity\Organization;
use App\Manager\TagManagerTrait;
use Exception;

class OrganisationManager extends Manager {

    use TagManagerTrait;

    /**
     * Cette fonction permet de setup la query pour créer un Organisation
     * 
     * @return array Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function createOrganisation(string $nom, string $description, string $lienSite , int $userId): array {
        /** @var string $newQuery */
        $newQuery = "INSERT INTO `Organization` (`orgName`, `content`, `orgUrl`) VALUES ('$nom', '$description', '$lienSite')";
        $this->setQuery($newQuery);
        $this->querySet();

        /** @var string $request */
        $request = "SELECT id FROM `Organization`  WHERE orgName = '$nom'";
        $this->setQuery($request);
        $orgId = $this->querySelect()[0]["id"];

        if(!isset($orgId)){
            throw new Exception("error-creation-organisation-failed");
        }

        /** @var string $request2 */
        $request2 = "INSERT INTO `OrgAdmin` (`id_Users`,`id_Organization`) VALUES ($userId, $orgId)";
        $this->setQuery($request2);
        $this->querySet();
        $this->addUserToOrganisation($orgId, $userId);

        return $this->ack("L'Organisation a bien été ajouté a la base de donnée");
    }

    /**
     * Cette fonction permet d'ajouter un membre dans une Organisation
     * 
     * @return array Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function addUserToOrganisation(string $organisationId , string $userId): array {
        /** @var string $request2 */    
        $request2 = "INSERT INTO `OrgMember` (`id_Organization`, `id_Users`) VALUES ($organisationId, $userId)";
        $this->setQuery($request2);
        $this->querySet();

        return $this->ack("L'user a bien été ajouté a l'organisation");
    }

    /**
     * Cette fonction permet de supprimer un membre d'une Organisation
     * 
     * @return array Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function deleteUserToOrganisation(string $organisationId , string $userId): array {
        /** @var string $request2 */    
        $request2 = "DELETE FROM `OrgMember` WHERE id_Organization = $organisationId  AND id_Users = $userId";
        $this->setQuery($request2);
        $this->querySet();

        return $this->ack("L'user a bien été supprimé de l'organisation");
    }

    /**
     * Cette fonction permet de récupéré un Organisation
     * 
     * @param int $id L'id de l'Organisation que l'on recherche
     * 
     * @return Organisation Cette fonction retourne l'Organisation rechercher
     * 
     * @throw Exception Relève une expetion si l'Organisation n'a pas été trouvé
     */
    public function getOrganisationById(int $id): Organization {
        if (!empty($this->inventory[$id])) {
            return $this->inventory[$id];
        }

        $newQuery = "SELECT * FROM `Organization` WHERE id = $id";
        $this->setQuery($newQuery);

        $result = $this->querySelect();

        if(count($result) < 1) {
            throw new Exception("error-organisation-not-found");
        }

        $org = $this->fromQueryToOrganisation($result);
        $this->inventory[$org->getId()] = $org;
        return $org;
    }

    /**
     * Cette fonction permet de récupéré un Organisation
     * 
     * @param string $nom Le nom des Organisations que l'on recherche
     * 
     * @return array Cette fonction retourne la liste d'Organisation possédant le nom rechercher
     * 
     * @throw Exception Relève une expetion si l'Organisation n'a pas été trouvé
     */
    public function getOrganisationByName(string $nom): Organization {
        $newQuery = "SELECT * FROM `Organization` WHERE orgName = '$nom'";
        $this->setQuery($newQuery);

        $result = $this->querySelect();

        if(count($result) < 1) {
            throw new Exception("error-organisation-not-found");
        }

        return $this->fromQueryToOrganisation($result);
    }

    /**
     * Cette fonction permet de supprimer une Organisation
     * 
     * @return string Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function deleteOrganisation(Organization $org, string $userName): array {
        $nom = $org->getOrgName();
        /** @var string $newQuery */
        $newQuery = "DELETE FROM `OrgAdmin`  WHERE id_Organization = (SELECT id FROM Organization WHERE orgName = $nom) AND 
        id_Users = (SELECT id FROM Users WHERE lastName = $userName)";
        $this->setQuery($newQuery);
        $this->querySet();
        /** @var string $request */
        $request = "DELETE FROM `Organization`  WHERE orgName = $nom ;";
        $this->setQuery($request);
        $this->querySet();

        return $this->ack("L'Organisation a bien été supprimé a la base de donnée");
    }

    /**
     * Permet de récupéré les organisations d'un utilisateur
     * 
     * @param int $userId l'id de l'utilisateur dont on veux récupéré les organisations
     * 
     * @return array La liste des organisations de l'utlisateur au format Id
     */
    public function getOrganisationsFromUser(int $userId): array {
        /** @var string $newQuery */
        $newQuery = "SELECT `id_Organization` FROM `OrgMember` WHERE id_Users = $userId";
        $this->setQuery($newQuery);
        $result = $this->querySelect();
        
        return $result;
    }

    /**
     * Permet de récupéré les organisations d'un utilisateur
     * 
     * @param int $orgId l'id de l'organisation dont on veux récupéré les membres abonnées
     * 
     * @return array La liste des organisations de l'utlisateur au format Id
     */
    public function getUsersFromOrganisation(int $orgId): array {
        /** @var string $newQuery */
        $newQuery = "SELECT `id_Users` FROM `OrgMember` WHERE id_Organization = $orgId";
        $this->setQuery($newQuery);
        $result = $this->querySelect();
        
        return $result;
    }

    /**
     * Permet de récupéré les organisations d'un utilisateur
     * 
     * @param int $orgId l'id de l'organisation dont on veux récupéré les membres admins
     * 
     * @return array La liste des organisations de l'utlisateur au format id
     */
    public function getAdminsFromOrganisation(int $orgId): array {
        /** @var string $newQuery */
        $newQuery = "SELECT `id_Users` FROM `OrgAdmin` WHERE id_Organization = $orgId";
        $this->setQuery($newQuery);
        $result = $this->querySelect();
        
        return $result;
    }

    /**
     * Cette fonction permet d'ajouter un admin dans une Organisation
     * 
     * @return array Cette fonction retourne ou un message d'erreur ou un message disant que tout c'est bien passer
     * 
     */
    public function addAdminToOrganisation(string $organisationId , string $userId): array {
        /** @var string $request2 */    
        $request2 = "INSERT INTO `OrgAdmin` (`id_Organization`, `id_Users`) VALUES ($organisationId, $userId)";
        $this->setQuery($request2);
        $this->querySet();

        return $this->ack("L'user a bien été ajouté a l'organisation");
    }
}
