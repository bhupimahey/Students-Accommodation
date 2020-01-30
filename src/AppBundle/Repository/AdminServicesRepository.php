<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminServicesRepository extends EntityRepository
{

 public function CountServices()
    {
     return $this->createQueryBuilder('services')->select('count(services.service_id)')
	                                              ->orderBy('services.entry_time', 'DESC')
											      ->getQuery()->getSingleScalarResult();
    }
		
 public function AllServices($currentPage = 1,$limit)
    {
      $query = $this->createQueryBuilder('services')->orderBy('services.entry_time', 'DESC')->getQuery();			
	   $paginator = $this->paginate($query, $currentPage, $limit);
       return $paginator;
			
    }


 public function ServicesArrayList()
    {
       $query = $this->createQueryBuilder('services')->orderBy('services.service_title', 'ASC')->getQuery()->getResult();			
	   $final_array=array();	
	   $final_array['Choose Service']='';	
	   if($query){
		   foreach($query as $querykey => $queryresult){
			 $final_array[$queryresult->getServiceTitle()]=$queryresult->getId(); 
		   }
	   }
      return $final_array;
			
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