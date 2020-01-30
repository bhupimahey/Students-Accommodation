<?php
// src/AppBundle/Entity/AdminVacating.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminVacating
*
* @ORM\Table(name="vacating_lists")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminVacatingRepository")
*/
class AdminVacating
{
/**
    * @var int
    *
    * @ORM\Column(name="vacating_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $vacating_id;
  
   /**
    * @var int
    *
    * @ORM\Column(name="user_id", type="integer")
    */
   private $user_id;
   

   /**
    * @var int
    *
    * @ORM\Column(name="property_id", type="integer")
    */
   private $vacating_property_id;

  /**
    * @var string
    *
    * @ORM\Column(name="reason_for", type="string")
    */
   private $reason_for;
 

  /**
    * @var string
    *
    * @ORM\Column(name="status", type="string")
    */
   private $vacating_status;


  /**
    * @var string
    *
    * @ORM\Column(name="remarks", type="string")
    */
   private $vacating_remarks;


 
    /**
    * @var string
    *
    * @ORM\Column(name="vacating_end_date", type="string")
    */	
   private $vacating_end_date;

  
 
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



  public function getVacatingId()
    {
        return $this->vacating_id;
    }

  public function getUserId()
    {
        return $this->user_id;
    }	
 
 
   public function getVacatingPropertyId()
    {
        return $this->vacating_property_id;
    }

   public function getVacatingReason()
    {
        return $this->reason_for;
    }


   public function setVacatingReason($reason_for)
    {
        return $this->reason_for=$reason_for;
    }
    

  public function getVacatingStatus()
    {
        return $this->vacating_status;
    }

  public function setVacatingStatus($vacating_status)
    {
        return $this->vacating_status=$vacating_status;
    }
    
    
  public function getVacatingRemarks()
    {
        return $this->vacating_remarks;
    }

  public function setVacatingRemarks($vacating_remarks)
    {
        return $this->vacating_remarks=$vacating_remarks;
    }    


  public function getVacatingEndDate()
    {
        return $this->vacating_end_date;
    }

  public function setVacatingEndDate($vacating_end_date)
    {
        return $this->vacating_end_date=$vacating_end_date;
    }
    
   public function getEntryTime()
    {
        return $this->entry_time;
    }	
    
   public function setUserId($user_id)
    {
        return $this->user_id=$user_id;
    }   
   public function setVacatingPropertyId($vacating_property_id)
    {
        return $this->vacating_property_id=$vacating_property_id;
    }  
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		
	
   public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }	

			
 }