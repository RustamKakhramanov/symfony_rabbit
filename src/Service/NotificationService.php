<?php

namespace App\Service;

use App\DTOs\NotificatorDTO;
use App\DTOs\Request\UserNotificationDTO;
use App\Enums\NotificatorEnum;
use App\Repository\UserRepository;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class NotificationService
{
    private UserRepository $userRepository;
    private SmsService $smsService;
    private EmailService $emailService;

    public function __construct ( UserRepository $userRepository, SmsService $smsService, EmailService $emailService ) {
        $this->userRepository = $userRepository;
        $this->smsService     = $smsService;
        $this->emailService   = $emailService;
    }


    public function handle ( $usersDTOs ) {
        $users = $this->getUserObjects( $usersDTOs );
        $items = $this->resolveForNotificator( $users, $usersDTOs );
        $this->send( $items );
    }

    public function getUserObjects ( $usersDTOs ) {
        $criterias = array_map( function ( UserNotificationDTO $user ) {
            return [ 'id' => $user->id ];
        }, $usersDTOs );

        return $this->userRepository->findBy( $criterias );
    }

    public function resolveForNotificator ( $users, $usersDTOs ) {
        $items = [];

        foreach ( $users as $item ) {
            //         $dto = array_search( $item->id, array_column($usersDTOs, 'id'));
            $dto = $this->getUserDTOById( $item->id, $usersDTOs );

            $items[ $item->channel ][] = new  NotificatorDTO( $item, $dto->content, $dto->type );
        }

        return $items;
    }

    public function getUserDTOById ( $id, $items ) {
        foreach ( $items as $item ) {
            if ( $item->id === $id ) {
                return $item;
            }
        }
    }

    public function send ( $items ) {
        foreach ( $items as $key => $items ) {
            /**@var \App\Interfaces\NotificationService $worker*/
            if ( $worker = NotificatorEnum::get( $key ) ) {
                $worker::handle($items);
            }
        }
    }
}