<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminUsersSuggestionsRepository extends EntityRepository
{

		
 public function CountPropertySuggestions($property_id)
    {
     return $this->createQueryBuilder('users_suggestions')->select('count(users_suggestions.suggestion_id)')
	 									->where('users_suggestions.property_id = :property_id')
										->setParameters(['property_id' => $property_id])
										->orderBy('users_suggestions.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();
    }


 public function CountUserSuggestions($user_id)
    {
     return $this->createQueryBuilder('users_suggestions')->select('count(users_suggestions.suggestion_id)')
	 									->where('users_suggestions.user_id = :user_id')
										->setParameters(['user_id' => $user_id])
										->orderBy('users_suggestions.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();
    }
    
    
 public function PropertyUsersSuggestions($property_id)
    {
       $query_result = $this->createQueryBuilder('users_suggestions')
	        ->where('users_suggestions.property_id = :property_id')
			->setParameters(['property_id' => $property_id])
            ->orderBy('users_suggestions.entry_time', 'DESC')
            ->getQuery()->getResult();
	  return $query_result;
    }


 public function AllUsersSuggestions($currentPage = 1,$limit,$user_id)
    {
       $query_result = $this->createQueryBuilder('users_suggestions')
              ->where('users_suggestions.user_id = :user_id')
			 ->setParameters(['user_id' => $user_id])
	         ->orderBy('users_suggestions.entry_time', 'DESC')
			 ->getQuery();
	   $paginator = $this->paginate($query_result, $currentPage, $limit);
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
  
 public function AllPropertySuggestions($limit)
    {
       $query_result = $this->createQueryBuilder('users_suggestions')
	         ->orderBy('users_suggestions.entry_time', 'DESC')
			 ->setMaxResults($limit)             
             ->getQuery()->getResult();
	  return $query_result;
    }
}
