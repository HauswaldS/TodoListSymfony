<?php

namespace TL\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    // All the functions need to be refined to avoid duplicate code

    public function UserFoldersWithTasks($userId)
    {
        return $userFoldersWithTasks = $this
                                ->createQueryBuilder('u')
                                ->where('u.id = :userId')
                                ->setParameter('userId', $userId)
                                ->leftJoin('u.folders', 'f')
                                ->addSelect('f')
                                ->leftJoin('f.tasks', 't')
                                ->addOrderBy('t.priority', 'DESC')
                                ->addOrderBy('t.createdAt', 'ASC')
                                ->addSelect('t')
                                ->getQuery()
                                ->getSingleResult();
    }

    public function FoldersWithTasksPrioritary($userId)
    {
        return $userFoldersWithTasksPrioritary = $this
                                ->createQueryBuilder('u')
                                ->where('u.id = :userId')
                                ->setParameter('userId', $userId)
                                ->leftJoin('u.folders', 'f')
                                ->addSelect('f')
                                ->leftJoin('f.tasks', 't')
                                ->AndWhere('t.priority = :true')
                                ->setParameter(':true', true)
                                ->orderBy('t.createdAt', 'ASC')
                                ->addSelect('t')
                                ->getQuery()
                                ->getOneOrNullResult();
    }

    public function FoldersWithTasksDone($userId)
    {
        return $foldersWithTasksDone = $this
                                ->createQueryBuilder('u')
                                ->where('u.id = :userId')
                                ->setParameter('userId', $userId)
                                ->leftJoin('u.folders', 'f')
                                ->addSelect('f')
                                ->leftJoin('f.tasks', 't')
                                ->AndWhere('t.status = :true')
                                ->setParameter(':true', true)
                                ->orderBy('t.createdAt', 'DESC')
                                ->addSelect('t')
                                ->getQuery()
                                ->getOneOrNullResult();
    }
}
