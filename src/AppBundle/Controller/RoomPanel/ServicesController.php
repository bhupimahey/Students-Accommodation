<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminServices; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/roompanel")
 */
class ServicesController extends Controller
{ 
    /**
    * @Route("/services", name="view_services")
    */
  public function indexAction(Request $request)
    {

        $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
 	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	    $page ='1'; 		
	
	$total_services = $this->getDoctrine()
           			   ->getRepository(AdminServices::class)
           			   ->CountServices();	
					 
	$limit = 10;
    $maxPages = ceil($total_services / $limit);
    $thisPage = $page;

 
  	$services = new AdminServices();   
	$all_services = $this->getDoctrine()
            ->getRepository(AdminServices::class)
            ->AllServices($thisPage,$limit);
			
			
 $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_services',
           ]);
					
	return $this->render('admin/services/view.html.twig',[
            'total_services'=>$total_services,'all_services' => $all_services,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_services',
        ]);			
	
    }
	
/**
     * Matches /services/*
     *
     * @Route("/services/add", name="admin_add_services")
    */	
	
  public function addservicesAction(Request $request)
    {
 
	  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		     $services = new AdminServices();   
	   $form = $this->createFormBuilder($services)
	          ->add('service_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
	         ->add('service_description', TextareaType::class, array('required'=>false,'label'=>false,'attr' => array('class' => 'form-control')))	        
			  ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        $form->handleRequest($request);	  
		  	
	  if($form->isSubmitted() ){	
	    
			$service_title  = $form['service_title']->getData();
			$service_description  = $form['service_description']->getData();
			$time  = new \DateTime(date("Y-m-d H:i:s"));
			$services->setServiceTitle($service_title);
			$services->setServiceDescription($service_description);			
			$services->setEntryTime($time);				
			# finally add data in database
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($services);
			$sn -> flush();
			$this->addFlash('success', 'Service has been added successfully');
			return $this->redirectToRoute('view_services');
	   }
	   return $this->render('admin/services/add.html.twig',array('form' => $form->createView() ));

    }



	/**
     * Matches /services/*
	 
	 /**
     * @Route("/services/edit/{slug}", name="edit_service")
     */
    public function editServiceAction(Request $request,$slug)
      {
		    $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
	    $service_info = $this->getDoctrine()->getRepository('AppBundle:AdminServices')->findBy(array("service_id"=>$slug));
	   
	   if(!$service_info)
	    return  $this->redirectToRoute('view_services');	
	   
       if(!$service_info[0]->getServiceTitle())
	      $service_title ='';
	   else	   	  
	       $service_title = $service_info[0]->getServiceTitle();

      if(!$service_info[0]->getServiceDescription())
	      $service_desc ='';
	   else	   	  
	       $service_desc = $service_info[0]->getServiceDescription();
		      
	  
	   $services = new AdminServices();   
	   $form = $this->createFormBuilder($services)
	            ->add('service_title', TextType::class, array('attr' => array('class' => 'form-control','value'=>$service_title)))
			 ->add('service_description', TextareaType::class, array('data'=>$service_desc,'required'=>false,'label'=>false,'attr' => array('class' => 'form-control')))	        
				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        	$form->handleRequest($request);	  
		  
	
	   if($form->isSubmitted() ){	
	        $entityManager = $this->getDoctrine()->getManager();
            $services = $entityManager->getRepository(AdminServices::class)->find($slug);
		  
			$services_title       = $form['service_title']->getData();
			$service_description  = $form['service_description']->getData();
						
			$services->setServiceTitle($services_title);
			$services->setServiceDescription($service_description);
			
			$entityManager->flush();	
			$this->addFlash('success', 'Service has been updated successfully');
			return $this->redirectToRoute('view_services');
	   }
	   
	   
	return $this->render('admin/services/edit.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug	
        ]);
    }
 	/**
     * Matches /services/*
   
     * @Route("/services/delete/{slug}", name="admin_delete_service")
     */
    public function delBlogAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminServices::class);
		$services      = $repository->find($slug);
		$entityManager->remove($services);
		$entityManager->flush();
		$this->addFlash('success', 'Service has been deleted successfully');
		return $this->redirectToRoute('view_services');
    }			

	
}
