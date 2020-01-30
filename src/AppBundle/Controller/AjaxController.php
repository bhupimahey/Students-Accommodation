<?php
namespace AppBundle\Controller;
use AppBundle\Form\FormValidationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/ajax")
 */
class AjaxController extends Controller
{ 

 
/**
 * @Route("/assign_property", name="ajax_assign_property")
 */ 
 public function AssignPropertyAction(Request $request)    
 {
    if ($request->isXMLHttpRequest()) {         
        return new JsonResponse(array('data' => 'this is a json response'));
    }

    return new Response('This is not ajax!', 400);
}
    
}
