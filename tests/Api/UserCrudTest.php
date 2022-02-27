<?php

namespace App\Tests\Api;

use App\Enums\NotificatorEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCrudTest extends TestCase
{

    /**
     * @var int
     */
    protected static $entityId;

    protected static $email;

    /**
     * @test
     */
    public function testCreateAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );
        $this->client->request(
            Request::METHOD_POST,
            '/users/',
            [],
            [],
            [],
            json_encode( [
                'firstName'     => 'User',
                'lastName'      => 'Test User',
                'email'         => $this->faker->email(),
                'phoneNumber'   => $this->faker->phoneNumber(),
                'plainPassword' => 'test',
            ] )
        );

        $this->assertEquals( Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseContent = json_decode( $this->client->getResponse()->getContent(), true );

        $this->assertArrayHasKey( 'id', $responseContent );

        self::$entityId = $responseContent[ 'id' ];
    }

    /**
     * @test
     */
    public function testBadRequestCreateAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request(
            Request::METHOD_POST,
            '/users',
            [],
            [],
            [],
            json_encode( [
                'fullName'      => 'Test User',
                'email'         => self::$email,
                'plainPassword' => 'test',
            ] )
        );

        $this->assertEquals( Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * @test
     */
    public function testUnauthorizedUpdateAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        self::$email = $this->faker->email;

        $this->client->request(
            Request::METHOD_PATCH,
            sprintf( '/users/%d', self::$entityId ),
            [],
            [],
            [],
            json_encode( [
                'email' => self::$email,
            ] )
        );

        $this->assertEquals( Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * @test
     */
    public function testUpdateAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request(
            Request::METHOD_PATCH,
            sprintf( '/users/%d', self::$entityId ),
            [],
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token ],
            json_encode( [
                'email' => self::$email,
            ] )
        );

        $this->assertEquals( Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseContent = json_decode( $this->client->getResponse()->getContent(), true );

        $this->assertSame( 'email', array_search( self::$email, $responseContent ) );
    }

    /**
     * @test
     */
    public function testNotFoundUpdateAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request(
            Request::METHOD_PATCH,
            '/users/0',
            [],
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token ],
            json_encode( [
                'email' => self::$email,
            ] )
        );

        $this->assertEquals( Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * @test
     */
    public function testListAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request( Request::METHOD_GET, '/users' );

        $this->assertEquals( Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains( 'users', $this->client->getResponse()->getContent() );
    }

    /**
     * @test
     */
    public function testFilterListAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request( Request::METHOD_GET, sprintf( '/users?user_filter[email]=%s', self::$email ) );

        $this->assertEquals( Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains( 'users', $this->client->getResponse()->getContent() );

        $responseContent = json_decode( $this->client->getResponse()->getContent(), true );

        $this->assertSame( 'email', array_search( self::$email, $responseContent[ 'users' ][ 0 ] ) );
    }

    /**
     * @test
     */
    public function testShowAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request( Request::METHOD_GET, sprintf( '/users/%d', self::$entityId ) );

        $this->assertEquals( Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseContent = json_decode( $this->client->getResponse()->getContent(), true );

        $this->assertSame( 'id', array_search( self::$entityId, $responseContent ) );
    }

    /**
     * @test
     */
    public function testNotFoundShowAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request( Request::METHOD_GET, '/users/0' );

        $this->assertEquals( Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * @test
     */
    public function testUnauthorizedDeleteAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request( Request::METHOD_DELETE, sprintf( '/users/%d', self::$entityId ) );

        $this->assertEquals( Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * @test
     */
    public function testDeleteAction () {
        $this->client->setServerParameter( 'HTTP_Authorization', \sprintf( 'Bearer %s', $this->token ) );

        $this->client->request(
            Request::METHOD_DELETE,
            sprintf( '/users/%d', self::$entityId ),
            [],
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token ]
        );

        $this->assertEquals( Response::HTTP_OK, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * @test
     */
    public function testNotFoundDeleteAction () {
        $this->client->request(
            Request::METHOD_DELETE,
            '/users/0',
            [],
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token ]
        );

        $this->assertEquals( Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode() );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}