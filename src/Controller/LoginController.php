<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;

#[Route('/login', name: 'api_alfareria_login', methods: ['POST'])]
class LoginController extends AbstractController
{
    private UsuariosRepository $usuariosRepository;

    public function __construct(UsuariosRepository $usuariosRepo)
    {
        $this->usuariosRepository = $usuariosRepo;
    }

    #[Route('/', name: 'api_alfareria_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        // Obtener los datos del JSON del cuerpo de la solicitud
        $data = json_decode($request->getContent(), true);
                 
            $usuario = $this->usuariosRepository->findOneBy(['email' => $data['username']]);
    
            if ($usuario->getPassword() == $data['password']) {
                $mensaje = true;
            }
            else {
                $mensaje = false;
            }
        
        return new JsonResponse($mensaje);
    }
}
