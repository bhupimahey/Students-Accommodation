<?php
// src/AppBundle/Entity/AdminUsersRequestsHistory.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminUsersRequestsHistory
*
* @ORM\Table(name="users_requests_history")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminUsersRequestsHistoryRepository")
*/
class AdminUsersRequestsHistory
{
/**
    * @var int
    *
    * @ORM\Column(name="shrequest_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $history_id;
  
 
 /**
    * @var int
    *
    * @ORM\Column(name="srequest_id", type="integer")     
    */
   private $srequest_id;
   
 /**
    * @var string
    *
    * @ORM\Column(name="resolution_details", type="text")     
    */
   private $resolution_details;
   
  /**
    * @var string
    *
    * @ORM\Column(name="status", type="string", length=1)
    */
   private $status;
   
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
		

   public function getRequestId()
    {
        return $this->srequest_id;
    }
    
   public function setHistoryRequestId($srequest_id)
    {
        return $this->srequest_id=$srequest_id;
    }			
	
   public function setRequestHistoryStatus($status)
    {
        return $this->status=$status;
    }			
    


   public function getRequestResolution()
    {
        return $this->resolution_details;
    }			


   public function setRequestResolution($resolution_details)
    {
        return $this->resolution_details=$resolution_details;
    }			

  public function setRequestHistoryIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }	

  public function getRequestHistoryEntryTime()
    {
        return $this->entry_time;
    }	
  public function setRequestHistoryEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }	

 }