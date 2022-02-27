<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare( strict_types=1 );

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    protected UserRepository $repository;

    /**
     * UserProvider constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct ( UserRepository $repository ) {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername ( $username ) {
        $user = $this->findUser( $username );

        if ( false === ! !$user ) {
            throw new UsernameNotFoundException( sprintf( 'Username "%s" does not exist.', $username ) );
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser ( UserInterface $user ): ?UserInterface {
        /** @var User $user */
        if ( !$this->supportsClass( \get_class( $user ) ) ) {
            throw new UnsupportedUserException( sprintf( 'Expected an instance of %s, but got "%s".', get_class( $this->repository ), \get_class( $user ) ) );
        }

        if ( null === $reloadedUser = $this->repository->findOneBy( [ 'id' => $user->getId() ] ) ) {
            throw new UsernameNotFoundException( sprintf( 'User with ID "%s" could not be reloaded.', $user->getId() ) );
        }

        if ( $reloadedUser instanceof UserInterface ) {
            return $reloadedUser;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass ( $class ) {
        $userRepositoryClass = get_class( $this->repository );
        $userClass           = User::class;

        return $userRepositoryClass === $class
               || is_subclass_of( $class, $userRepositoryClass )
               || $userClass === $class
               || is_subclass_of( $class, $userClass );
    }

    /**
     * Finds a user by username.
     *
     * This method is meant to be an extension point for child classes.
     *
     * @param string $email
     *
     * @return User
     */
    protected function findUser ( string $email ) {
        return $this->repository->findOneBy( [ 'email' => $email ] );
    }
}
