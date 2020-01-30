<?php
namespace AppBundle\Controller;
use AppBundle\Entity\CommonRegister; 
use AppBundle\Entity\AdminUsersSuggestions; 
use AppBundle\Entity\AdminUsers; 
use AppBundle\Entity\AdminUsersRequests;
use AppBundle\Entity\AdminUsersRequestsHistory;
use AppBundle\Entity\AdminVacating;
use AppBundle\Entity\AdminServices; 
use AppBundle\Service\CommonConfig;
use AppBundle\Form\FormValidationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RequestContext;


class DashboardController extends Controller
{ 
  private $session;
  private $router;
  private $customer_id;
  private $currenturl;
   public function __construct(SessionInterface $session)
    { 
       $this->session = $session;	  
       $this->customer_id    = $this->session->get('customer_id');
	   $this->customer_email = $this->session->get('customer_email');
	   $this->customer_name  = $this->session->get('customer_name');
	   $this->getKycPhotoDoc  = $this->session->get('kyc_photo_doc');
	   $this->getKycPhotoDocStatus  = $this->session->get('kyc_photo_doc_status');
	   
	   
	  $context = new RequestContext();
	  $path_url = $context->fromRequest(Request::createFromGlobals());
	  $this->currenturl = $path_url->getPathInfo();
      	  
	   
    }

/**
 * @Route("/dashboard", name="user_dashboard")
 */ 
  public function indexAction(Request $request)
    {    
	 $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');   

      if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
		  
        return $this->render('default/dashboard.html.twig');
    }
 
 /**
 
     * @Route("/user_logout", name="user_logout")
    */	
	
  public function logoutAction(Request $request)
    {
  	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');
        else{
		$session->remove('customer_name');	
		$session->remove('customer_id');	
		return $this->redirectToRoute('view_login');			
		}  

    }
    
 
/**
 * @Route("/profile", name="user_profile")
 */ 
  public function profileAction(Request $request)
   {
	  $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
		    
     if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
       $customer_id = $this->customer_id;
     
       
		$user_info = $this->getDoctrine()->getRepository(CommonRegister::class)->UserInfo($customer_id);
		
		$assigned_property = 	$user_info->getAssignedPropertyId();
		
		$property_info = $this->getDoctrine()->getRepository('AppBundle:AdminProperty')->findBy(array("property_id"=>$assigned_property));
		$property_info =$property_info[0];
		
	  return $this->render('default/profile.html.twig',[ 'user_info'=>$user_info,'property_info'=>$property_info,'user_id'=>$customer_id    ]);	
    
	}
	

/**
 * @Route("/profile/update", name="update_user_profile")
 */ 
  public function updateprofileAction(Request $request,CommonConfig $CommonConfig)
   {
      $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');    
       $customer_id = $this->customer_id;
       
       $user_info = $this->getDoctrine()->getRepository(CommonRegister::class)->UserInfo($customer_id);
	   
	   $gender_array   = $CommonConfig->GendersConfig();
	   $assigned_property =   $user_info->getAssignedPropertyId();
	   $first_name         =   $user_info->getCustomerFName();
	   $last_name          =   $user_info->getCustomerLName();
	   
	   $cust_mobile       =   $user_info->getCustomerPhone();
	   $cust_photoid       =   $user_info->getKycPhotoDoc();
	   $cust_docid          =  $user_info->getKycOtherDoc(); 	
	   
       $passport_no       =   $user_info->getPassportNo();
	   $address          =  $user_info->getAddress(); 	
	   $whatsap_no       =$user_info->getWhatsappno(); 
	   $email_id =$user_info->getCustomerEmail(); 
	   $dob  =$user_info->getCustomerDOB(); 
	   $gender  =$user_info->getCustomerGender(); 
	   $em_first_name =$user_info->getEmergencyFname(); 
	   $em_last_name =$user_info->getEmergencyLname(); 
	   $em_mobile =$user_info->getEmergencyMobile();  $em_relation =$user_info->getEmergencyRelation();
	   $employer_name=$user_info->getEmployerName();
	   $work_phone=$user_info->getWorkMobile();
	   $work_email=$user_info->getWorkEmail();
	   $college_name=$user_info->getCollegeName();
	   $course=$user_info->getCourseName();
		 
	   if($cust_photoid=='' || $cust_docid==''){
		
	    $form = $this->createFormBuilder()
	    ->add('first_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$first_name)))->add('last_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$last_name)))
       	->add('mobile', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$cust_mobile)))
       ->add('whatsap_no', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$whatsap_no)))
	   ->add('email_id', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$email_id)))
	  ->add('dob', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control datepicker','value'=>$dob))) 
	   
	   ->add('gender', ChoiceType::class, ['choices' =>$gender_array,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>'m'])  
	->add('address', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$address)))   
	   
	   ->add('em_first_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_first_name)))   
	   
	   ->add('em_last_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_last_name)))   
	    
	   ->add('em_mobile', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_mobile)))   
	     ->add('em_relation', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_relation)))   
	    ->add('employer_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$employer_name)))    
	    ->add('work_phone', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$work_phone)))  
	
	  ->add('work_email', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$work_email)))  
	  ->add('college_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$college_name)))  
	  ->add('course', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$course)))  
	    
	    ->add('passport_no', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$passport_no)))
       	->add('address', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$address)))
        ->add('kyc_doc_photoid', FileType::class, [
					'mapped' => false,
					'label'  => false,
					'required'=>false,
					'attr' => array('class' => 'form-control'),
					'constraints' => [
						new File([
							'maxSize' => '1024k',
							'mimeTypes' => [
								'image/jpeg',
								'image/png',
								'image/jpg',
								'image/gif',
							],							              		    
							'mimeTypesMessage' => 'Please upload a valid photo id image file',
						])
					  ],
                   ])
       	 ->add('Submit', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
         ->getForm();        	   
        $form->handleRequest($request);
        
	   }
	   else{
	   $form = $this->createFormBuilder()
	     ->add('first_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$first_name)))->add('last_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$last_name)))
       	->add('mobile', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$cust_mobile)))
       ->add('whatsap_no', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$whatsap_no)))
	   ->add('email_id', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$email_id)))
	  ->add('dob', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control datepicker','value'=>$dob))) 
	   ->add('address', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$address)))   
	
	    ->add('gender', ChoiceType::class, ['choices' =>$gender_array,'label'=>false,'attr' => array('class' => 'form-control'),'data'=>'m'])
	      
	
	   ->add('em_first_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_first_name)))   
	   
	   ->add('em_last_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_last_name)))   
	    
	   ->add('em_mobile', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_mobile)))   
	     ->add('em_relation', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$em_relation)))   
	    ->add('employer_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$employer_name)))    
	    ->add('work_phone', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$work_phone)))  
	
	  ->add('work_email', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$work_email)))  
	  ->add('college_name', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$college_name)))  
	  ->add('course', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$course)))  
	  
	  
	  
	  
	   
	   	->add('passport_no', TextType::class, array('label'=>false,'attr' => array('class' => 'form-control','value'=>$passport_no)))
       	  ->add('Submit', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
         ->getForm();        	   
        $form->handleRequest($request);     
	       
	       
	   }
        
         $entityManager = $this->getDoctrine()->getManager();
         $usersprofileinfo =  $entityManager->getRepository(AdminUsers::class)->find(array('account_id'=>$customer_id));
	       
		
		if($form->isSubmitted() &&  $form->isValid()){
		   
		    $customer_full_name  = $form['first_name']->getData().' '.$form['last_name']->getData();
		    $customer_mobile     = $form['mobile']->getData();
		    
		    $passport_no  = $form['passport_no']->getData();
		    $address  = $form['address']->getData(); 
			
		$first_name         =   $form['first_name']->getData();
	   $last_name          =   $form['last_name']->getData();
	    $whatsap_no       =$form['whatsap_no']->getData();
	   $email_id =$form['email_id']->getData();
	   $dob  =$form['dob']->getData();
	   $gender  =$form['gender']->getData();
	   $em_first_name =$form['em_first_name']->getData(); 
	   $em_last_name =$form['em_last_name']->getData();
	   $em_mobile =$form['em_mobile']->getData();
	     $em_relation =$form['em_relation']->getData();
	   $employer_name=$form['employer_name']->getData();
	   $work_phone=$form['work_phone']->getData();
	   $work_email=$form['work_email']->getData();
	   $college_name=$form['college_name']->getData();
	   $course=$form['course']->getData();
	   
	   
			
			
		    
		    if($cust_photoid==''){
		     $kyc_doc_photoid     = $form['kyc_doc_photoid']->getData();
		     }
		    
		    
		    $usersprofileinfo->setCustomerName($customer_full_name);		
			$usersprofileinfo->setCustomerPhone($customer_mobile);
			$usersprofileinfo->setPassportNo($passport_no);	
			
			
			 $usersprofileinfo->setAddress($address);
			 
			
			$usersprofileinfo->setCustomerFName($first_name);		
			$usersprofileinfo->setCustomerLName($last_name);
			$usersprofileinfo->setWhatsappno($whatsap_no);	
			
			
			$usersprofileinfo->setCustomerEmail($email_id);		
			$usersprofileinfo->setCustomerDOB($dob);
			$usersprofileinfo->setCustomerGender($gender);	
			
			$usersprofileinfo->setEmergencyFname($em_first_name);		
			$usersprofileinfo->setEmergencyLname($em_last_name);
			$usersprofileinfo->setEmergencyMobile($em_mobile);
			
			$usersprofileinfo->setEmergencyRelation($em_relation);		
			$usersprofileinfo->setEmployerName($employer_name);
			$usersprofileinfo->setWorkMobile($work_phone);
			
			$usersprofileinfo->setWorkEmail($work_email);		
			$usersprofileinfo->setCollegeName($college_name);
			$usersprofileinfo->setCourseName($course);
			
		    if($cust_photoid==''){ 
		       $doc_photo_Filename = uniqid().'.'.$kyc_doc_photoid->guessExtension();
		       
		        $kyc_doc_photoid->move(
                        $this->getParameter('userdoc_directory'),
                        $doc_photo_Filename
                    );
                $session = new Session();        
                    
		    $session->set('kyc_photo_doc', $doc_photo_Filename);
		        	$usersprofileinfo->setKycPhotoDoc($doc_photo_Filename);  
		    	    
		    }
		  	
			$entityManager -> flush();					
			
		    $this->addFlash('success', 'Profile has been updated successfully. ');
				
			
		    return $this->redirectToRoute('user_profile');	
		}
		
	  return $this->render('default/update_profile.html.twig',['cust_docid'=>$cust_docid,'cust_photoid'=>$cust_photoid, 'user_info'=>$user_info,'form' => $form->createView(),'user_id'=>$customer_id]);	
    
	}
	
	
	
/**
 * @Route("/raise_service_request", name="raise_service_request")
 */ 
  public function servicerequestAction(Request $request,CommonConfig $CommonConfig)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
        if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
         $usersrequests = new AdminUsersRequests();   
		 $customer_id = $this->customer_id;
		 
		 $requests_list        = new AdminUsersRequests();   
		 $final_lists    = $this->getDoctrine()->getRepository(AdminServices::class)->ServicesArrayList();	
		 $requests_dropdown_array = $CommonConfig->ArrayReverse($final_lists);
		 
	   $form  = $this->createFormBuilder()
	   		->add('service_id', ChoiceType::class, ['choices' =>$final_lists,'label'=>false,	'attr' => array('class' => 'form-control'),	'data'=>'' ])
	   		->add('description', TextareaType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
			->add('Submit', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
		    $customer_id = $this->customer_id;
		    $user_info = $this->getDoctrine()->getRepository(CommonRegister::class)->UserInfo($customer_id);
	 	    $assigned_property = 	$user_info->getAssignedPropertyId();
	 	    
		    $service_id  = $form['service_id']->getData();
		    $service_description  = $form['description']->getData();
		    $time  = new \DateTime(date("Y-m-d H:i:s"));
		    
		    
			$usersrequests->setUserid($customer_id);		
			$usersrequests->setRequestId($service_id);
			$usersrequests->setDescription($service_description);
			$usersrequests->setIpAddress($request->getClientIp());
			$usersrequests->setRequestStatus('o');
			$usersrequests->setEntryTime($time);
			
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($usersrequests);
			$sn -> flush();					
			
		    $this->addFlash('success', 'Request has been submitted successfully. ');
				
			
		    return $this->redirectToRoute('view_raise_service_request');	
		}
		  
		return $this->render('default/raise_service_request.html.twig',array('form' => $form->createView()));
	}  		


/**
 * @Route("/vacating_notice", name="vacating_notice")
 */ 
  public function vacatingrequestAction(Request $request,CommonConfig $CommonConfig)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
   
       if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
         $vacatingrequests = new AdminVacating();
		 $customer_id = $this->customer_id;
	     $form  = $this->createFormBuilder()
	   		 ->add('vacating_date', TextType::class, array('label'=>false,'attr' => array('readonly'=>true,'class' => 'form-control datepicker-vacating')))
	   		->add('description', TextareaType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
			->add('Submit', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
		    $customer_id = $this->customer_id;
		    $user_info = $this->getDoctrine()->getRepository(CommonRegister::class)->UserInfo($customer_id);
		    $check_request_exists=$this->getDoctrine()->getRepository(AdminVacating::class)->CheckVacatingExists($customer_id);
		    
		    if(count($check_request_exists) >0){
		  
		      $vacating_id = $check_request_exists[0]['vacating_id'];
		      $entityManager      = $this->getDoctrine()->getManager();
		      $vacatingrequests = $entityManager->getRepository(AdminVacating::class)->find(array('vacating_id'=>$vacating_id));
			  $assigned_property = 	$user_info->getAssignedPropertyId();
	 	    
    		    $vacating_date  = $form['vacating_date']->getData();
    		    $vacating_reason  = $form['description']->getData();
    		    $time  = new \DateTime(date("Y-m-d H:i:s"));
    		    	
    			$vacatingrequests->setVacatingPropertyId($assigned_property);
    			$vacatingrequests->setVacatingReason($vacating_reason);
    			$vacatingrequests->setIpAddress($request->getClientIp());
    			$vacatingrequests->setVacatingEndDate($vacating_date);
    			$vacatingrequests->setEntryTime($time);			
    			$vacatingrequests->setVacatingStatus('o');
			
		      $entityManager->flush();	
		      
		      $this->addFlash('success', 'Request has been submitted successfully. ');
					  
		    }else{
		        
		    
		    $assigned_property = 	$user_info->getAssignedPropertyId();
	 	    
		    $vacating_date  = $form['vacating_date']->getData();
		    $vacating_reason  = $form['description']->getData();
		    $time  = new \DateTime(date("Y-m-d H:i:s"));
		    
			$vacatingrequests->setUserId($customer_id);		
			$vacatingrequests->setVacatingPropertyId($assigned_property);
			$vacatingrequests->setVacatingReason($vacating_reason);
			$vacatingrequests->setIpAddress($request->getClientIp());
			$vacatingrequests->setVacatingEndDate($vacating_date);
			$vacatingrequests->setEntryTime($time);			
			$vacatingrequests->setVacatingStatus('o');
			
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($vacatingrequests);
			$sn -> flush();					    
		        
			
		    $this->addFlash('success', 'Request has been submitted successfully. ');
				
			    
		    }
		    
		   return $this->redirectToRoute('view_vacating_request');	
		}
		  
		return $this->render('default/vacating_notice.html.twig',array('form' => $form->createView()));
	}  

/**
 * @Route("/vacating_notice/view", name="view_vacating_request")
 */ 
  public function viewvacatingrequestAction(Request $request,CommonConfig $CommonConfig)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
		  
      if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
   
	    if(isset($_GET['page']))		 
		$page = $_GET['page'];	
    	 else
	     $page ='1'; 
          
		    $customer_id = $this->customer_id;
			$count_requests = $this->getDoctrine()->getRepository(AdminVacating::class)->CountUsersVacating( $customer_id);						 
			$limit    = 10;
			$maxPages = ceil($count_requests / $limit);
			$thisPage = $page;
								
		
			$all_requests    = $this->getDoctrine()->getRepository(AdminVacating::class)->AllUsersVacating($thisPage,$limit, $customer_id);	
			$post_result     = $all_requests->getIterator();	
		
		
		 $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_vacating_request',
           ]);
		   
		  
		return $this->render('default/view_vacating_notice.html.twig',array('total_requests'=>$count_requests,'all_requests' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,
			'routname'=>'view_vacating_request' ));
	} 
	

	
/**
 * @Route("/raise_service_request/view", name="view_raise_service_request")
 */ 
  public function viewservicerequestAction(Request $request,CommonConfig $CommonConfig)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
     if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
   
	    if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	     $page ='1'; 
		
          $usersrequests = new AdminUsersRequests();   
		  $customer_id = $this->customer_id;
		 
		   $requests_list        = new AdminUsersRequests();   
	       $final_lists    = $this->getDoctrine()->getRepository(AdminServices::class)->ServicesArrayList();	
		   $requests_dropdown_array = $CommonConfig->ArrayReverse($final_lists);
		
			$count_requests = $this->getDoctrine()->getRepository(AdminUsersRequests::class)->CountUserRequests( $customer_id);						 
			$limit    = 10;
			$maxPages = ceil($count_requests / $limit);
			$thisPage = $page;
								
			$requests        = new AdminUsersRequests();   
			$all_requests    = $this->getDoctrine()->getRepository(AdminUsersRequests::class)->AllUsersRequests($thisPage,$limit, $customer_id);	
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
				$postval->ReuestForName= $requests_dropdown_array[$postval->getRequestId()];
			}
		
		 $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'raise_service_request',
           ]);
		   
		  
		return $this->render('default/view_raise_service_request.html.twig',array('total_requests'=>$count_requests,'all_requests' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,
			'routname'=>'view_raise_service_request' ));
	} 	
/**
 * @Route("/suggest_service", name="suggest_service")
 */ 
  public function suggestserviceAction(Request $request)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
       if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
       $suggestions = new AdminUsersSuggestions();   
		
	   $form  = $this->createFormBuilder()
	   		->add('user_suggestion', TextareaType::class, array('label'=>false,'required'=>true,'attr' => array('class' => 'form-control')))
			->add('Send', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
        $form->handleRequest($request);
		
		if($form->isSubmitted() &&  $form->isValid()){
		    $customer_id = $this->customer_id;
		    $user_info = $this->getDoctrine()->getRepository(CommonRegister::class)->UserInfo($customer_id);
	 	    $assigned_property = 	$user_info->getAssignedPropertyId();
	 	    
		    $user_suggestion  = $form['user_suggestion']->getData();
		    $time  = new \DateTime(date("Y-m-d H:i:s"));
		    
		    $suggestions->setSuggestions($user_suggestion);
			$suggestions->setUserid($customer_id);		
			$suggestions->setPropertyid($assigned_property);
			$suggestions->setIpAddress($request->getClientIp());
			$suggestions->setEntryTime($time);
			
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($suggestions);
			$sn -> flush();					
			
		    $this->addFlash('success', 'Suggestion has been submitted successfully. ');
				
			
		    return $this->redirectToRoute('view_suggest_service');	
		}
	    return $this->render('default/suggest_service.html.twig',array('form' => $form->createView() ));
	}  


/**
 * @Route("/suggest_service/view", name="view_suggest_service")
 */ 
  public function viewsuggestserviceAction(Request $reques,CommonConfig $CommonConfig)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
       if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
       
    if(isset($_GET['page']))		 
		$page = $_GET['page'];	
	 else
	     $page ='1'; 
		
          $customer_id = $this->customer_id;
		 
		  
			$count_suggestions = $this->getDoctrine()->getRepository(AdminUsersSuggestions::class)->CountUserSuggestions( $customer_id);						 
			$limit    = 10;
			$maxPages = ceil($count_suggestions / $limit);
			$thisPage = $page;
								 
			$all_requests    = $this->getDoctrine()->getRepository(AdminUsersSuggestions::class)->AllUsersSuggestions($thisPage,$limit, $customer_id);	
			$post_result     = $all_requests->getIterator();	
			
		
		 $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'view_raise_service_request',
           ]);
		   
		  
		return $this->render('default/view_suggest_service.html.twig',array('total_suggestions'=>$count_suggestions,'all_suggestions' => $post_result,'maxPages'=>$maxPages,'thisPage'=>$thisPage,
			'routname'=>'view_raise_service_request' ));
	}  

	
/**
 * @Route("/refer_friend", name="refer_friend")
 */ 
  public function referfriendAction(Request $request,CommonConfig $CommonConfig)
   {
	    $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
       if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
   
	   $customer_email = $this->customer_email;
	   $customer_name  = $this->customer_name;
	   
		$form  = $this->createFormBuilder()
	   		->add('friend_name1', TextType::class, array('label'=>false,'attr' => array('placeholder'=>'Name','class' => 'form-control')))
	   		->add('friend_email1', EmailType::class, array('label'=>false,'attr' => array('placeholder'=>'Email','class' => 'form-control')))
	   		->add('friend_name2', TextType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Name','class' => 'form-control')))
	   		->add('friend_email2', EmailType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Email','class' => 'form-control')))
	   		->add('friend_name3', TextType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Name','class' => 'form-control')))
	   		->add('friend_email3', EmailType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Email','class' => 'form-control')))
			->add('friend_name4', TextType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Name','class' => 'form-control')))
	   		->add('friend_email4', EmailType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Email','class' => 'form-control')))
			->add('friend_name5', TextType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Name','class' => 'form-control')))
	   		->add('friend_email5', EmailType::class, array('required'=>false,'label'=>false,'attr' => array('placeholder'=>'Email','class' => 'form-control')))
			->add('Send', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
        $form->handleRequest($request);
        
        
        if($form->isSubmitted() ){         
		    $mail_content ='Hi, your friend '.ucwords($customer_name).' refere you  for Prestige Accommodation ';
			$mail_subject='Refer a friend email from '.$customer_name;			
            for($i=1;$i<=5;$i++){
				$name  = $form['friend_name'.$i]->getData();
				$send_to_email = $form['friend_email'.$i]->getData();
             if($name!='' && $send_to_email!=''){                 
                 $CommonConfig->SendMail($send_to_email, $mail_subject, $mail_content,$customer_email,$customer_name);                }
			}
			$this->addFlash('success', 'Mail has been send successfully. ');
		    return $this->redirectToRoute('refer_friend');	
        }
		return $this->render('default/refer_friend.html.twig',array('form' => $form->createView() ));
	} 
	


/**
     * @Route("/changepassword", name="user_change_password")
    */	
	
  public function ChangeUserPasswordAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
		 $session = new Session();
	    if(!$session->get('customer_id'))
		  return $this->redirectToRoute('view_login');  
       if($this->getKycPhotoDoc=='' || $this->getKycPhotoDocStatus==0){
      	return $this->redirectToRoute('update_user_profile');
      	  }
   
		 $form  = $this->createFormBuilder()
	   		->add('old_password', PasswordType::class, array('label'=>false,'attr' => array('class' => 'form-control')))
			 ->add('new_password', RepeatedType::class, [
						'type' => PasswordType::class,
						'invalid_message' => 'The new password and old password fields must match.',
						'label'=>false,						
						'required' => true,
						'first_options'  => array('label'=>false,'attr' => array('class' => 'form-control')),
						'second_options' =>array('label'=>false,'attr' => array('class' => 'form-control')),
					])

			->add('Save', SubmitType::class, array('label'=> 'Submit', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
              $form->handleRequest($request);
		 
		 	 if($form->isSubmitted()){
				 $customer_id = $this->customer_id; 
				 $entityManager      = $this->getDoctrine()->getManager();
				 $updatepassworduser = $entityManager->getRepository(AdminUsers::class)->find(array('account_id'=>$customer_id));	
				 
			     $old_password      = $form['old_password']->getData();
				 $new_password      = $form['new_password']['first']->getData();
				 $confirm_password  = $form['new_password']['second']->getData();
				 
			     $password_info = $this->getDoctrine()->getRepository('AppBundle:AdminUsers')->OldPasswordCheck($old_password,$customer_id); 
			     $error=0;
				 if($password_info==0){
				  $this->addFlash('error', 'Old password does not match');
						return $this->redirectToRoute('user_change_password'); 
						$error=$error+1;	 
				 }
				if($new_password !=$confirm_password){
				  $this->addFlash('error', 'Password and confirm paswword does not match');
						return $this->redirectToRoute('user_change_password'); 	 
						$error=$error+1;
				 } 
				 
				 if($error==0){
					  $updatepassworduser->setCustomerAccountKey(md5($new_password));
					  $entityManager->flush();	
					  $this->addFlash('success', 'Password has been changed successfully');
					 
					 return $this->redirectToRoute('user_change_password'); 
				 }
		   } 
		    return $this->render('default/change_password.html.twig',array('form' => $form->createView() ));		 
							
		
    }	
	
}
