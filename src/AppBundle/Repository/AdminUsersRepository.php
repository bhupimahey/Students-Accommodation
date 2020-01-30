<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminUsersRepository extends EntityRepository
{
 
  public function CountActiveTenants($filter2_value='')
    {
    if($filter2_value!=''){
    return $this->createQueryBuilder('users')->select('count(users.account_id)')
                                       	->where('users.assigned_property_id = :assigned_property_id')
	 									->Andwhere('users.account_status = :search_user1 AND users.assigned_property_id > :search_user2')
	 									->setParameter('assigned_property_id', $filter2_value)
                                      	->setParameter('search_user1', '1')
										->setParameter('search_user2', '0')
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();    
        
        
    }   
    else{
    return $this->createQueryBuilder('users')->select('count(users.account_id)')
	 									->where('users.account_status = :search_user1 AND users.assigned_property_id > :search_user2')
                                      	->setParameter('search_user1', '1')
										->setParameter('search_user2', '0')
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();    
        
    }
     
    }

 public function AllActiveTenants($currentPage = 1,$limit,$filter2_value='')
    {
     if($filter2_value!=''){
     $query= $this->createQueryBuilder('users')
                                        ->where('users.assigned_property_id = :assigned_property_id')
	 									->Andwhere('users.account_status = :search_user1 AND users.assigned_property_id > :search_user2')
	 									->setParameter('assigned_property_id', $filter2_value)
                                      	->setParameter('search_user1', '1')
										->setParameter('search_user2', '0')
										->orderBy('users.entry_time', 'DESC')
										->getQuery();    
     }
     else{
    $query= $this->createQueryBuilder('users')
	 									->where('users.account_status = :search_user1 AND users.assigned_property_id > :search_user2')
                                      	->setParameter('search_user1', '1')
										->setParameter('search_user2', '0')
										->orderBy('users.entry_time', 'DESC')
										->getQuery();
     }					
    $paginator = $this->paginate($query, $currentPage, $limit);
    return $paginator;									
  }
  
 public function CountUsers($search_users='',$filter2_value='',$filter3_value='')
    {
        $query =  $this->createQueryBuilder('users')->select('count(users.account_id)')
	 									->where('users.account_type = :account_type')
                                        ->setParameter('account_type','2');
	 									if($search_users!=''){
                                        $query->Andwhere('users.mobile LIKE :search_user OR users.full_name LIKE :search_user OR users.account_username LIKE :search_user')
	 									->setParameter('search_user', '%'.$search_users.'%');
                                        }
	 									if($filter2_value!=''){
	 									$query->Andwhere('users.assigned_property_id = :assigned_property_id')
	 									->setParameter('assigned_property_id', $filter2_value);
	 									}
	 									if($filter3_value=='1'){
	 									 $query->Andwhere('users.account_status = :account_status')
										 ->setParameter('account_status','1');
	 									}
	 								   else if($filter3_value=='0'){
	 									 $query->Andwhere('users.account_status = :account_status')
										 ->setParameter('account_status','0');
	 									}
										 
   
     $query->orderBy('users.entry_time', 'DESC');
     $count=   $query->getQuery()->getSingleScalarResult();  
   return $count;
										
    }

  public function AllUsers($currentPage = 1,$limit,$search_users='',$filter2_value='',$filter3_value='')
    {
     $query = $this->createQueryBuilder('users')
                                        ->where('users.account_type = :account_type')
                                        ->setParameter('account_type','2');
                                        if($search_users!=''){
                                        $query->Andwhere('users.mobile LIKE :search_user OR users.full_name LIKE :search_user OR users.account_username LIKE :search_user')
	 									->setParameter('search_user', '%'.$search_users.'%');
                                        }
	 									if($filter2_value!=''){
	 									$query->Andwhere('users.assigned_property_id = :assigned_property_id')
	 									->setParameter('assigned_property_id', $filter2_value);
	 									}
	 									if($filter3_value=='1'){
	 									  
	 									 $query->Andwhere('users.account_status = :account_status')
										 ->setParameter('account_status','1');
	 									}
	 								 else   if($filter3_value=='0'){
	 									 $query->Andwhere('users.account_status = :account_status')
										 ->setParameter('account_status','0');
	 									}	
							$query->orderBy('users.entry_time', 'DESC')
										->getQuery();
	  $paginator = $this->paginate($query, $currentPage, $limit);
      return $paginator;
    }
    
 public function PropertyActiveTenants($property_id)
    {
     return $this->createQueryBuilder('users')->select('users.account_id,users.full_name,users.email_id,users.mobile,users.assigned_property_id')
	 									->where('users.assigned_property_id = :assigned_property_id')
	 									->Andwhere('users.account_status = :search_user1 AND users.assigned_property_id > :search_user2')
                                      	->setParameters(['assigned_property_id' => $property_id])
										->setParameter('search_user1', '1')
										->setParameter('search_user2', '0')
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getResult();
    }

 public function PropertyAssigned($property_id)
    {
     return $this->createQueryBuilder('users')->select('count(users.account_id)')
	 									->where('users.assigned_property_id = :assigned_property_id')
										->setParameters(['assigned_property_id' =>$property_id])
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getSingleScalarResult();
									
    }


  public function UserInfo($user_id)
    {
     $user_info =  $this->createQueryBuilder('users')
										->Where('users.account_id = :account_id')
										->setParameters(['account_id' => $user_id])
										->orderBy('users.entry_time', 'DESC')
										->getQuery()->getResult();
    if($user_info && isset($user_info[0]))
     return $user_info[0];
    }


 public function OldPasswordCheck($old_password,$user_id)
    {
     return $this->createQueryBuilder('users')->select('count(users.account_id)')
	 									->where('users.account_id = :account_id')
										->setParameters(['account_id' =>$user_id])
	 									->where('users.account_key = :account_key')
										->setParameters(['account_key' =>md5($old_password)])
										->getQuery()->getSingleScalarResult();
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
