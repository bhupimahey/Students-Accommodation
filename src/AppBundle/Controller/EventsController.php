<?php
namespace AppBundle\Controller;
use AppBundle\Entity\AdminEvents; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
class EventsController extends Controller
{ 
/**
 * @Route("/events", name="events")
 */ 
  public function indexAction()
    {			
	$events = new AdminEvents();   
	$all_events = $this->getDoctrine()
            ->getRepository(AdminEvents::class)
            ->AllEvents();
	
	    return $this->render('default/events.html.twig',[
            'all_events' => $all_events
        ]);
    }
}
