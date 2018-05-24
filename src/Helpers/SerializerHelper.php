<?php

    namespace App\Helpers;

    // Serializer
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Encoder\XmlEncoder;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
    
class SerializerHelper{

    // Serializer
    private $encoders;
    private $normalizers;
    private $serializer;

    public function __construct(){
        $this->encoders = array(new XmlEncoder(), new JsonEncoder());
        $this->normalizers = array(new ObjectNormalizer());
 
        return $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }
}