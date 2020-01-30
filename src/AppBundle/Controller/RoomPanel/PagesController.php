<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminPages; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 *
 * @Route("/roompanel")
 */
class PagesController extends Controller
{ 
    /**
    * @Route("/pages", name="view_pages")
    */
	
  public function indexAction()
    {
     $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	    $page ='1'; 		

	$count_pages = $this->getDoctrine()
           			 ->getRepository(AdminPages::class)
           			 ->CountPages();			

	$limit = 10;
    $maxPages = ceil($count_pages / $limit);
    $thisPage = $page;
		
		  	
	$all_pages = $this->getDoctrine()
           			->getRepository(AdminPages::class)
         			->AllPages($thisPage,$limit);
     
	  $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_pages',
        ]);
			
	return $this->render('admin/pages/view.html.twig',[
            'all_pages' => $all_pages,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_pages'
        ]);

    }
	
   /**
     * Matches /pages/*
     *
     * @Route("/pages/add", name="add_page")
    */	
	
  public function addpageAction(Request $request)
    {
  	     $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	   $pages   = new AdminPages();   
	   $form   = $this->createFormBuilder($pages)
       	 	      ->add('page_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
				  ->add('page_description', TextareaType::class, array('required'=>false,'label'=>false,'attr' => array('class' => 'form-control ckeditor')))
				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        $form->handleRequest($request);
	   if($form->isSubmitted() ){	
	   
			    $page_title      = $form['page_title']->getData();
			    $page_url_title  = preg_replace('/\s+/', '_', $page_title);
			    $page_desc       = $form['page_description']->getData();
		    	   
		        $time  = new \DateTime(date("Y-m-d H:i:s"));
			    $pages->setPageTitle($page_title);
				$pages->setPageUrlTitle($page_url_title);
				$pages->setPageDescription($page_desc);
				$pages->setIpAddress($request->getClientIp());
				$pages->setEntryTime($time);
				
			
			# finally add data in database
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($pages);
			$sn -> flush();
			$this->addFlash('success', 'Page has been added successfully');
			return $this->redirectToRoute('view_pages');
	   }
	   return $this->render('admin/pages/add.html.twig',array('form' => $form->createView() ));
    }



	/**
     * Matches /pages/*
	 
	 /**
     * @Route("/pages/edit/{slug}", name="edit_page")
     */
    public function editPageAction(Request $request,$slug)
      {
		    $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $page_info = $this->getDoctrine()->getRepository('AppBundle:AdminPages')->findBy(array("id"=>$slug));
	    if(!$page_info)
	    return  $this->redirectToRoute('view_page');	
	   
       if($page_info){
	       $page_title    = $page_info[0]->getPageTitle();
	       $page_desc     = $page_info[0]->getPageDescription();
	   }
	
	     $pages   = new AdminPages();  
	     $form   = $this->createFormBuilder($pages)
	   	 	      ->add('page_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$page_title)))
				  ->add('page_description', TextareaType::class, array('data'=>$page_desc,'required'=>false,'label'=>false,'attr' => array('class' => 'form-control ckeditor')))
	   			 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        	$form->handleRequest($request);	  
		  
	
	   if($form->isSubmitted() ){	
	        $entityManager    = $this->getDoctrine()->getManager();
            $pages            = $entityManager->getRepository(AdminPages::class)->find($slug);		  
			$page_title       = $form['page_title']->getData();
			$page_desc        = $form['page_description']->getData();
			$page_url_title   = preg_replace('/\s+/', '_', $page_title);		
            
            $time  = new \DateTime(date("Y-m-d H:i:s"));
            
			$pages->setPageTitle($page_title);
			$pages->setPageDescription($page_desc);
			$pages->setModifiedTime($time);
			$entityManager->flush();	

			$this->addFlash('success', 'Page has been updated successfully');
			return $this->redirectToRoute('view_pages');
	   }
	   
	   
	return $this->render('admin/pages/edit.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug	
        ]);
    }



 /**
     * @Route("/pages/delete/{slug}", name="admin_delete_page")
     */
    public function delblogAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminPages::class);
		$category      = $repository->find($slug);
		$entityManager->remove($category);
		$entityManager->flush();			
		$this->addFlash('success', 'Page has been deleted successfully');
		return $this->redirectToRoute('view_pages');			
    }			

	
}
