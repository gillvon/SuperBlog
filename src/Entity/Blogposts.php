<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogpostsRepository")
 */
class Blogposts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function getContent(){
        return $this->content;
    }

    public function setContent($content){
        $this->content = $content;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    public function getViews(){
        return $this->views;
    }

    public function setViews($views){
        $this->views = $views;
    }

}
