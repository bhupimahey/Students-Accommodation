<?php
// src/AppBundle/Entity/AdminBlog.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminBlog
*
* @ORM\Table(name="blog")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminBlogRepository")
*/

class AdminBlog
{
/**
    * @var int
    *
    * @ORM\Column(name="blog_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $id;
 
	 
   /**
    * @var string
    *
    * @ORM\Column(name="blog_url_title", type="string", length=150)
    */
   private $blog_url_title;
   

   /**
    * @var string
    *
    * @ORM\Column(name="blog_title", type="string", length=150)
    */
   private $blog_title;
      
   /**
    * @var string
    *
    * @ORM\Column(name="image_path", type="string", length=100)
    */	
   private $image_path;
 
   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;
 


 /**
    * @var int
    *
    * @ORM\Column(name="category_id", type="integer", length=10)
    */
   private $category_id;


    /**
    * @var string
    *
    * @ORM\Column(name="blog_desc", type="text")
    */	
   private $blog_desc;


   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;
		
 
    public function getId()
    {
        return $this->id;
    }	
	  

   public function getBlogUrlTitle()
    {
        return $this->blog_url_title;
    }

  public function setBlogUrlTitle($blog_url_title)
    {
        return $this->blog_url_title=$blog_url_title;
    }

   public function getBlogPhoto()
    {
        return $this->image_path;
    }

  public function setBlogPhoto($image_path)
    {
        return $this->image_path=$image_path;
    }


  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }



   public function getBlogTitle()
    {
        return $this->blog_title;
    }
	
   public function setBlogTitle($blog_title)
    {
        return $this->blog_title=$blog_title;
    }

   public function getBlogDescription()
    {
        return $this->blog_desc;
    }
	
   public function setBlogDescription($blog_desc)
    {
        return $this->blog_desc=$blog_desc;
    }

  public function getBlogCategory()
    {
        return $this->category_id;
    }
	
   public function setBlogCategory($category_id)
    {
        return $this->category_id=$category_id;
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