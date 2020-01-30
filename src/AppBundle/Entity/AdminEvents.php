<?php
// src/AppBundle/Entity/AdminEvents.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminEvents
*
* @ORM\Table(name="events")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminEventsRepository")
*/
class AdminEvents
{
/**
    * @var int
    *
    * @ORM\Column(name="event_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $event_id;
  
   /**
    * @var string
    *
    * @ORM\Column(name="event_title", type="string", length=150)
    */
   private $event_title;
   
  /**
    * @var string
    *
    * @ORM\Column(name="event_desc", type="string", length=150)
    */
   private $event_desc;
   
   
   /**
    * @var string
    *
    * @ORM\Column(name="event_venue", type="string", length=150)
    */
   private $event_venue;
      
    /**
    * @var string
    *
    * @ORM\Column(name="event_datetime", type="datetime")
    */	
    private $event_datetime;
 
   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;
   
   /**
    * @var string
    *
    * @ORM\Column(name="event_url_title", type="string", length=100)
    */	
   private $event_url_title;

   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
	
	
 
    public function getId()
    {
        return $this->event_id;
    }	
	  
   public function getEventTitle()
    {
        return $this->event_title;
    }
	
   public function setEventTitle($event_title)
    {
        return $this->event_title=$event_title;
    }


   public function getEventDesc()
    {
        return $this->event_desc;
    }
	
   public function setEventDesc($event_desc)
    {
        return $this->event_desc=$event_desc;
    }

   public function getEventUrlTitle()
    {
        return $this->event_url_title;
    }
	
   public function setEventUrlTitle($event_url_title)
    {
        return $this->event_url_title=$event_url_title;
    }
		
   public function getEventVenue()
    {
        return $this->event_venue;
    }
	
   public function setEventVenue($event_venue)
    {
        return $this->event_venue=$event_venue;
    }
			
   public function getEventDatetime()
    {
        return $this->event_datetime;
    }	
	
   public function setEventDatetime($event_datetime)
    {
        return $this->event_datetime=$event_datetime;
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