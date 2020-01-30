<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminPayments; 
use AppBundle\Entity\AdminUsers; 
use AppBundle\Entity\AdminProperty; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 *
 * @Route("/roompanel")
 */
class PaymentsController extends Controller
{ 
    /**
    * @Route("/payments", name="admin_users_payments")
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
		
	$count_payments = $this->getDoctrine()->getRepository(AdminPayments::class)->CountPayments();						 
	$limit          = 10;
    $maxPages       = ceil($count_payments / $limit);
    $thisPage       = $page;				 		
  
	$all_payments    = $this->getDoctrine()->getRepository(AdminPayments::class)->AllPayments($thisPage,$limit);	
    $post_result     = $all_payments->getIterator();	
	foreach($post_result as $postkey => $postval){
		$user_info=$this->getDoctrine()->getRepository(AdminUsers::class)->UserInfo($postval->getUserid());
		$postval->CustomerName    = $user_info->getCustomerName();
		$postval->CustomerMobile  = $user_info->getCustomerPhone();
		$postval->CustomerEmail  = $user_info->getCustomerUsername();
		
		if($user_info->getAssignedPropertyId()){
		$property_info=$this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($user_info->getAssignedPropertyId());
		$postval->PropertyName  = $property_info->getPropertyTitle();	
		}
		else
		$postval->PropertyName  = 'Not Assigned';						
	}
			
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_users_payments',
           ]);
					
	return $this->render('admin/payments/view.html.twig',[
            'total_payments'=>$count_payments,'all_payments' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,
			'routname'=>'admin_users_payments'
        ]);

    }
	
}
