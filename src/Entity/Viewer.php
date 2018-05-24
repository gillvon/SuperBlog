<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViewerRepository")
 */
class Viewer
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
     * @ORM\Column(type="integer")
     */
    private $blogpost;

    public function getBlogpost(){
        return $this->blogpost;
    }

    public function setBlogpost($blogpost){
        $this->blogpost = $blogpost;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $ip;

    public function getIp(){
        return $this->ip;
    }

    public function setIp($ip){
        $this->ip = $ip;
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

}
