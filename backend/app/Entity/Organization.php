<?php

namespace App\Entity;

class Organization
{

    protected int $id;
    protected string $orgName;
    protected string $content;
    protected string $orgUrl ;
    protected string $orgMail;
    protected string $phone;
    protected string $backImg ;


    public function __construct(int $id, string $orgName, string $content, string $orgUrl,string $orgMail, string $phone, string $backImg)
    {
        $this->id = $id;
        $this->orgName = $orgName;
        $this->content = $content;
        $this->orgUrl = $orgUrl;
        $this->orgMail=$orgMail;
        $this->phone=$phone;
        $this->backImg=$backImg;
    }

    public function getId():int{
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function setOrgName(string $orgName){
        $this->orgName = $orgName;
    }
    public function getOrgName():string{
        return $this->orgName;
    }

    public function setContent(string $content){
        $this->content = $content;
    }
    public function getContent():string{
        return $this->content;
    }

    public function setOrgUrl(string $orgUrl){
        $this->orgUrl = $orgUrl;
    }
    public function getOrgUrl():string{
        return $this->orgUrl;
    }

    public function setOrgMail(string $orgMail){
        $this->orgMail = $orgMail;
    }
    public function getOrgMail():string{
        return $this->orgMail;
    }

    public function setPhone(string $phone){
        $this->phone = $phone;
    }
    public function getPhone():string{
        return $this->phone;
    }

    public function setBackImg(string $backImg){
        $this->backImg = $backImg;
    }
    public function getBackImg():string{
        return $this->backImg;
    }

    public function arrayify(): array {
        return [
            "id" => $this->id,
            "orgName" => $this->orgName,
            "content" => $this->content,
            "orgUrl" => $this->orgUrl,
            "orgMail" => $this->orgMail,
            "phone" => $this->phone,
            "backImg" => $this->backImg,
        ];
    }
}
