<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Repository\PedidosRepository;
use App\Repository\UsuariosRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pedidos', name: 'api_alfareria_pedidos')]
class PedidosController extends AbstractController
{
    private PedidosRepository $pedidosRepository;
    private UsuariosRepository $usuariosRepository;

    public function __construct(PedidosRepository $pedidosRepo, UsuariosRepository $usuariosRepo)
    {
        $this->pedidosRepository = $pedidosRepo;
        $this->usuariosRepository = $usuariosRepo;

    }

    #[Route('/', name: 'api_alfareria_pedidos_showAll', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $pedidos = $this->pedidosRepository->findAll();
        $data = [];

        foreach ($pedidos as $pedido) {
            $data[] = [
                'id' => $pedido->getId(),
                'fecha' => $pedido->getFecha(),
                'total' => $pedido->getTotal(),
                'estado' => $pedido->getEstado(),
                'usuario' => [
                    'id' => $pedido->getIdUsuario()->getId(),
                    'nombre' => $pedido->getIdUsuario()->getNombre(),
                    'email' => $pedido->getIdUsuario()->getEmail()
                ]
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_alfareria_pedidos_show', methods: ['GET'])]
    public function show(Pedidos $pedido): JsonResponse
    {
        $data[] = [
            'id' => $pedido->getId(),
            'fecha' => $pedido->getFecha(),
            'total' => $pedido->getTotal(),
            'estado' => $pedido->getEstado(),
            'usuario' => [
                'id' => $pedido->getIdUsuario()->getId(),
                'nombre' => $pedido->getIdUsuario()->getNombre(),
                'email' => $pedido->getIdUsuario()->getEmail()
            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/new/', name: 'api_alfareria_pedidos_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $usuario = $this->usuariosRepository->find($data->id_usuario);
        $pedido = new Pedidos();
        $pedido->setFecha($data->fecha)
            ->setTotal($data->total)
            ->setEstado($data->estado)
            ->setIdUsuario($usuario);

        $this->pedidosRepository->save($pedido, true);

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/edit/{id}', name: 'api_alfareria_pedidos_edit', methods: ['PUT', 'PATCH'])]
    public function edit($id, Request $request): JsonResponse
    {
        $pedido = $this->pedidosRepository->find($id);
        $data = json_decode($request->getContent());

        empty($data->fecha) ? true : $pedido->setFecha($data->fecha);
        empty($data->total) ? true : $pedido->setTotal($data->total);
        empty($data->estado) ? true : $pedido->setEstado($data->estado);
        $this->pedidosRepository->save($pedido, true);

        $mensaje = "Pedido " . $pedido->getId() . " actualizado";

        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: "api_alfareria_pedidos_delete", methods: ['DELETE'])]
    public function remove(Pedidos $pedido): JsonResponse
    {
        $this->pedidosRepository->remove($pedido, true);
        return new JsonResponse(['status' => 'Pedido ' . $pedido->getId() . ' borrado'], Response::HTTP_OK);
    }
}
