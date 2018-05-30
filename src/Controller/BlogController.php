<?php

    namespace App\Controller;

    // Entity
    use App\Entity\Blogposts;

    // Use Controller
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    // Helpers
    use App\Helpers\SerializerHelper;    

    // Dependency
    use App\Services\Date\Date;
    use App\Services\Viewer\Viewer;
    

    // Routing in Comments
    use Symfony\Component\Routing\Annotation\Route;
    // Method GET, POST, etc with Routing
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    
    class BlogController extends Controller{

        // Serializer
        private $serializer;

        public function __construct(){
            $serializer = new SerializerHelper();
            $this->serializer = $serializer->getSerializer();
        }

        /**
         * @Route("/blog/{id}")
         * @Method({"GET"})
         */
        public function blogpost(Date $dateService, Viewer $viewer, $id){
            $blogpost = $this->getDoctrine()->getRepository(Blogposts::class)->find($id);
            
            if(!$blogpost){
                throw $this->createNotFoundException("Sorry, we couldn't find that a blogpost with an id of ". $id);
            }

            // Get random blogposts
            // Make connection to DB
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();

            // QUERY
            // $query = "SELECT COUNT(*) as count FROM blogposts";
            $query = "SELECT * FROM blogposts ORDER BY RAND() LIMIT 3";

            // Execute
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $output = $stmt->fetchAll();


            // View check
            $viewer->getsetViewer($blogpost, $_SERVER['REMOTE_ADDR']);

             // Serialize to JSON
            // NOTE: Serializes to STRING ?????
            $blogpost = $this->serializer->serialize($blogpost, 'json');

            // Back to Array
            $blogpost = (array)json_decode($blogpost, true);

            // $blogpost = $this->serializer->deserialize($blogpost, Blogpost::class, 'json');
            

            $time = new \DateTime();
            $time->setTimestamp($blogpost['date']['timestamp']);
            $time->format('U = Y-m-d H:i:s');

            // Use service
            $blogpost['date'] =  $dateService->Difference($time, new \DateTime("now"));

            return $this->render('pages/blogpost.html.twig', array('blogpost' => $blogpost, 'random' => $output));
            // return new Response(json_encode($blogpost));
            // return new Response($test);
        }
    }