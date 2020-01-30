<?php
namespace AppBundle\Controller\RoomPanel;
use AppBundle\Entity\AdminUsers; 
use AppBundle\Entity\AdminProperty;
use AppBundle\Entity\CommonRegister; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\CommonConfig;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 *
 * @Route("/roompanel")
 */
class UsersController extends Controller
{ 
    /**
    * @Route("/users", name="admin_view_users")
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
	   	    	->add('search_user', TextType::class, array('label'=>false,'required'=>false,'attr' => array('class' => 'form-control','placeholder'=>'Search user by name or email or mobile')))
    		   ->add('property_dropdown', ChoiceType::class, ['choices' =>$property_array, 'multiple' => false,'label'=>false,'required'=>false,'attr' => array('class' => 'form-control'),'data'=>$filter2_value])
	   	    	->add('users_status', ChoiceType::class, ['choices' =>$users_status_dropdown,'label'=>false,'required'=>false,'attr' => array('class' => 'form-control'),'data'=>$filter3_value])
    		    ->add('Save', SubmitType::class, array('label'=> 'Search', 'attr' => array('value'=>'submit','class' => 'btn btn-primary m-b-0 btn-sm')))
			    ->getForm();        	   
        $form->handleRequest($request); 
	
	
	  if($form->isSubmitted() ){
	       $search_user_by=$form['search_user']->getData();
	       $filter2_value = $form['property_dropdown']->getData();
	       $filter3_value = $form['users_status']->getData();
		}
    $count_users = $this->getDoctrine()->getRepository(AdminUsers::class)->CountUsers($search_user_by,$filter2_value,$filter3_value);	
   

	$limit       = 50;
    $maxPages    = ceil($count_users / $limit);
    $thisPage    = $page;
				 		
  	$users        = new AdminUsers();   
	$all_users    = $this->getDoctrine()->getRepository(AdminUsers::class)->AllUsers($thisPage,$limit,$search_user_by,$filter2_value,$filter3_value);	

    foreach($all_users as $userskey => $usersval){
        $propertyinfo= $this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($usersval->getAssignedPropertyId());
        
       $usersval->property_detail=$propertyinfo;
    }
    
    
	   foreach($all_property as $postkey => $postval){
	         $property_tenant = $postval->getPropertyTenant();
	         $count_property_tenants = $this->getDoctrine()->getRepository(AdminUsers::class)->PropertyAssigned($postval->getId());
	         if($count_property_tenants==$property_tenant)
	          $postval->tenant_full='1';
	         else
	         $postval->tenant_full='0';
	      
	   }

		
    $pagination = $this->render('pagination.html.twig',[
            'maxPages'=>$maxPages,'thisPage'=>$thisPage,'routname'=>'admin_view_users','filter_name'=>'search_users','filter_value'=>$search_user_by,
            'filter2_name'=>'search_property','filter2_value'=>$filter2_value,'filter3_name'=>'user_status','filter3_value'=>$filter3_value
            
           ]);
					
	return $this->render('admin/users/view.html.twig',[
            'total_users'=>$count_users,'all_users' => $all_users,'maxPages'=>$maxPages,'thisPage'=>$thisPage,'filter_name'=>'search_users','filter_value'=>$search_user_by,
			'filter2_name'=>'search_property','filter2_value'=>$filter2_value,'filter3_name'=>'user_status','filter3_value'=>$filter3_value,'routname'=>'admin_view_users','all_property'=>$all_property,'search_user_by'=>$search_user_by,'form' => $form->createView()
        ]);

    }


	/**
     * Matches /users/*
	 /**
     * @Route("/users/assign_propert", name="ajax_assign_property")
     */

 public function AssignPropertyAction(Request $request)    
 {
	   $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
    if ($request->isXMLHttpRequest()) {   
	    $user_id     = $request->request->get('customer_id');
		$property_id = $request->request->get('property_id');
		if($user_id && $property_id){
	    $entityManager = $this->getDoctrine()->getManager();
		$users         = $entityManager->getRepository(AdminUsers::class)->find($user_id);	
		
		$users->setAssignedPropertyId($property_id);		
		$entityManager->flush();
		$this->addFlash('success', 'Property has been assigned successfully');
		return new JsonResponse(array('success' => '1','error'=>'0','message'=>'property assigned successfully'));
		}
        
    }
   else
    return new Response('This is not ajax!', 400);
}



   public function generateToken()
   {
    return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
   }

	/**
     * Matches /users/*
	 /**
     * @Route("/users/add", name="add_admin_users")
     */
  public function addUserAction(Request $request,CommonConfig $CommonConfig)
    {  
	  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	
     $random_password =  substr(str_shuffle('abcdef^%#ghijklmnopqrstu$#@%vwxyzABCDEFGHIJKLMNOP@#!^%QRSTUVWXYZ0123$#@&456789') , 0 , 10 );


	$token =$this->generateToken();
	
    $form  = $this->createFormBuilder()
	   		->add('customer_name', TextType::class, array('label'=>false,'required'=>true,'attr' => array('class' => 'form-control')))
			->add('customer_phone', TextType::class, array('label'=>false,'required'=>true,'attr' => array('class' => 'form-control')))
			->add('customer_email', EmailType::class, array('label'=>false,'required'=>true,'attr' => array('class' => 'form-control')))
			 ->add('Save', SubmitType::class, array('label'=> 'Create User And Send Email', 'attr' => array('value'=>'submit','class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20')))
			->getForm();        	   
        $form->handleRequest($request);

		if($form->isSubmitted() ){	
		    
	        $entityManager      = $this->getDoctrine()->getManager();
			$customer_name      = $form['customer_name']->getData();
			$customer_phone   	= $form['customer_phone']->getData();
			$customer_email 	= $form['customer_email']->getData();
			$customer_password  = $random_password;				
		   
		    $users     = new AdminUsers();   
		    $user_info = $this->getDoctrine()->getRepository('AppBundle:AdminUsers')->findBy(array("account_username"=>$customer_email));
		    if($user_info){
			$this->addFlash('error', 'User has already registered with this email.');
			return $this->redirectToRoute('admin_view_users');				
			}
			else{
			$token =$this->generateToken();
	        $activation_link= $this->container->get('router')->getContext()->getBaseUrl().'/registration/confirm_email/'.$token;	
		    $time  = new \DateTime(date("Y-m-d H:i:s"));
		
			$users->setCustomerName($customer_name);
			$users->setCustomerUsername($customer_email);			
			$users->setCustomerPhone($customer_phone);
			$users->setCustomerAccountKey(md5($customer_password));
			$users->setCustomerAccountType(2);	
			$users->setCustomerAccountStatus(0);	
			$users->setCustomerIsDeleted(0);
			$users->setKycStatus(0);
			$users->setPassportNo('');
			$users->setAddress('');
			$users->setIpAddress($request->getClientIp());	
			$users->setEntryTime($time);
			$users->setAssignedPropertyId('0');
			$users->setActivationToken($token);	
			$sn = $this->getDoctrine()->getManager();      
			$sn -> persist($users);
			$sn -> flush();	
			
		    $htmlContent = $this->renderView(
								'emails/user_confirm_email.twig',
								['customer_email' => $customer_email,'customer_name' => $customer_name,'customer_password' => $random_password,'token'=>$token]
							);	
		  $mail_data =  $CommonConfig->SendMail($customer_email, 'Confirm Registration', $htmlContent,'info@prestige.22creative.in','Prestige Team');
		 
	 
			$this->addFlash('success', 'User has been added successfully');
			return $this->redirectToRoute('admin_view_users');				
			}					 	
	   }
	   
	return $this->render('admin/users/add.html.twig',[
            'form' => $form->createView(),
            'token'=>$token
            ]);
     
    }

	/**
     * Matches /users/*
	 /**
     *  @Route("/users/{user_status}/{user_id}", name="admin_users_status")
     */

    public function changeUserStatusAction($user_status,$user_id)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$users         = $entityManager->getRepository(AdminUsers::class)->find($user_id);	
		
		if($user_status==0){
		$users->setCustomerAccountStatus(1);
		$users->setActivationToken(NULL);
		}
		else{
			$users->setCustomerAccountStatus(0);
		}
		
		$entityManager->flush();
			
		$this->addFlash('success', 'User status has been updated successfully');
		return $this->redirectToRoute('admin_view_users');	
	
    }			


/**
     * Matches /users/*
	 /**
     *  @Route("/users_update_profile/{user_id}", name="admin_update_user_profile")
     */

  public function updateprofileAction($user_id,Request $request,CommonConfig $CommonConfig)
   {
    $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
       $customer_id = $user_id;
       
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
	   
	   $address=$form['address']->getData();
	   
	   
			
			
		    
		    if($cust_photoid=='' ){
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
				
			
		    return $this->redirectToRoute('admin_view_users');	
		}
		
	  return $this->render('admin/users/edit.html.twig',['id'=>$user_id,'cust_docid'=>$cust_docid,'cust_photoid'=>$cust_photoid, 'user_info'=>$user_info,'form' => $form->createView(),'user_id'=>$customer_id]);	
    
	}
	
	

	/**
     * Matches /users/*
	 /**
     *  @Route("/users_kyc_status/{user_id}", name="admin_users_kyc_status")
     */

    public function changeUserKycStatusAction($user_id)
    {
    $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
	    $entityManager = $this->getDoctrine()->getManager();
		$users         = $entityManager->getRepository(AdminUsers::class)->find($user_id);	
		
		$users->setKycStatus(1);
		
		$entityManager->flush();
			
		$this->addFlash('success', 'User Kyc has been approved successfully');
		return $this->redirectToRoute('admin_view_users');	
	
    }
    
    
    
	/**
     * Matches /users/*
	 /**
     *  @Route("/users/send_mail", name="send_mail_users")
     */

    public function MailUserStatusAction(Request $request,CommonConfig $CommonConfig)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		  
     if ($request->isXMLHttpRequest()) {   
       
	    $user_id          = $request->request->get('user_id');
	    $mail_subject     = $request->request->get('mail_subject');
	    $mail_content     = $request->request->get('mailcontent');
	    $mail_cc          =  $request->request->get('user_mail_cc');
	    
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
     * @Route("/delete_users/{slug}", name="admin_delete_user")
     */
    public function deluserAction($slug)
    {
		  $session = new Session();
	    if(!$session->get('user_id'))
		  return $this->redirectToRoute('admin_login');
		  
		$user_info = $this->getDoctrine()->getRepository(CommonRegister::class)->UserInfo($slug);
		if($user_info){
		// Remove users requests
		$em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'DELETE FROM users_requests where users_requests.user_id = '.$slug.';';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        
           
         // Remove users suggestion
		$em2 = $this->getDoctrine()->getManager();
        $RAW_QUERY2 = 'DELETE FROM users_suggestions where users_suggestions.user_id = '.$slug.';';
        $statement2 = $em2->getConnection()->prepare($RAW_QUERY2);
        $statement2->execute();       
	
	     // Remove vacating lists
		$em3 = $this->getDoctrine()->getManager();
        $RAW_QUERY3 = 'DELETE FROM vacating_lists where vacating_lists.user_id = '.$slug.';';
        $statement3 = $em3->getConnection()->prepare($RAW_QUERY3);
        $statement3->execute();       
        
		
        $entityManager = $this->getDoctrine()->getManager();
		$repository    = $this->getDoctrine()->getRepository(AdminUsers::class);
		$users         = $repository->find($slug);
		$entityManager->remove($users);
		$entityManager->flush();			
		
		}
		
		$this->addFlash('success', 'User has been deleted successfully');
		return $this->redirectToRoute('admin_view_users');			
    }			

	
}
