<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminGalleryRepository extends EntityRepository
{

 public function CountGallery()
    {
     return $this->createQueryBuilder('gallery')->select('count(gallery.id)')
	                                              ->orderBy('gallery.entry_time', 'DESC')
											      ->getQuery()->getSingleScalarResult();
    }
		
 public function AllImages($currentPage = 1,$limit)
    {
      $query = $this->createQueryBuilder('gallery')->orderBy('gallery.entry_time', 'DESC')->getQuery();			
	   $paginator = $this->paginate($query, $currentPage, $limit);
       return $paginator;
			
    }
	
 public function paginate($dql, $page = 1, $limit)
   {
    $paginator = new Paginator($dql);
    $paginator->getQuery()
        ->setFirstResult($limit * ($page - 1)) // Offset
        ->setMaxResults($limit); // Limit
    return $paginator;
   }

}