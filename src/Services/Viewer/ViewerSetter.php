<?php

namespace App\Services\Viewer;

// use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// Doctrine
use Doctrine\ORM\EntityManagerInterface;

// Entity
use App\Entity\Blogposts;
use App\Entity\Viewer;

class ViewerSetter{

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    
    public function setViewer($blogpost, $ip){

        $output1 = false;
        $output2 = false;

        // Make new record
        try {
            $viewer = new Viewer();
            $viewer->setBlogpost($blogpost->getId());
            $viewer->setIp($ip);
            $viewer->setDate(new \DateTime("now"));

            // "Commit"
            $this->em->persist($viewer);
            // "Push"
            $this->em->flush();

            $output1 = true;

        } catch(Exception $e){
            $output1 = false;
        }
        
        // Give blogpost 1+
        try {
            $new = $this->em->getRepository(Blogposts::class)->find($blogpost->getId());
            $new->setViews($new->getViews() + 1);

            // "Commit"
            $this->em->persist($new);
            // "Push"
            $this->em->flush();

            $output2 = true;

        } catch(Exception $e) {
            $output2 = false;
        }

        // Succes to DB?
        if($output1 && $output2){
            $output = 'true';
        }

        
        
        return $output;
    }

}