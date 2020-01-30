<?php
// src/AppBundle/Entity/AdminBlogCategory.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminBlogCategory
*
* @ORM\Table(name="blog_category")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminBlogCategoryRepository")
*/

class AdminBlogCategory
{
	
/**
    * @var int
    *
    * @ORM\Column(name="category_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $category_id;
  
   /**
    * @var string
    *
    * @ORM\Column(name="category_title", type="string", length=150)
    */
   private $category_title;
   

   /**
    * @var string
    *
    * @ORM\Column(name="description", type="string", length=150)
    */
   private $category_description;

   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=150)
    */
   private $ip_address;

      
  
   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
		
 
    public function getCategoryId()
    {
        return $this->category_id;
    }	
	  
	
   public function getBlogCategoryTitle()
    {
        return $this->category_title;
    }

  public function setBlogCategoryTitle($category_title)
    {
        return $this->category_title=$category_title;
    }

   public function getBlogCategoryDescription()
    {
        return $this->category_description;
    }

  public function setBlogCategoryDescription($category_description)
    {
        return $this->category_description=$category_description;
    }

   public function getEntryTime()
    {
        return $this->entry_time;
    }	

   public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }		
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		
		
 }