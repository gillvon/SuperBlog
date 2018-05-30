<?php

    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Request;

    // Form
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    

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
    
    class AdminController extends Controller{

        // Serializer
        private $serializer;
        
        // Entity Manager
        private $em;

        public function __construct(){
            $serializer = new SerializerHelper();
            $this->serializer = $serializer->getSerializer();
        }   

        /**
         * @Route("/admin-panel", name="admin-panel")
         * @Method({"GET"})
         */
        public function index(){
            // $username = $this->getUser()->getUsername();
            // die(var_dump($this->getUser()));
            // $username = $request->get('code');

            $blogposts = $this->getDoctrine()->getRepository(Blogposts::class)->findAll();


            return $this->render('admin/index.html.twig', array('blogposts' => $blogposts));
        }

        /**
         * @Route("/admin-panel/blog/details/{id}",  name="admin-details")
         * @Method({"GET"})
         */
        public function details($id){
            $blogpost = $this->getDoctrine()->getRepository(Blogposts::class)->find($id);
            
            return $this->render('admin/details.html.twig', array('blogpost' => $blogpost));
        }

        /**
         * @Route("/admin-panel/blog/edit/{id}",  name="admin-edit")
         * @Method({"GET", "POST"})
         */
        public function edit(Request $request, $id){
            $blogpost = $this->getDoctrine()->getRepository(Blogposts::class)->find($id);

            $form = $this->createFormBuilder($blogpost)
                // Image File
                // ->add('imagessssss', FileType::class, ['label' => 'Photo', 'attr' => ['class' => 'form-control-file mb-3'], 'required' => true])
                
                ->add('title', TextType::class, ['label' => 'Title*', 'attr' => ['class' => 'form-control mb-3'], 'required' => true])
                ->add('description', TextType::class, ['label' => 'Description*', 'attr' => ['class' => 'form-control mb-3'], 'required' => true])
                ->add('content', TextareaType::class, ['label' => 'Content*', 'attr' => ['class' => 'form-control mb-3', 'rows' => 14], 'required' => true])
                ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'btn btn-success']])
                ->getForm();

            $form->handleRequest($request);

            // Check
            if($form->isSubmitted() and $form->isValid()){
                
                $blogpost->setTitle($form->get('title')->getData());
                $blogpost->setDescription($form->get('description')->getData());
                $blogpost->setContent($form->get('content')->getData());
                
                // $blogpost->setDate(new \DateTime('now'));
                // $blogpost->setViews(0);

                // To DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($blogpost);
                $em->flush();
            }
            
            return $this->render('admin/edit.html.twig', array('blogpost' => $blogpost, 'form' => $form->createView()));
        }

        /**
         * @Route("/admin-panel/blog/delete/{id}",  name="admin-delete")
         * @Method({"GET"})
         */
        public function delete($id){
            // Get post
            $blogpost = $this->getDoctrine()->getRepository(Blogposts::class)->find($id);
            
            return $this->render('admin/delete.html.twig', array('blogpost' => $blogpost));
        }

        /**
         * @Route("/admin-panel/blog/deleteConfirmation/{id}",  name="admin-deleteConfirmation")
         * @Method({"GET"})
         */
        public function deleteConfirmation($id){
            // Get post
            $blogpost = $this->getDoctrine()->getRepository(Blogposts::class)->find($id);
            
            // Delete post
            $this->getDoctrine()->getManager()->remove($blogpost);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin-panel');
        }




        /**
         * @Route("/admin-panel/blog/create", name="admin-create")
         * @Method({"GET", "POST"})
         */
        public function create(Request $request){

            $blogpost = new Blogposts();

            $form = $this->createFormBuilder($blogpost)
                // Image File
                // ->add('imagessssss', FileType::class, ['label' => 'Photo', 'attr' => ['class' => 'form-control-file mb-3'], 'required' => true])
                
                ->add('title', TextType::class, ['label' => 'Title*', 'attr' => ['class' => 'form-control mb-3'], 'required' => true])
                ->add('description', TextType::class, ['label' => 'Description*', 'attr' => ['class' => 'form-control mb-3'], 'required' => true])
                ->add('content', TextareaType::class, ['label' => 'Content*', 'attr' => ['class' => 'form-control mb-3', 'rows' => 14], 'required' => true])
                ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'btn btn-success']])
                ->getForm();

            $form->handleRequest($request);

            // Checkj
            if($form->isSubmitted() and $form->isValid()){
                
                $blogpost->setTitle($form->get('title')->getData());
                $blogpost->setDescription($form->get('description')->getData());
                $blogpost->setContent($form->get('content')->getData());
                
                $blogpost->setDate(new \DateTime('now'));
                $blogpost->setViews(0);

                // To DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($blogpost);
                $em->flush();
                return $this->redirectToRoute('admin-panel');
            }


            return $this->render('admin/create.html.twig', array('form' => $form->createView()));
        }

    }