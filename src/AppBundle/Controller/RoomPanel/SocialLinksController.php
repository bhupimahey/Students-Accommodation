<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminSocialLinks; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 *
 * @Route("/roompanel")
 */
class SocialLinksController extends Controller
{ 
    /**
    * @Route("/social_links", name="view_links")
    */
	
  public function indexAction()
    {
 
        $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
     $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	    $page ='1'; 		

	$count_links = $this->getDoctrine()
           			 ->getRepository(AdminSocialLinks::class)
           			 ->CountLinks();			

	$limit = 10;
    $maxPages = ceil($count_links / $limit);
    $thisPage = $page;
		
		  	
	$all_links = $this->getDoctrine()
           			->getRepository(AdminSocialLinks::class)
         			->AllLinks($thisPage,$limit);
     
	  $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_links',
        ]);
			
	return $this->render('admin/social_links/view.html.twig',[
            'all_links' => $all_links,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_links'
        ]);

    }
	
   /**
     * Matches /social_links/*
     *
     * @Route("/social_links/add", name="add_social_link")
    */	
	
  public function addsocialAction(Request $request)
    {
  	   $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		    
	   $sociallinks   = new AdminSocialLinks();   
	   $form   = $this->createFormBuilder($sociallinks)
       	 	      ->add('link_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
				  ->add('link_text', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
				   ->add('link_icon', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        $form->handleRequest($request);
	   if($form->isSubmitted() ){	
	   
			    $link_title      = $form['link_title']->getData();
			    $link_text       = $form['link_text']->getData();
			    $link_icon       = $form['link_icon']->getData();
		    	   
		        $time  = new \DateTime(date("Y-m-d H:i:s"));
			    $sociallinks->setLinkTitle($link_title);
				$sociallinks->setLinkText($link_text);
				$sociallinks->setLinkIcon($link_icon);
				$sociallinks->setIpAddress($request->getClientIp());
				$sociallinks->setEntryTime($time);		
			
			# finally add data in database
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($sociallinks);
			$sn -> flush();
			$this->addFlash('success', 'Social link has been added successfully');
			return $this->redirectToRoute('view_links');
	   }
	   return $this->render('admin/social_links/add.html.twig',array('form' => $form->createView() ));
    }

	/**
     * Matches /social_links/*
	 
	 /**
     * @Route("/social_links/edit/{slug}", name="edit_social_link")
     */
    public function editLinkAction(Request $request,$slug)
      {
	   $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	 
	    $slink_info = $this->getDoctrine()->getRepository('AppBundle:AdminSocialLinks')->findBy(array("id"=>$slug));
	    if(!$slink_info)
	    return  $this->redirectToRoute('view_page');	
	   
       if($slink_info){
	       $link_title    = $slink_info[0]->getLinkTitle();
	       $link_text     = $slink_info[0]->getLinkText();
	       $link_icon     = $slink_info[0]->getLinkIcon();
	   }
	
	     $pages   = new AdminSocialLinks();  
	     $form   = $this->createFormBuilder($pages)
	   	 	      ->add('link_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$link_title)))
				  ->add('link_text', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$link_text)))
				  ->add('link_icon', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$link_icon)))
				  ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        	$form->handleRequest($request);	  
	
	   if($form->isSubmitted() ){	
	        $entityManager   = $this->getDoctrine()->getManager();
            $sociallinks           = $entityManager->getRepository(AdminSocialLinks::class)->find($slug);	
            $link_title      = $form['link_title']->getData();
			$link_text       = $form['link_text']->getData();
			$link_icon       = $form['link_icon']->getData();
			
		    $sociallinks->setLinkTitle($link_title);
			$sociallinks->setLinkText($link_text);
			$sociallinks->setLinkIcon($link_icon);	
			$entityManager->flush();	
			$this->addFlash('success', 'Social link has been updated successfully');
			return $this->redirectToRoute('view_links');
	   }
	   
	return $this->render('admin/social_links/edit.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug	
        ]);
    }

 /**
     * @Route("/social_links/delete/{slug}", name="admin_delete_link")
     */
    public function dellinkAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminSocialLinks::class);
		$links         = $repository->find($slug);
		$entityManager->remove($links);
		$entityManager->flush();			
		$this->addFlash('success', 'Social link has been deleted successfully');
		return $this->redirectToRoute('view_links');			
    }			

	
}
