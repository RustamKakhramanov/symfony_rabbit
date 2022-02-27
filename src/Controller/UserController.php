<?php

namespace App\Controller;

use App\DTOs\Request\UserRequestRequestDTO;
use App\DTOs\Resources\UserResource;
use App\Repository\UserRepository;
use App\Rules\UserRules;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct ( UserRepository $UserRepository, ValidatorInterface $validator ) {
        $this->userRepository = $UserRepository;
        $this->validator      = $validator;
    }

    /**
     * @Route("/users/", name="add_user", methods={"GET"})
     */
    public function index () {
        $users = $this->userRepository->findAll();

        return new JsonResponse( UserResource::forList( $users ), Response::HTTP_OK );
    }

    /**
     * @Route("/users/", name="add_user", methods={"POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function add ( Request $request ): JsonResponse {
        $dto         = new UserRequestRequestDTO( $request );
        $constraints = UserRules::getRules();
        $errors      = $this->validator->validate( $dto->toArray(), $constraints );

        if ( $errors->count() > 0 ) {
            throw new BadRequestHttpException( (string) $errors );
        }

        $user  =  $this->userRepository->saveuser( $dto->firstName, $dto->lastName, $dto->email, $dto->phoneNumber );

        return new JsonResponse( new UserResource( $user ), Response::HTTP_CREATED );
    }


    /**
     * @Route("/users/{id}", name="get_one_user", methods={"GET"})
     */
    public function show ( $id ): JsonResponse {
        $user = $this->userRepository->findOneBy( [ 'id' => $id ] );

        return new JsonResponse( new UserResource( $user ), Response::HTTP_OK );
    }


    /**
     * @Route("/customers/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function delete ( $id ): JsonResponse {
        $customer = $this->userRepository->findOneBy( [ 'id' => $id ] );
        $this->userRepository->removeUser( $customer );

        return new JsonResponse( [ 'status' => 'Customer deleted' ], Response::HTTP_NO_CONTENT );
    }
}