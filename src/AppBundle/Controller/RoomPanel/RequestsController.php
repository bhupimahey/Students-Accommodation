<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminUsersRequests; 
use AppBundle\Entity\AdminUsersRequestsHistory;
use AppBundle\Service\CommonConfig;
use AppBundle\Entity\AdminServices; 
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
class RequestsController extends Controller
{ 
    /**
    * @Route("/requests", name="admin_users_requests")
    */
  public function indexAction(Request $request,CommonConfig $CommonConfig)
    {
        $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');

	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	    $page ='1'; 	
	
	$property_dropdown    = $this->getDoctrine()->getRepository(AdminProperty::class)->AllPropertyDropdown();	
	$property_array=array();
	$property_array['Choose Property']='';
	if(	$property_dropdown){
	 foreach(	$property_dropdown as $propertylist){
	   if($propertylist->getPropertyStatus()=='1')  
	   $property_array[$propertylist->getPropertyTitle()]=$propertylist->getId();
	 }    
	}
	
	$request_date_sorting=array();
   $vacating_date_sorting['Sort By Vacating Date']='';
   
   $vacating_date_sorting['Open']='1';
   $vacating_date_sorting['Closed']='asc'; 
   
		
	$count_requests = $this->getDoctrine()->getRepository(AdminUsersRequests::class)->CountRequests();						 
	$limit    = 10;
    $maxPages = ceil($count_requests / $limit);
    $thisPage = $page;
				 		
  	$requests        = new AdminUsersRequests();   
  	$final_lists    = $this->getDoctrine()->getRepository(AdminServices::class)->ServicesArrayList();	
    $requests_dropdown_array = $CommonConfig->ArrayReverse($final_lists);
  	
  	
  	
	$all_requests    = $this->getDoctrine()->getRepository(AdminUsersRequests::class)->AllRequests($thisPage,$limit);	
    $post_result     = $all_requests->getIterator();	
	foreach($post_result as $postkey => $postval){
	    
	    // get request latest remarks and date
	    
	    $latest_request_info    = $this->getDoctrine()->getRepository(AdminUsersRequestsHistory::class)->LatestRequestHistory($postval->getURequestid());	
	    if($latest_request_info && isset($latest_request_info[0])){
	       $remarksinfo =   $latest_request_info[0];
	       $latest_remarks =$remarksinfo->getRequestResolution();
	       $remarks_added_date = $remarksinfo->getRequestHistoryEntryTime();
	    }
	    else{
	       $latest_remarks =  $remarks_added_date='';
	    }
	    
		$postval->LatestRemarks  = $latest_remarks;
		$postval->LatestRemarksDate  = $remarks_added_date;
		$postval->ReuestForName=$requests_dropdown_array[$postval->getRequestId()];
		$user_info=$this->getDoctrine()->getRepository(AdminUsers::class)->UserInfo($postval->getUserid());
		$postval->CustomerName  = $user_info->getCustomerName();
		$postval->CustomerAddress  = $user_info->getAddress();
		
		
		if($user_info->getAssignedPropertyId()){
		$property_info=$this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($user_info->getAssignedPropertyId());
		$postval->PropertyName  = $property_info->getPropertyTitle();	
		}
		else
		$postval->PropertyName  = 'Not Assigned';	
					
	}
	
		
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_users_requests',
           ]);
					
	return $this->render('admin/users_requests/view.html.twig',[
            'total_requests'=>$count_requests,'all_requests' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,
			'routname'=>'admin_users_requests'
        ]);

    }


/**
 * @Route("/requests/change_status", name="ajax_update_request")
 */ 

 public function UpdateRequestAction(Request $request)    
 {
	   $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
    if ($request->isXMLHttpRequest()) {   
	    $rqstid           = $request->request->get('rqstid');
		$resolution_given = $request->request->get('resolution_given');
		$request_status   = $request->request->get('request_status');
		if($rqstid && $request_status){
	    $entityManager = $this->getDoctrine()->getManager();
		$usersrequests = $entityManager->getRepository(AdminUsersRequests::class)->find(array('srequest_id'=>$rqstid));	
		$usersrequests->setRequestStatus($request_status);		
		$entityManager->flush();
		$entityManager->clear();
		// Create log for status updated				
		$RequestHistory   = new AdminUsersRequestsHistory(); 
		$time 			  = new \DateTime(date("Y-m-d H:i:s"));
		$RequestHistory->setHistoryRequestId($rqstid);
		$RequestHistory->setRequestResolution($resolution_given);
		$RequestHistory->setRequestHistoryStatus($request_status);
		$RequestHistory->setRequestHistoryIpAddress($request->getClientIp());
		$RequestHistory->setRequestHistoryEntryTime($time);			   
		$entityManager->persist($RequestHistory);
		$entityManager ->flush(); 
		$entityManager->clear();
				
		$this->addFlash('success', 'Request status has been updated successfully');
		return new JsonResponse(array('success' => '1','error'=>'0','message'=>'Request status has been updated successfully'));
		}        
      }
   else
    return new Response('This is not ajax!', 400);
  }

	
}
