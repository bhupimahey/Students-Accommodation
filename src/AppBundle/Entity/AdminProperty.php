<?php
// src/AppBundle/Entity/AdminProperty.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminProperty
*
* @ORM\Table(name="property")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminPropertyRepository")
*/

class AdminProperty
{
/**
    * @var int
    *
    * @ORM\Column(name="property_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $property_id;
 
	 
   /**
    * @var string
    *
    * @ORM\Column(name="property_url_title", type="string", length=150)
    */
   private $property_url_title;
   

   /**
    * @var string
    *
    * @ORM\Column(name="property_title", type="string", length=150)
    */
   private $property_title;


 /**
    * @var int
    *
    * @ORM\Column(name="property_type", type="integer")
    */	
   private $property_type;
   
   
 /**
    * @var int
    *
    * @ORM\Column(name="property_bedrooms", type="integer")
    */	
   private $property_bedrooms;
   
 /**
    * @var int
    *
    * @ORM\Column(name="property_bathrooms", type="integer")
    */	
   private $property_bathrooms;
   

 /**
    * @var int
    *
    * @ORM\Column(name="property_parking", type="integer")
    */	
   private $property_parking;
 
  /**
    * @var int
    *
    * @ORM\Column(name="property_internet", type="integer")
    */	
   private $property_internet;  
      

  /**
    * @var int
    *
    * @ORM\Column(name="total_flatmates", type="integer")
    */	
   private $total_flatmates;  


  /**
    * @var int
    *
    * @ORM\Column(name="bond_status", type="integer")
    */	
   private $bond_status;  
   


  /**
    * @var int
    *
    * @ORM\Column(name="bills_status", type="integer")
    */	
   private $bills_status;  

   
  /**
    * @var string
    *
    * @ORM\Column(name="property_address", type="string", length=150)
    */
   private $property_address;
   

   /**
    * @var string
    *
    * @ORM\Column(name="room_furnishings_features", type="string")
    */
   private $room_furnishings_features; 


   
   /**
    * @var string
    *
    * @ORM\Column(name="lease_startdate", type="string")
    */
   private $lease_start_date; 
   
   /**
    * @var string
    *
    * @ORM\Column(name="lease_enddate", type="string")
    */
   private $lease_end_date; 
   

   /**
    * @var string
    *
    * @ORM\Column(name="status", type="string")
    */
   private $property_status; 
   
   
  /**
    * @var string
    *
    * @ORM\Column(name="property_desc", type="string", length=255)
    */
   private $property_desc;


 /**
    * @var string
    *
    * @ORM\Column(name="flatmatespref", type="string", length=150)
    */
   private $flatmatespref;
   
   
   
 /**
    * @var string
    *
    * @ORM\Column(name="about_flatmates", type="string")
    */
   private $about_flatmates;
   
   
 /**
    * @var string
    *
    * @ORM\Column(name="about_living_property", type="string")
    */
   private $about_living_property;
    
 
   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;
 
  /**
    * @var int
    *
    * @ORM\Column(name="total_tenants_allowed", type="integer")
    */	
   private $property_tenant;


  /**
    * @var int
    *
    * @ORM\Column(name="minimum_stay_length", type="integer")
    */	
   private $minimum_stay_length;


  /**
    * @var string
    *
    * @ORM\Column(name="available_date")
    */	
   private $available_date;
   
   
   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
    
    
   /**
    * @var string
    *
    * @ORM\Column(name="modified_time", type="datetime")
    */	
    private $modified_time;

		
 
    public function getId()
    {
        return $this->property_id;
    }	

   public function getAvailableDate()
    {
        return $this->available_date;
    }

   public function setAvailableDate($available_date)
    {
        return $this->available_date=$available_date;
    }

   public function getPropertyUrlTitle()
    {
        return $this->property_url_title;
    }

  public function setPropertyUrlTitle($property_url_title)
    {
        return $this->property_url_title=$property_url_title;
    }

  
  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }

  public function getRoomFurnishingsFeatures()
    {
        return $this->room_furnishings_features;
    }

  public function setRoomFurnishingsFeatures($room_furnishings_features)
    {
        return $this->room_furnishings_features=$room_furnishings_features;
    }

  public function getPropertyParking()
    {
        return $this->property_parking;
    }

  public function setPropertyParking($property_parking)
    {
        return $this->property_parking=$property_parking;
    }

  public function getPropertyInternet()
    {
        return $this->property_internet;
    }

  public function setPropertyInternet($property_internet)
    {
        return $this->property_internet=$property_internet;
    }


  public function getBondStatus()
    {
        return $this->bond_status;
    }

  public function setBondStatus($bond_status)
    {
        return $this->bond_status=$bond_status;
    }
    
  public function getBillsStatus()
    {
        return $this->bills_status;
    }

  public function setBillsStatus($bills_status)
    {
        return $this->bills_status=$bills_status;
    }
 
  public function getFlatmatesPreference()
    {
        return $this->flatmatespref;
    }

  public function setFlatmatesPreference($flatmatespref)
    {
        return $this->flatmatespref=$flatmatespref;
    }

  public function getAboutFlatmates()
    {
        return $this->about_flatmates;
    }

  public function setAboutFlatmates($about_flatmates)
    {
        return $this->about_flatmates=$about_flatmates;
    }

  public function getAboutLivingProperty()
    {
        return $this->about_living_property;
    }

  public function setAboutLivingProperty($about_living_property)
    {
        return $this->about_living_property=$about_living_property;
    }
  
   public function getPropertyTitle()
    {
        return $this->property_title;
    }
	

  public function getPropertyBathrooms()
    {
        return $this->property_bathrooms;
    }
   public function setPropertyBathrooms($property_bathrooms)
    {
        return $this->property_bathrooms=$property_bathrooms;
    }



  public function getPropertyType()
    {
        return $this->property_type;
    }
   public function setPropertyType($property_type)
    {
        return $this->property_type=$property_type;
    }



  public function getTotalFlatmates()
    {
        return $this->total_flatmates;
    }
   public function setTotalFlatmates($total_flatmates)
    {
        return $this->total_flatmates=$total_flatmates;
    }



  public function getPropertyBedrooms()
    {
        return $this->property_bedrooms;
    }
   public function setPropertyBedrooms($property_bedrooms)
    {
        return $this->property_bedrooms=$property_bedrooms;
    }
    
    
 public function setPropertyTenant($property_tenant)
    {
        return $this->property_tenant=$property_tenant;
    }

   public function getPropertyTenant()
    {
        return $this->property_tenant;
    }


   public function setPropertyTitle($property_title)
    {
        return $this->property_title=$property_title;
    }

   public function getPropertyDescription()
    {
        return $this->property_desc;
    }
	
   public function setPropertyDescription($property_desc)
    {
        return $this->property_desc=$property_desc;
    }

  public function getPropertyAddress()
    {
        return $this->property_address;
    }
	
   public function setPropertyAddress($property_address)
    {
        return $this->property_address=$property_address;
    }

   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }	
    
   public function setModifiedTime($modified_time)
    {
        return $this->modified_time=$modified_time;
    }	    
    
   public function getLeaseStartDate()
    {
        return $this->lease_start_date;
    }	
	
   public function setLeaseStartDate($lease_start_date)
    {
        return $this->lease_start_date=$lease_start_date;
    }	    

   public function getLeaseEndDate()
    {
        return $this->lease_end_date;
    }	
	
   public function setLeaseEndDate($lease_end_date)
    {
        return $this->lease_end_date=$lease_end_date;
    }		
 
 
   public function getMinimumStayLength()
    {
        return $this->minimum_stay_length;
    }	
	
   public function setMinimumStayLength($minimum_stay_length)
    {
        return $this->minimum_stay_length=$minimum_stay_length;
    }
    
    
 
 
    
   public function getPropertyStatus()
    {
        return $this->property_status;
    }	
	
   public function setPropertyStatus($property_status)
    {
        return $this->property_status=$property_status;
    }		
    
}