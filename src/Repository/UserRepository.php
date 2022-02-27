<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find( $id, $lockMode = null, $lockVersion = null )
 * @method User|null findOneBy( array $criteria, array $orderBy = null )
 * @method User[]    findAll()
 * @method User[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class UserRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry        $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct( $registry, User::class );
        $this->manager = $manager;
    }

    public function saveUser ( string $firstName, string $lastName, string $email, string $phoneNumber, array $roles = [] ) {
        $newUser = new User();

        $newUser
            ->setFirstName( $firstName )
            ->setLastName( $lastName )
            ->setEmail( $email )
            ->setPhoneNumber( $phoneNumber )
            ->setRoles( $roles );

        $this->manager->persist( $newUser );
        $this->manager->flush();

        return $newUser;
    }

    public function removeUser ( User $User ) {
        $this->manager->remove( $User );
        $this->manager->flush();
    }

}
