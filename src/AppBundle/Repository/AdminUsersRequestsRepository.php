<?php

namespace AppBundle\Repository;



use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Tools\Pagination\Paginator;



class AdminUsersRequestsRepository extends EntityRepository

{



 public function CountUserRequests($user_id)

    {

     return $this->createQueryBuilder('users_requests')->select('count(users_requests.srequest_id)')
										->where('users_requests.user_id = :user_id')
                                        ->setParameter('user_id',$user_id)
	 									->orderBy('users_requests.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();

    }
	
 public function AllUsersRequests($currentPage = 1,$limit,$user_id)

    {

       $query = $this->createQueryBuilder('users_requests')
			 ->where('users_requests.user_id = :user_id')
             ->setParameter('user_id',$user_id)
	         ->orderBy('users_requests.entry_time', 'DESC')
			 ->setMaxResults($limit)             
             ->getQuery();

	   $paginator = $this->paginate($query, $currentPage, $limit);

      return $paginator;

    }
	
			

 public function CountRequests()

    {

     return $this->createQueryBuilder('users_requests')->select('count(users_requests.srequest_id)')
									
	 									->orderBy('users_requests.entry_time', 'DESC')

										->getQuery()->getSingleScalarResult();

    }



 public function AllRequests($currentPage = 1,$limit)

    {

       $query = $this->createQueryBuilder('users_requests')

	         ->orderBy('users_requests.entry_time', 'DESC')

			 ->setMaxResults($limit)             

             ->getQuery();

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

