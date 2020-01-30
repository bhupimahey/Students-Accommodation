<?php
// src/AppBundle/Entity/CommonRegister.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* CommonRegister
*
* @ORM\Table(name="users")
* @ORM\Entity(repositoryClass="AppBundle\Repository\CommonRegisterRepository")
*/
class CommonRegister
{
/**
    * @var int
    *
    * @ORM\Column(name="account_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $account_id;
  
   /**
    * @var string
    *
    * @ORM\Column(name="full_name", type="string", length=70)
    */
   private $full_name;
   
  /**
    * @var string
    *
    * @ORM\Column(name="account_username", type="string", length=70)
    */
   private $account_username;
   

   /**
    * @var string
    *
    * @ORM\Column(name="first_name", type="string")
    */
   private $first_name;


   /**
    * @var string
    *
    * @ORM\Column(name="last_name", type="string")
    */
   private $last_name;

   
   /**
    * @var string
    *
    * @ORM\Column(name="mobile", type="string", length=15)
    */
   private $mobile;

   /**
    * @var string
    *
    * @ORM\Column(name="account_key", type="string", length=100)
    */
   private $account_key;

   /**
    * @var string
    *
    * @ORM\Column(name="account_type", type="string", length=1)
    */	
   private $account_type;
   

   /**
    * @var string
    *
    * @ORM\Column(name="account_status", type="string", length=1)
    */	
   private $account_status;



   /**
    * @var string
    *
    * @ORM\Column(name="is_deleted", type="string", length=1)
    */	
   private $is_deleted;


   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;


   /**
    * @var string
    *
    * @ORM\Column(name="activation_token", type="string", length=150)
    */	
   private $activation_token;


 /**
    * @var string
    *
    * @ORM\Column(name="passport_no", type="string", length=20)
    */	
   private $passport_no;

 /**
    * @var string
    *
    * @ORM\Column(name="address", type="string", length=100)
    */	
   private $address;

   /**
    * @var string
    *
    * @ORM\Column(name="kyc_status", type="string", length=1)
    */
   private $kyc_status;


   /**
    * @var int
    *
    * @ORM\Column(name="assigned_property_id", type="integer", length=10)
    */	
   private $assigned_property_id;


   /**
    * @var string
    *
    * @ORM\Column(name="kyc_photo_doc")
    */	
   private $kyc_photo_doc;
   

   /**
    * @var string
    *
    * @ORM\Column(name="kyc_other_doc")
    */	
   private $kyc_other_doc;


 /**
    * @var string
    *
    * @ORM\Column(name="whatsapp_mobile", type="string")
    */
   private $whatsapp_mobile;
   
 
  /**
    * @var string
    *
    * @ORM\Column(name="email_id")
    */	
   private $email_id;

  /**
    * @var string
    *
    * @ORM\Column(name="dob")
    */	
   private $dob;

  /**
    * @var string
    *
    * @ORM\Column(name="gender")
    */	
   private $gender;

  /**
    * @var string
    *
    * @ORM\Column(name="em_first_name")
    */	
   private $em_first_name;


  /**
    * @var string
    *
    * @ORM\Column(name="em_last_name")
    */	
   private $em_last_name;

  /**
    * @var string
    *
    * @ORM\Column(name="em_mobile")
    */	
   private $em_mobile;

  /**
    * @var string
    *
    * @ORM\Column(name="em_relation")
    */	
   private $em_relation;

  /**
    * @var string
    *
    * @ORM\Column(name="employer_name")
    */	
   private $employer_name;

  /**
    * @var string
    *
    * @ORM\Column(name="work_phone")
    */	
   private $work_phone;
   
    /**
    * @var string
    *
    * @ORM\Column(name="work_email")
    */	
   private $work_email; 
   
   
     /**
    * @var string
    *
    * @ORM\Column(name="college_name")
    */	
   private $college_name;   
   
   
     /**
    * @var string
    *
    * @ORM\Column(name="course")
    */	
   private $course;      
   
   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;

  public function getCustomerId()
    {
        return $this->account_id;
    }

  public function getCustomerName()
    {
        return $this->full_name;
    }	
	
   public function getCustomerPhone()
    {
        return $this->mobile;
    }
  public function getCustomerFName()
    {
        return $this->first_name;
    }	
  public function getCustomerLName()
    {
        return $this->last_name;
    }	

  public function setCustomerFName($first_name)
    {
        return $this->first_name=$first_name;
    }	
  public function setCustomerLName($last_name)
    {
        return $this->last_name=$last_name;
    }	
	
  public function getWhatsappno()
    {
        return $this->whatsapp_mobile;
    }	
  public function setWhatsappno($whatsapp_mobile)
    {
        return $this->whatsapp_mobile=$whatsapp_mobile;
    }
	

  public function getCustomerEmail()
    {
        return $this->email_id;
    }	
  public function setCustomerEmail($email_id)
    {
        return $this->email_id=$email_id;
    }	

  public function getCustomerDOB()
    {
        return $this->dob;
    }	
  public function setCustomerDOB($dob)
    {
        return $this->dob=$dob;
    }	


  public function getCustomerGender()
    {
        return $this->gender;
    }	
  public function setCustomerGender($gender)
    {
        return $this->gender=$gender;
    }	

  public function getEmergencyFname()
    {
        return $this->em_first_name;
    }	
  public function setEmergencyFname($em_first_name)
    {
        return $this->em_first_name=$em_first_name;
    }	


  public function getEmergencyLname()
    {
        return $this->em_last_name;
    }	
  public function setEmergencyLname($em_last_name)
    {
        return $this->em_last_name=$em_last_name;
    }	
	

  public function getEmergencyMobile()
    {
        return $this->em_mobile;
    }	
  public function setEmergencyMobile($em_mobile)
    {
        return $this->em_mobile=$em_mobile;
    }	
		

  public function getEmergencyRelation()
    {
        return $this->em_relation;
    }	
  public function setEmergencyRelation($em_relation)
    {
        return $this->em_relation=$em_relation;
    }


  public function getEmployerName()
    {
        return $this->employer_name;
    }	
  public function setEmployerName($employer_name)
    {
        return $this->employer_name=$employer_name;
    }

  public function getWorkMobile()
    {
        return $this->work_phone;
    }	
  public function setWorkMobile($work_phone)
    {
        return $this->work_phone=$work_phone;
    }


  public function getWorkEmail()
    {
        return $this->work_email;
    }	
  public function setWorkEmail($work_email)
    {
        return $this->work_email=$work_email;
    }

  public function getCollegeName()
    {
        return $this->college_name;
    }	
  public function setCollegeName($college_name)
    {
        return $this->college_name=$college_name;
    }

  public function getCourseName()
    {
        return $this->course;
    }	
  public function setCourseName($course)
    {
        return $this->course=$course;
    }


  public function getPassportNo()
    {
        return $this->passport_no;
    }
	
   public function setPassportNo($passport_no)
    {
        return $this->passport_no=$passport_no;
    }


  public function getAddress()
    {
        return $this->address;
    }
	
   public function setAddress($address)
    {
        return $this->address=$address;
    }	
	
	
 public function getKycStatus()
    {
        return $this->kyc_status;
    }

   public function setKycStatus($kyc_status)
    {
        return $this->kyc_status=$kyc_status;
    }	
	
   public function getAccountUsername()
    {
        return $this->account_username;
    }

  public function getCustomerAccountStatus()
    {
        return $this->account_status;
    }
 	
   public function setCustomerName($full_name)
    {
        return $this->full_name=$full_name;
    }
	
   public function setCustomerPhone($mobile)
    {
        return $this->mobile=$mobile;
    }
	
   public function setAccountUsername($account_username)
    {
        return $this->account_username=$account_username;
    }

  public function getAccountKey()
    {
        return $this->account_key;
    }
    
  public function getKycPhotoDoc()
    {
        return $this->kyc_photo_doc;
    }


   public function setKycPhotoDoc($kyc_photo_doc)
    {
        return $this->kyc_photo_doc=$kyc_photo_doc;
    }
    
  public function getKycOtherDoc()
    {
        return $this->kyc_other_doc;
    }


   public function setKycOtherDoc($kyc_other_doc)
    {
        return $this->kyc_other_doc=$kyc_other_doc;
    }

  public function setAccountKey($account_key)
    {
        return $this->account_key=$account_key;
    }

  public function setCustomerAccountStatus($account_status)
    {
        return $this->account_status=$account_status;
    }

  public function setCustomerIsDeleted($is_deleted)
    {
        return $this->is_deleted=$is_deleted;
    }

  public function setCustomerAccountType($account_type)
    {
        return $this->account_type=$account_type;
    }
		
   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		
	
   public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }	

   public function getActivationToken()
    {
        return $this->activation_token;
    }	
	
   public function setActivationToken($activation_token)
    {
        return $this->activation_token=$activation_token;
    }		
	

   public function getAssignedPropertyId()
    {
        return $this->assigned_property_id;
    }		

   public function setAssignedPropertyId($assigned_property_id)
    {
        return $this->assigned_property_id=$assigned_property_id;
    }		

			
 }