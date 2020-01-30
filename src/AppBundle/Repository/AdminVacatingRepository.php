<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminVacatingRepository extends EntityRepository
{


 
  public function LatestVacatingRequest($user_id,$property_id)
    {
       $query = $this->createQueryBuilder('vacating_lists')
             ->where('vacating_lists.user_id = :user_id')
              ->Andwhere('vacating_lists.vacating_property_id = :property_id')
             ->setParameters(['user_id'=>$user_id,'property_id'=>$property_id])
             ->orderBy('vacating_lists.vacating_id', 'DESC')
			 ->setMaxResults(1)             
             ->getQuery()->getResult();
      return $query;
    }
    
    
    
     public function CheckVacatingExists($user_id)
    {
    $query =  $this->createQueryBuilder('vacating_lists')->select('vacating_lists.vacating_id')
	 									->where('vacating_lists.user_id = :user_id')
	 									->setParameters(['user_id' => $user_id])
								     	->orderBy('vacating_lists.vacating_end_date','DESC')
										 ->setMaxResults(1)             
                                         ->getQuery()->getResult();
         return $query;									
    }
    
    
		
 public function CountVacating($search_property_by)
    {
    if($search_property_by!='') {
   
     $query =  $this->createQueryBuilder('vacating_lists')->select('count(vacating_lists.vacating_id)')
	 									->where('vacating_lists.vacating_property_id = :vacating_property_id')
	 									->setParameters(['vacating_property_id' => $search_property_by])
								     	->orderBy('vacating_lists.vacating_end_date','DESC')
										->getQuery()->getSingleScalarResult();
										
        
    }     
    else{    
     return $this->createQueryBuilder('vacating_lists')->select('count(vacating_lists.vacating_id)')
	 									->orderBy('vacating_lists.vacating_end_date','DESC')
										->getQuery()->getSingleScalarResult();
       }									
    }
 
 
 
  public function CountUsersVacating($user_id,$search_vacating_by='DESC')
    {
     $query =  $this->createQueryBuilder('vacating_lists')->select('count(vacating_lists.vacating_id)')
	 									->where('vacating_lists.user_id = :vacating_user_id')
	 									->setParameter('vacating_user_id', $user_id)
								     	->orderBy('vacating_lists.vacating_end_date', $search_vacating_by)
										->getQuery()->getSingleScalarResult();
									
    }

  public function AllUsersVacating($currentPage = 1,$limit,$user_id,$search_vacating_by='DESC')
    {
   
       $query = $this->createQueryBuilder('vacating_lists')
										->where('vacating_lists.user_id = :vacating_user_id')
	 									->setParameters(['vacating_user_id' => $user_id])
								     	->orderBy('vacating_lists.vacating_end_date', $search_vacating_by)
										->getQuery();		
	   $paginator = $this->paginate($query, $currentPage, $limit);
      return $paginator;
    }
	
	 
 	
  public function AllVacating($currentPage = 1,$limit,$search_property_by)
    {
    if($search_property_by!='') {    
        
       $query = $this->createQueryBuilder('vacating_lists')
										->where('vacating_lists.vacating_property_id = :vacating_property_id')
	 									->setParameters(['vacating_property_id' => $search_property_by])
								     	->orderBy('vacating_lists.vacating_end_date','DESC')
										->getQuery();		
										
    }
    else{
       $query = $this->createQueryBuilder('vacating_lists')
										->orderBy('vacating_lists.vacating_end_date','DESC')
										->getQuery();			  
        
    }
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
