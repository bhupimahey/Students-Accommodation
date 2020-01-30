<?php
// src/AppBundle/Entity/AdminPropertyRooms.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminPropertyRooms
*
* @ORM\Table(name="property_rooms")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminPropertyRoomsRepository")
*/

class AdminPropertyRooms
{
/**
    * @var int
    *
    * @ORM\Column(name="room_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $room_id;
 
	 
   /**
    * @var int
    *
    * @ORM\Column(name="property_id", type="integer")
    */
   private $property_id;
   

   /**
    * @var int
    *
    * @ORM\Column(name="room_type", type="integer")
    */
   private $room_type;


 /**
    * @var int
    *
    * @ORM\Column(name="room_furnishings", type="integer")
    */	
   private $room_furnishings;
   
   
 /**
    * @var int
    *
    * @ORM\Column(name="room_bathroom", type="integer")
    */	
   private $room_bathroom;
   
 /**
    * @var string
    *
    * @ORM\Column(name="room_rent")
    */	
   private $room_rent;
   

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
		
 
    public function getRoomId()
    {
        return $this->room_id;
    }	
    public function getPropertyId()
    {
        return $this->property_id;
    }	  

    public function setPropertyId($property_id)
    {
        return $this->property_id= $property_id;
    }
    
   public function getRoomType()
    {
        return $this->room_type;
    }

  public function setRoomType($room_type)
    {
        return $this->room_type=$room_type;
    }

   public function getRoomFurnishings()
    {
        return $this->room_furnishings;
    }

  public function setRoomFurnishings($room_furnishings)
    {
        return $this->room_furnishings=$room_furnishings;
    }


   public function getRoomBathroom()
    {
        return $this->room_bathroom;
    }

  public function setRoomBathroom($room_bathroom)
    {
        return $this->room_bathroom=$room_bathroom;
    }

   public function getRoomRent()
    {
        return $this->room_rent;
    }

  public function setRoomRent($room_rent)
    {
        return $this->room_rent=$room_rent;
    }
    
    
  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }


   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }	
	
    
}