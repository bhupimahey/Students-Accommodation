<?php
// src/AppBundle/Entity/AdminUsersRequests.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminUsersRequests
*
* @ORM\Table(name="users_requests")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminUsersRequestsRepository")
*/
class AdminUsersRequests
{
/**
    * @var int
    *
    * @ORM\Column(name="srequest_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $srequest_id;
  
 
 /**
    * @var int
    *
    * @ORM\Column(name="user_id", type="integer")     
    */
   private $user_id;
   
 /**
    * @var int
    *
    * @ORM\Column(name="request_id", type="integer")     
    */
   private $request_id;
   
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
    * @ORM\Column(name="description", type="string")
    */	
   private $description;
   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
		

   public function getRequestId()
    {
        return $this->request_id;
    }

   public function setRequestId($request_id)
    {
        return $this->request_id=$request_id;
    }
    
   public function getUserid()
    {
        return $this->user_id;
    }			

   public function setUserid($user_id)
    {
        return $this->user_id=$user_id;
    }
    
  public function getRequestStatus()
    {
        return $this->status;
    }			
  public function setRequestStatus($status)
    {
        return $this->status=$status;
    }			

   public function getURequestid()
    {
        return $this->srequest_id;
    }			

   public function getDescription()
    {
        return $this->description;
    }

  public function setDescription($description)
    {
        return $this->description=$description;
    }

   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }
    
  public function getEntryTime()
    {
        return $this->entry_time;
    }	

  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }
    


 }