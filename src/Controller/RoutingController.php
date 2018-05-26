<?php

    namespace App\Controller;

    use App\Entity\Blogposts;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    // Routing in comments
    use Symfony\Component\Routing\Annotation\Route;

    // Method GET, POST, etc with Routing
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

    // Use Controller
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    // Serializer
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Encoder\XmlEncoder;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

    // Dependency's
    use App\Services\Date\Date;
    use App\Services\Viewer\Viewer;

    // Helpers
    use App\Helpers\SerializerHelper;


    class RoutingController extends Controller{

        // Serializer
        private $encoders;
        private $normalizers;
        private $serializer;

        public function __construct(){
            $this->encoders = array(new XmlEncoder(), new JsonEncoder());
            $this->normalizers = array(new ObjectNormalizer());
     
            $this->serializer = new Serializer($this->normalizers, $this->encoders);
        }

        // private function setSerializer(){
        //     $this->encoders = array(new XmlEncoder(), new JsonEncoder());
        //     $this->normalizers = array(new ObjectNormalizer());
     
        //     $this->serializer = new Serializer($this->normalizers, $this->encoders);
        // }

        /**
         * @Route("/")
         * @Method({"GET"})
         */
        public function index(Date $dateService){
            
            // init Serializer
            // $this->setSerializer();


            // Get Data
            $blogposts = $this->getDoctrine()->getRepository(Blogposts::class)->findAll();

            // Serialize to JSON
            // NOTE: Serializes to STRING ?????
            $blogposts = $this->serializer->serialize($blogposts, 'json');

            // Back to Array
            $blogposts = (array)json_decode($blogposts, true);



            // Get Service
            // $date = $this->container->get("app.date");
            

            // Check Date data
            for($i = 0; $i < count($blogposts); $i++){
                // $blogposts[$i]['date'] gives Array(Timezone, Offset, Timestamp)

                // Format to DateTime;
                $time = new \DateTime();
                $time->setTimestamp($blogposts[$i]['date']['timestamp']);
                $time->format('U = Y-m-d H:i:s');

                // Use service
                $blogposts[$i]['date'] = $dateService->Difference($time, new \DateTime("now"));
            }

            return $this->render('pages/index.html.twig', array('blogposts' => $blogposts));
            // return new Response(json_encode($blogposts));
            // return var_dump($blogposts);
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



        /**
         * @Route("/admin_panel/")
         * @Method({"GET"})
         */
        public function callbackthing(Request $request){
            $username = $this->getUser()->getUsername();
            // die(var_dump($this->getUser()));
            // $username = $request->get('code');

            return $this->render('pages/callback.html.twig', array('username' => $username));
        }




        /**
         * @Route("/save")
         * @Method({"GET"})
         */
        public function save(){
            $entityManager = $this->getDoctrine()->getManager();

            $blogpost = new Blogposts();
            $blogpost->setTitle("Blog 4");
            $blogpost->setContent("Content for Blogpost 4");
            $blogpost->setDate(new \DateTime("now"));
            $blogpost->setViews(0);
            
            $entityManager->persist($blogpost);

            $entityManager->flush();

            return new Response("Saved the blogpost");
        }

    }

?>