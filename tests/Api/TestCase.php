<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * AbstractWebTestCase constructor.
     *
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct ( ?string $name = null, array $data = [], string $dataName = '' ) {
        parent::__construct( $name, $data, $dataName );
        if ( !static::$booted ) {
            $this->client = static::createClient();
            $this->token  = self::getToken();
            $this->faker  = Factory::create();
        }
    }

    /**
     * @test
     */
    protected static function getToken (): ?string {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        $data      = [ 'username' => 'test@symfony.local', 'roles' => [ 'ROLE_ADMIN' ] ];

        self::createUser( 'admin', 'admin', $data[ 'username' ], '77713602692', [ 'ROLE_ADMIN' ] );

        try {
            $token = $container
                ->get( 'lexik_jwt_authentication.encoder' )
                ->encode( $data );
        } catch ( JWTEncodeFailureException $e ) {
            echo $e->getMessage() . PHP_EOL;
        }

        return $token;
    }

    public static function createUser ( string $firstName = '', string $lastName = '', string $email = '', string $phoneNumber = '', array $roles = [] ) {
        $faker = Factory::create();

        $firstName   = $firstName ? : $faker->firstName();
        $email       = $email ? : $faker->firstName();
        $phoneNumber = $phoneNumber ? : $faker->firstName();

        $kernel = self::bootKernel();
        /**@var UserRepository $repo * */

        $repo = $kernel->getContainer()
                       ->get( 'doctrine' )
                       ->getManager()->getRepository( User::class );

        if ( !$repo->findBy( [ 'email' => $email ] ) ) {
          return  $repo->saveUser( $firstName, $lastName, $email, $phoneNumber, $roles );
        }
    }
}