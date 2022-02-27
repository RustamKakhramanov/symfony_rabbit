<?php

namespace App\Tests\Queue;

use App\Message\UserNotification;
use App\MessageHandler\UserNotificationHandler;
use App\Tests\Api\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class MessagesTest extends TestCase
{
    /**
     * @var MessageBusInterface|MockObject
     */
    private $messageBus;

    public function __construct ( ?string $name = null, array $data = [], string $dataName = '' ) {
        parent::__construct( $name, $data, $dataName );
        $this->messageBus = $this->getMockBuilder( MessageBusInterface::class )
                                 ->disableOriginalConstructor()
                                 ->getMock();
    }

    /**
     * @test
     */
    public function testCreateNotifyAction () {
        $this->createMock( UserNotificationHandler::class );
        $channels = [ 'sms', 'email' ];

        $usersContent = [];

        for ( $i = 0; $i <= 5; $i++ ) {
            $user           = self::createUser();
            $usersContent[] = [
                'content' => $this->faker->text( 50 ),
                'id'      => $user->getId(),
                'channel' => array_rand( $channels )
            ];
        }

        $this->client->setServerParameter( 'X-AUTH-TOKEN', env( 'PRIVATE_TOKEN' ) );
        $this->client->request(
            Request::METHOD_POST,
            '/users/',
            [],
            [],
            [],
            json_encode( $usersContent )
        );

        $message = new UserNotification( $usersContent );

        $this->messageBus->expects( self::exactly( 1 ) )
                         ->method( 'dispatch' )
                         ->withConsecutive(
                             (array) self::isInstanceOf( UserNotification::class ),
                         )
                         ->willReturn( new Envelope( $message ) );
    }

}