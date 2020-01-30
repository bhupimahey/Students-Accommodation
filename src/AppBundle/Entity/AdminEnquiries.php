<?php
// src/AppBundle/Entity/AdminEnquiries.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminEnquiries
*
* @ORM\Table(name="enquiries")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminEnquiriesRepository")
*/
class AdminEnquiries
{
/**
    * @var int
    *
    * @ORM\Column(name="enquiry_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $enquiry_id;
  
   /**
    * @var string
    *
    * @ORM\Column(name="customer_name", type="string", length=60)
    */
   private $customer_name;
   
  /**
    * @var string
    *
    * @ORM\Column(name="customer_phone", type="string", length=15)
    */
   private $customer_phone;
   
   
   /**
    * @var string
    *
    * @ORM\Column(name="customer_email", type="string", length=100)
    */
   private $customer_email;


   /**
    * @var string
    *
    * @ORM\Column(name="customer_address", type="string", length=100)
    */
   private $customer_address;


   /**
    * @var string
    *
    * @ORM\Column(name="is_converted", type="string", length=1)
    */	
   private $is_converted;



   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;
   

   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
	
	
 
    public function getEnquiryId()
    {
        return $this->enquiry_id;
    }	
	  
   public function getCustomerName()
    {
        return $this->customer_name;
    }
	
   public function setCustomerName($customer_name)
    {
        return $this->customer_name=$customer_name;
    }


   public function getCustomerPhone()
    {
        return $this->customer_phone;
    }
	
   public function setCustomerPhone($customer_phone)
    {
        return $this->customer_phone=$customer_phone;
    }

   public function getCustomerEmail()
    {
        return $this->customer_email;
    }
	
   public function setCustomerEmail($customer_email)
    {
        return $this->event_url_title=$customer_email;
    }
		
   public function getCustomerAddress()
    {
        return $this->customer_address;
    }
	
   public function setCustomerAddress($customer_address)
    {
        return $this->customer_address=$customer_address;
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

   public function setIsConverted($is_converted)
    {
        return $this->is_converted=$is_converted;
    }	
			
 }