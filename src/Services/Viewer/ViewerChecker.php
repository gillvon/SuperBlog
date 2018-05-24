<?php

namespace App\Services\Viewer;

// Doctrine
use Doctrine\ORM\EntityManagerInterface;

// Entity
use App\Entity\Viewer;

// Serializer
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ViewerChecker{

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;

        // init Serializer
        $this->setSerializer();
    }
    
    public function getViewer($blogpost, $ip){

        $output;        
        $view = $this->em->getRepository(Viewer::class)->findBy([
                'blogpost'  => $blogpost->getId(),
                'ip'        => $ip,
            ]);

        if(count($view) > 0){
            $array_view = $this->serializer->serialize($view, 'json');
            $array_view = (array)json_decode($array_view, true);
            
    
            // Format to DateTime;
            $viewDate = new \DateTime();
            $viewDate->setTimestamp($array_view[0]['date']['timestamp']);
            $viewDate->format('U = Y-m-d H:i:s');
    
            $diff = date_diff($viewDate, new \DateTime("now"));
            
            // date > 5min
            if($diff->format('%a') >= 1 || $diff->format('%h') >= 1 || $diff->format('%i') >= 10 ){
                // Remove
                $this->em->remove($view[0]);
                $this->em->flush();
    
                $output = true;
    
            } else {
                $output = false;
            }
        } else {
            $output = true;
        }
           
        return $output;
    }

    private function setSerializer(){
        $this->encoders = array(new XmlEncoder(), new JsonEncoder());
        $this->normalizers = array(new ObjectNormalizer());
 
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

}