<?php

    namespace App\Controller;

    // Entity
    use App\Entity\Blogposts;

    // Use Controller
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    // Helpers
    use App\Helpers\SerializerHelper; 


    use App\Services\Filter\Filter; 

    // Json Response
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;

    // Routing in Comments
    use Symfony\Component\Routing\Annotation\Route;
    // Method GET, POST, etc with Routing
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    
    class ApiController extends Controller{

        // Serializer
        private $serializer;

        public function __construct(){
            $serializer = new SerializerHelper();
            $this->serializer = $serializer->getSerializer();
        }

        /**
         * 
         * @Route("/api/all-blogposts")
         * @Method("GET")
         */
        public function allBlogposts(){
            $blogposts = $this->getDoctrine()->getRepository(Blogposts::class)->findAll();

            // $blogposts = $this->serializer->serialize($blogposts, 'json');
            // $blogposts = (array)json_decode($blogposts, true);

            $output = array();

            foreach($blogposts as $blogpost){
                $test;
                $test['id'] = $blogpost->getId();
                $test['title'] = $blogpost->getTitle();
                $test['description'] = $blogpost->getDescription();
                $test['content'] = $blogpost->getContent();
                $test['creation'] = $blogpost->getDate()->format("d/m/Y");
                $test['view'] = $blogpost->getViews();

                array_push($output, $test);
            }
            
            return new JsonResponse($output, 200);
        }

        /**
         * 
         * @Route("/api/create-post")
         * @Method("POST")
         */
        public function createBlogpost(Request $request, Filter $filter){

            $blogpost_data = (array)json_decode($request->getContent());


            $blogpost = new Blogposts();
                
            $blogpost->setTitle($blogpost_data['title']);
            $blogpost->setDescription($blogpost_data['description']);
            $blogpost->setContent($blogpost_data['content']);
            
            $blogpost->setDate(new \DateTime('now'));
            $blogpost->setViews(0);

            if($filter->hasBadWords($blogpost)){
                return new JsonResponse(['status' => false], 200);
            } else {
                // To DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($blogpost);
                $em->flush();
                return new JsonResponse(['status' => true], 200);
            }
            

            

        }
      
    }