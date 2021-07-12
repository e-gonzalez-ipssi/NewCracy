<?php

namespace App\Entity;

use DateTime;
use App\Entity\Tag;

class Post
{
    protected int $id;
    protected User $author;
    protected string $title;
    protected string $content;
    protected string $img;
    protected array $tag = []; // ceci est une array contenant des tags
    protected int $date;
    protected int $like;
    protected int $dislike;


    public function __construct(int $id, User $author, string $title, string $content, string $img, array $tag, int $date, int $like, int $dislike)
    {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->img = $img;
        $this->tag = $tag;
        $this->date = $date;
        $this->like = $like;
        $this->dislike = $dislike;
    }

    public function getId():int{
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getAuthor(): User{
        return $this->author;
    }

    public function setAuthor(User $user){
        $this->author = $user;
    }

    public function getTitle():string{
        return $this->title;
    }

    public function setTitle(string $title){
        $this->title = $title;
    }

    public function setContent(string $content){
        $this->content = $content;
    }
    public function getContent():string{
        return $this->content;
    }

    public function setImg(string $img){
        $this->img = $img;
    }
    public function getImgt():string{
        return $this->img;
    }

    public function setTag(array $tag){
        $this->tag = $tag;
    }

    public function getTag(): array{
        return $this->tag;
    }

    public function addTag(Tag $tag){
        array_push($this->tag, $tag);
    }

    public function setDate(int $date){
        $this->date = $date;
    }
    public function getDate(): int{
        return $this->date;
    }

    public function getLike(): int{
        return $this->like;
    }

    public function getDislike(): int{
        return $this->dislike;
    }

    public function arrayify(): array {
        $date = new DateTime();
        $date->format('U = Y-m-d H:i:s');
        $date->setTimestamp($this->date);

        $tags = [];

        /** @var Tag $tag */
        foreach($this->tag as $tag) {
            array_push($tags, $tag->arrayify());
        }

        return [
            "id" => $this->id,
            "author" => $this->author->arrayify(),
            "title" => $this->title,
            "content" => $this->content,
            "img" => $this->img,
            "tags" => $tags,
            "id" => $this->id,
            "date" => $date,
            "like" => $this->like,
            "dislike" => $this->dislike,
        ];
    }
}
