<?php

namespace App\Entity;

class  User {

    private int $id;
    private string $lastName;
    private string $firstName;
    private string $mail;
    private string $phone;
    private string $img;
    private bool $isAdmin;

    public function __construct (  
        int $id,
        string $lastName,
        string $firstName,
        string $mail,
        ?string $phone = null,
        ?string $img = null,
        bool $isAdmin = false
    ) {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->mail = $mail;
        
        if(!empty($phone)) {
            $this->phone = $phone;
        }

        if(!empty($img)) {
            $this->img = $img;
        }

        $this->isAdmin = $isAdmin;
    }

    public function arrayify(): array {
        if(!empty($this->img)) {
            if(empty($this->phone)){
                return [
                    "id" => $this->id,
                    "lastName" => $this->lastName,
                    "firstName" => $this->firstName,
                    "mail" => $this->mail,
                    "img" => $this->img,
                ];
            }
            return [
                "id" => $this->id,
                "lastName" => $this->lastName,
                "firstName" => $this->firstName,
                "mail" => $this->mail,
                "phone" => $this->phone,
                "img" => $this->img,
            ];
        }
        elseif(!empty($this->phone)){
            return [
                "id" => $this->id,
                "lastName" => $this->lastName,
                "firstName" => $this->firstName,
                "mail" => $this->mail,
                "phone" => $this->phone,
            ];
        }
        return [
            "id" => $this->id,
            "lastName" => $this->lastName,
            "firstName" => $this->firstName,
            "mail" => $this->mail,
        ];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
    }

    public function getMail(): string {
        return $this->mail;
    }

    public function setMail(string $mail) {
        $this->mail = $mail;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function setPhone(string $phone) {
        $this->phone = $phone;
    }

    public function getImg(): string {
        return $this->img;
    }

    public function setImg(string $img) {
        $this->img = $img;
    }

    public function isAdmin(): bool {
        return $this->isAdmin;
    }
}
