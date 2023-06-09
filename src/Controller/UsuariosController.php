<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/usuarios', name: 'api_alfareria_usuarios')]
class UsuariosController extends AbstractController
{

    private UsuariosRepository $usuariosRepository;

    public function __construct(UsuariosRepository $usuariosRepo)
    {
        $this->usuariosRepository = $usuariosRepo;
    }

    #[Route('/', name: 'api_alfareria_usuarios_showAll', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $usuarios = $this->usuariosRepository->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
            $pedidos = [];
            foreach ($usuario->getPedidos() as $pedido) {
                // var_dump($pedido);
                $pedidos[] = [
                    'id' => $pedido->getId(),
                    'fecha' => $pedido->getFecha(),
                    'total' => $pedido->getTotal(),
                    'estado' => $pedido->getEstado(),
                    // Agregar cualquier otra información que desee mostrar
                ];
            }
            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'password' => $usuario->getPassword(),
                'es_admin' => $usuario->isEsAdmin(),
                'pedidos' => $pedidos
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_alfareria_usuarios_show', methods: ['GET'])]
    public function show(Usuarios $usuario): JsonResponse
    {
        $pedidos = [];
            foreach ($usuario->getPedidos() as $pedido) {
                // var_dump($pedido);
                $pedidos[] = [
                    'id' => $pedido->getId(),
                    'fecha' => $pedido->getFecha(),
                    'total' => $pedido->getTotal(),
                    'estado' => $pedido->getEstado(),
                    // Agregar cualquier otra información que desee mostrar
                ];
            }
        $data[] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'email' => $usuario->getEmail(),
            'password' => $usuario->getPassword(),
            'es_admin' => $usuario->isEsAdmin(),
            'pedidos' => $pedidos
        ];


        return new JsonResponse($data, Response::HTTP_OK);
    }
    // #[Route('/{id}', name: 'api_alfareria_usuarios_show', methods: ['GET'])]
    // public function show(int $id): JsonResponse
    // {
    //     $usuario = $this->usuariosRepository->find($id);

    //     if (!$usuario) {
    //         return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    //     }

    //     $data = [
    //         'id' => $usuario->getId(),
    //         'nombre' => $usuario->getNombre(),
    //         'email' => $usuario->getEmail(),
    //         'password' => $usuario->getPassword(),
    //         'es_admin' => $usuario->isEsAdmin()
    //     ];

    //     return new JsonResponse($data, Response::HTTP_OK);
    // }

    #[Route('/new/', name: 'api_alfareria_usuarios_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent());

        $usuario = new Usuarios();
        $usuario->setNombre($data->nombre)
            ->setEmail($data->email)
            ->setPassword($data->password)
            ->setEsAdmin($data->es_admin);

        $this->usuariosRepository->save($usuario, true);

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/edit/{id}', name: 'api_alfareria_usuarios_edit', methods: ['PUT','PATCH'])]
    public function edit($id, Request $request): JsonResponse
    {
        $usuario = $this->usuariosRepository->find($id);
        $data = json_decode($request->getContent());

        empty($data->nombre) ? true : $usuario->setNombre($data->nombre);
        empty($data->email) ? true : $usuario->setEmail($data->email);
        empty($data->password) ? true : $usuario->setPassword($data->password);
        empty($data->setEsAdmin) ? true : $usuario->setEsAdmin($data->setEsAdmin);
        $this->usuariosRepository->save($usuario, true);

        $mensaje = "Usuario ".$data->nombre." actualizado"; 

        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: "api_alfareria_usuarios_delete", methods: ['DELETE'])]
    public function remove(Usuarios $usuario): JsonResponse
    {
        $usuarioNombre = $usuario->getNombre();
        $this->usuariosRepository->remove($usuario, true);
        return new JsonResponse(['status' => 'Usuario ' . $usuarioNombre . ' borrado'], Response::HTTP_OK);
    }
}
