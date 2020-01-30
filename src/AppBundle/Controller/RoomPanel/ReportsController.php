<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminUsers; 
use AppBundle\Entity\AdminProperty;
use AppBundle\Entity\AdminVacating; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\CommonConfig;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @Route("/roompanel")
 */
class ReportsController extends Controller
{ 
    /**
    * @Route("/reports/vacating_tenants", name="vacating_tenants_report")
    */
  public function vacatingindexAction(Request $request)
    {
 $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');   
        
	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else  
	    $page ='1'; 	

	$all_property = $this->getDoctrine()->getRepository(AdminProperty::class)->AllPropertyDropdown();
	$property_array=array();
	$property_array['Choose Property']='';
	if(	$all_property){
	 foreach(	$all_property as $propertylist){
	   if($propertylist->getPropertyStatus()=='1')  
	   $property_array[$propertylist->getPropertyTitle()]=$propertylist->getId();
	 }    
	}  
 
   $users_status_dropdown=array();
   $users_status_dropdown['Choose Status']='';
   $users_status_dropdown['Active']='1';
   $users_status_dropdown['Inactive']='0';

	 if(isset($_GET['filter_value']))		 
	$search_user_by = $_GET['filter_value'];
	else
 $search_user_by ='';	
	 if(isset($_GET['filter2_value']))		 
	$filter2_value = $_GET['filter2_value'];
	else
	 $filter2_value='';
	 if(isset($_GET['filter3_value']))		 
	$filter3_value = $_GET['filter3_value'];
	else
	 $filter3_value=''; 
	 
        $form  = $this->createFormBuilder()	   	    	
    		   ->add('property_dropdown', ChoiceType::class, ['choices' =>$property_array, 'multiple' => false,'label'=>false,'required'=>false,'attr' => array('class' => 'form-control'),'data'=>$filter2_value])	   	    	
    		    ->add('Save', SubmitType::class, array('label'=> 'Search', 'attr' => array('value'=>'submit','class' => 'btn btn-primary m-b-0 btn-sm')))
			    ->getForm();        	   
        $form->handleRequest($request); 
	
	
	  if($form->isSubmitted() ){
	       $search_user_by='';
	       $filter2_value = $form['property_dropdown']->getData();
	       $filter3_value = '';
		}
    $count_users = $this->getDoctrine()->getRepository(AdminVacating::class)->CountVacating($filter2_value);	
   

	$limit       = 50;
    $maxPages    = ceil($count_users / $limit);
    $thisPage    = $page;
				 		
  
	$all_users_result    = $this->getDoctrine()->getRepository(AdminVacating::class)->AllVacating($thisPage,$limit,$filter2_value);	
   $all_users           = $all_users_result->getIterator();	
   
    foreach($all_users as $userskey => $postval){	        
	         $userinfo = $this->getDoctrine()->getRepository(AdminUsers::class)->UserInfo($postval->getUserId());
			 $propertyinfo = $this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($postval->getVacatingPropertyId());
	         $postval->userinfo=$userinfo;         
			 $postval->propertyinfo=$propertyinfo;         
	   }
	$pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_users','filter_name'=>'search_users','filter_value'=>$search_user_by,
            'filter2_name'=>'search_property','filter2_value'=>$filter2_value,'filter3_name'=>'user_status','filter3_value'=>$filter3_value
            
           ]);
					
	return $this->render('admin/reports/tenants_vacating.html.twig',[
            'total_users'=>$count_users,'all_users' => $all_users,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'filter_name'=>'search_users','filter_value'=>$search_user_by,
			'filter2_name'=>'search_property','filter2_value'=>$filter2_value,'filter3_name'=>'user_status','filter3_value'=>$filter3_value,'routname'=>'admin_view_users','all_property'=>$all_property,'search_user_by'=>$search_user_by,'form' => $form->createView()
        ]);

    }
 

    /**
    * @Route("/reports/active_tenants", name="active_tenants_report")
    */
  public function ActivetenantsindexAction(Request $request)
    {
 $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');   
        
	 if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else  
	    $page ='1'; 	

	$all_property = $this->getDoctrine()->getRepository(AdminProperty::class)->AllPropertyDropdown();
	$property_array=array();
	$property_array['Choose Property']='';
	if(	$all_property){
	 foreach(	$all_property as $propertylist){
	   if($propertylist->getPropertyStatus()=='1')  
	   $property_array[$propertylist->getPropertyTitle()]=$propertylist->getId();
	 }    
	}  
 
   $users_status_dropdown=array();
   $users_status_dropdown['Choose Status']='';
   $users_status_dropdown['Active']='1';
   $users_status_dropdown['Inactive']='0';

	 if(isset($_GET['filter_value']))		 
	$search_user_by = $_GET['filter_value'];
	else
 $search_user_by ='';	
	 if(isset($_GET['filter2_value']))		 
	$filter2_value = $_GET['filter2_value'];
	else
	 $filter2_value='';
	 if(isset($_GET['filter3_value']))		 
	$filter3_value = $_GET['filter3_value'];
	else
	 $filter3_value=''; 
	 
        $form  = $this->createFormBuilder()
	   	        ->add('property_dropdown', ChoiceType::class, ['choices' =>$property_array, 'multiple' => false,'label'=>false,'required'=>false,'attr' => array('class' => 'form-control'),'data'=>$filter2_value])
	   	        ->add('Save', SubmitType::class, array('label'=> 'Search', 'attr' => array('value'=>'submit','class' => 'btn btn-primary m-b-0 btn-sm')))
			    ->getForm();        	   
        $form->handleRequest($request); 
	
	
	  if($form->isSubmitted() ){
	       $search_user_by='';
	       $filter2_value = $form['property_dropdown']->getData();
	       $filter3_value ='';
		}


    $count_active_tenants = $this->getDoctrine()
           			 ->getRepository(AdminUsers::class)
           			 ->CountActiveTenants($filter2_value);	
           			 
           			 
	$limit       = 50;
   $maxPages    = ceil($count_active_tenants / $limit);
    $thisPage    = $page;
				 		
				 				
   $all_users = $this->getDoctrine()
           			->getRepository(AdminUsers::class)
         			->AllActiveTenants($thisPage,$limit,$filter2_value);

       $post_result = $all_users->getIterator();	
   foreach($post_result as $postkey => $postval){
       
       $user_latest_vacting_info=$this->getDoctrine()
					                               ->getRepository(AdminVacating::class)
         			                                -> LatestVacatingRequest($postval->getCustomerId(),$postval->getAssignedPropertyId());
    
      $property_info =$this->getDoctrine()
					                               ->getRepository(AdminProperty::class)
         			                                -> PropertyInfo($postval->getAssignedPropertyId()); 
        
       $postval->property_name=$property_info->getPropertyTitle(); 
         			                                
        if( $user_latest_vacting_info){
            
             $user_latest_vacting_info = $user_latest_vacting_info[0];
             
              $postval->vacating_start=$user_latest_vacting_info->getEntryTime();
              $postval->vacating_end=$user_latest_vacting_info->getVacatingEndDate();
        } 			                                
         else{
          $postval->vacating_start=$postval->vacating_end=''; 
             
         }			                                
      
   }
      // echo'<pre>';
    //   print_r($post_result);
    //   die();
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_users','filter_name'=>'search_users','filter_value'=>$search_user_by,
            'filter2_name'=>'search_property','filter2_value'=>$filter2_value,'filter3_name'=>'user_status','filter3_value'=>$filter3_value
            
           ]);
					
	return $this->render('admin/reports/active_tenants.html.twig',[
            'total_users'=>$count_active_tenants,'all_users' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'filter_name'=>'search_users','filter_value'=>$search_user_by,
			'filter2_name'=>'search_property','filter2_value'=>$filter2_value,'filter3_name'=>'user_status','filter3_value'=>$filter3_value,'routname'=>'admin_view_users','all_property'=>$all_property,'search_user_by'=>$search_user_by,'form' => $form->createView()
        ]);
        
    }
	
	 /**
     *  @Route("/reports/tenants/send_mail", name="active_tenants_mail_users")
     */

    public function TenantMailUserStatusAction(Request $request,CommonConfig $CommonConfig)
    {
	  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  	
     if ($request->isXMLHttpRequest()) {   
	 
	    $user_id          = $request->request->get('user_id');
	    $mail_subject     = $request->request->get('mail_subject');
	    $mail_content     = $request->request->get('mailcontent');
	    $mail_cc          = $request->request->get('mailcc');
	    
		if($_FILES['file']!=''){
	    $attachment_file   =$_FILES['file'];
	    }
	    else
	    $attachment_file   ='';
	    
	    
		if($user_id){
	    $entityManager = $this->getDoctrine()->getManager();
        	if($user_id){
        	    $user_ids = explode(",",$user_id);
        	    foreach($user_ids as $user_id_val){
	                $user_info   = $entityManager->getRepository(AdminUsers::class)->find($user_id_val);
	                $user_email  = $user_info->getCustomerEmail();
	                if($user_email)
	                $CommonConfig->SendMail($user_email, $mail_subject, $mail_content,'info@prestige.22creative.in','Prestige Team',$mail_cc,$attachment_file);
	                }
        	   }	
        		$this->addFlash('success', 'Mail has been send successfully');
        		return new JsonResponse(array('success' => '1','error'=>'0','message'=>'mail send successfully'));
		    }
        }
    else
           return new Response('This is not ajax!', 400);
    }

	 /**
     *  @Route("/reports/tenants_request_status/{vacating_id}", name="tenants_request_status")
     */

    public function changeTenanctRequestStatusAction($vacating_id)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$vacating_request   = $entityManager->getRepository(AdminVacating::class)->find($vacating_id);					
		$vacating_request->setVacatingStatus('c');		
		$entityManager->flush();			
		$this->addFlash('success', 'Vacating request status has been updated successfully');
		return $this->redirectToRoute('vacating_tenants_report');		
    }			
	
}
