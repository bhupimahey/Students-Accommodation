<?php
// src/AppBundle/Entity/AdminUsersSuggestions.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminUsersSuggestions
*
* @ORM\Table(name="users_suggestions")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminUsersSuggestionsRepository")
*/
class AdminUsersSuggestions
{
/**
    * @var int
    *
    * @ORM\Column(name="usuggestion_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $suggestion_id;
  
 
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
   private $property_id;
   
  /**
    * @var string
    *
    * @ORM\Column(name="suggestion_text", type="string", length=255)
    */
   private $suggestion_text;
   
   
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
	
	

   public function getSuggestions()
    {
        return $this->suggestion_text;
    }

   public function setSuggestions($suggestion_text)
    {
        return $this->suggestion_text=$suggestion_text;
    }
    
   public function getUserid()
    {
        return $this->user_id;
    }			
   public function setUserid($user_id)
    {
        return $this->user_id=$user_id;
    }	
   public function getPropertyid()
    {
        return $this->property_id;
    }			

   public function setPropertyid($property_id)
    {
        return $this->property_id=$property_id;
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

	
 }