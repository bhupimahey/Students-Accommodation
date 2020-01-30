<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminEvents; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType ;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/roompanel")
 */
class EventsController extends Controller
{ 
    /**
    * @Route("/events", name="admin_view_events")
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
	
	
	$count_events = $this->getDoctrine()
           			   ->getRepository(AdminEvents::class)
           			   ->CountEvents();	
					 
	$limit = 10;
    $maxPages = ceil($count_events / $limit);
    $thisPage = $page;
				 		
  	$events = new AdminEvents();   
	$all_events = $this->getDoctrine()
            ->getRepository(AdminEvents::class)
            ->AllEvents($thisPage,$limit);
		
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_events',
           ]);
					
	return $this->render('admin/events/view.html.twig',[
            'total_events'=>$count_events,'all_events' => $all_events,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_events',
        ]);

    }
	
/**
     * Matches /events/*
     *
     * @Route("/events/add", name="admin_add_events")
    */	
	
  public function addeventAction(Request $request)
    {
    $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
	   $events = new AdminEvents();   
	   $form = $this->createFormBuilder($events)
	            ->add('event_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
	   			->add('event_venue', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
				->add('event_desc', TextareaType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
				->add('event_datetime', DateTimeType::class,array('widget' => 'single_text','label'=>false,'attr' => array('class' => 'form-control input-inline form_datetime'),'html5' => false,))
				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        $form->handleRequest($request);	  
		  
	
	   if($form->isSubmitted() ){	
	    
			$event_title    = $form['event_title']->getData();
			$event_venue   	= $form['event_venue']->getData();
			$event_desc 	= $form['event_desc']->getData();
			$event_datetime = $form['event_datetime']->getData();		
			
		    $event_url_title  = preg_replace('/\s+/', '_', $event_venue);
			$time  = new \DateTime(date("Y-m-d H:i:s"));
			$events->setEventTitle($event_title);
			$events->setEventVenue($event_venue);			
			$events->setEventDatetime($event_datetime);
			$events->setEventDesc($event_desc);		
			$events->setEventUrlTitle($event_url_title);			
			$events->setIpAddress($request->getClientIp());			
			$events->setEntryTime($time);	
			# finally add data in database
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($events);
			$sn -> flush();
			$this->addFlash('success', 'Event has been added successfully');
			return $this->redirectToRoute('admin_view_events');
	   }
	   return $this->render('admin/events/add.html.twig',array('form' => $form->createView() ));

    }



	/**
     * Matches /events/*
	 
	 /**
     * @Route("/events/edit/{slug}", name="edit_events")
     */
    public function editAction(Request $request,$slug)
      {
        $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login'); 
	    $event_info = $this->getDoctrine()->getRepository('AppBundle:AdminEvents')->findBy(array("event_id"=>$slug));
	   
	   if(!$event_info)
	    return  $this->redirectToRoute('admin_view_events');	
	   
       if(!$event_info[0]->getEventTitle())
	      $event_title ='';
	   else	   	  
	       $event_title = $event_info[0]->getEventTitle();

       if(!$event_info[0]->getEventDatetime())
	      $event_datetime ='';
	   else	   	  
	       $event_datetime = $event_info[0]->getEventDatetime();
	   
	   
	    if(!$event_info[0]->getEventDesc())
	      $event_desc ='';
	   else	   	  
	       $event_desc = $event_info[0]->getEventDesc();
		   
	
       if(!$event_info[0]->getEventVenue())
	      $event_venue ='';
	   else	   	  
	       $event_venue = $event_info[0]->getEventVenue();
		      
			  $event_datetime =  $event_datetime->format('Y-m-d H:i:s');
			  
			  $event_datetime  = new \DateTime( $event_datetime);
			  
	//echo   $event_datetime =  $event_datetime->format('Y-m-d h:i:s');	   
	   
	   
	   
	   $events = new AdminEvents();   
	   $form = $this->createFormBuilder($events)
	           ->add('event_title', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$event_title)))
	   			->add('event_venue', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$event_venue)))
				->add('event_desc', TextareaType::class, array('label'=>false,'data'=>$event_desc,'attr' => array('class' => 'form-control')))
				->add('event_datetime', DateTimeType::class,array('widget' => 'single_text','data'=>$event_datetime,'label'=>false,'attr' => array('class' => 'form-control input-inline form_datetime'),'html5' => false))
				 ->add('Save', SubmitType::class, array('label'=> 'Save', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
				 ->getForm();        
	    # Handle form response
        	$form->handleRequest($request);	  
		  
	
	   if($form->isSubmitted() ){	
	        $entityManager = $this->getDoctrine()->getManager();
            $events          = $entityManager->getRepository(AdminEvents::class)->find($slug);		  
			$event_title     = $form['event_title']->getData();
			$event_venue   	 = $form['event_venue']->getData();
			$event_desc 	 = $form['event_desc']->getData();
			$event_datetime  = $form['event_datetime']->getData();				
		    $event_url_title = preg_replace('/\s+/', '_', $event_venue);			
			
			$events->setEventTitle($event_title);
			$events->setEventVenue($event_venue);			
			$events->setEventDatetime($event_datetime);
			$events->setEventDesc($event_desc);		
			$events->setEventUrlTitle($event_url_title);			
			$events->setIpAddress($request->getClientIp());			
			$entityManager->flush();	
			
		 	
			$this->addFlash('success', 'Event has been updated successfully');
			return $this->redirectToRoute('admin_view_events');
	   }
	   
	   
	return $this->render('admin/events/edit.html.twig',[
            'form' => $form->createView(),
			'id'=>	$slug	
        ]);
    }



 /**
     * @Route("/events/delete/{slug}", name="admin_delete_events")
     */
    public function deleventAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminEvents::class);
		$event         = $repository->find($slug);
		$entityManager->remove($event);
		$entityManager->flush();	
		
		$this->addFlash('success', 'Event has been deleted successfully');
		return $this->redirectToRoute('admin_view_events');		
	
    }			

	
}
