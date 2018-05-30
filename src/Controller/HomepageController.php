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

    // Routing in Comments
    use Symfony\Component\Routing\Annotation\Route;
    // Method GET, POST, etc with Routing
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    
    class HomepageController extends Controller{

        // Serializer
        private $serializer;

        public function __construct(){
            $serializer = new SerializerHelper();
            $this->serializer = $serializer->getSerializer();
        }

        /**
         * @Route("/")
         * @Method({"GET"})
         */
        public function index(Date $dateService){

            // Get Data
            $blogposts = $this->getDoctrine()->getRepository(Blogposts::class)->findAll();

            // // Serialize to JSON
            // // NOTE: Serializes to STRING ?????
            // $blogposts = $this->serializer->serialize($blogposts, 'json');

            // // Back to Array
            // $blogposts = (array)json_decode($blogposts, true);

            // Check Date data
            // for($i = 0; $i < count($blogposts); $i++){
            //     // $blogposts[$i]['date'] gives Array(Timezone, Offset, Timestamp)

            //     // // Format to DateTime;
            //     // $time = new \DateTime();
            //     // // $time->setTimestamp($blogposts[$i]->getDate());

            //     // die(var_dump($blogposts[$i]->getDate()));

            //     // Use service
            //     $blogposts[$i]->setDate($dateService->Difference($blogposts[$i]->getDate(), new \DateTime("now")));
            // }

            foreach($blogposts as $blogpost){
                $blogpost->setDate($dateService->Difference($blogpost->getDate(), new \DateTime("now")));
            }

            return $this->render('pages/index.html.twig', array('blogposts' => $blogposts));
            // return new Response(json_encode($blogposts));
            // return var_dump($blogposts);
        }
    }