<?php

namespace App\Controller;

use App\Entity\LineasPedidos;
use App\Entity\Pedidos;
use App\Entity\Productos;

use App\Repository\LineasPedidosRepository;
use App\Repository\PedidosRepository;
use App\Repository\ProductosRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lineaspedidos', name: 'api_alfareria_lineasPedidos')]
class LineasPedidosController extends AbstractController
{
    private LineasPedidosRepository $lineasPedidosRepository;
    private PedidosRepository $pedidosRepository;
    private ProductosRepository $productosRepository;

    public function __construct(LineasPedidosRepository $lineasPedidosRepo, PedidosRepository $pedidosRepo, ProductosRepository $productosRepo)
    {
        $this->lineasPedidosRepository = $lineasPedidosRepo;
        $this->pedidosRepository = $pedidosRepo;
        $this->productosRepository = $productosRepo;
    }

    #[Route('/', name: 'api_alfareria_lineasPedidos_showAll', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $lineasPedidos = $this->lineasPedidosRepository->findAll();

        foreach($lineasPedidos as $lineaPedido)
        $data[] = [
                'id' => $lineaPedido->getId(),
                'producto' => [
                    'id' => $lineaPedido->getIdProducto()->getId(),
                    'nombre' => $lineaPedido->getIdProducto()->getNombre(),
                    'descripcion' => $lineaPedido->getIdProducto()->getDescripcion(),
                    'precio' => $lineaPedido->getIdProducto()->getPrecio(),    
                ],
                'cantidad' => $lineaPedido->getCantidad(),
                'precio' => $lineaPedido->getPrecio(),
                'pedidos' => [
                    'id' => $lineaPedido->getIdPedido()->getId(),
                    'total' => $lineaPedido->getIdPedido()->getTotal(), 
                ]
                 
            ];
        

        return new JsonResponse($data, Response::HTTP_OK);
    }


    #[Route('/new', name: 'api_alfareria_lineasPedidos_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $lineaPedido = new LineasPedidos();
        $lineaPedido->setCantidad($data['cantidad']);
        $lineaPedido->setPrecio($data['precio']);
        $lineaPedido->setIdProducto($this->productosRepository->find($data['idProducto']));
        $lineaPedido->setIdPedido($this->pedidosRepository->find($data['idPedido']));

        $this->lineasPedidosRepository->save($lineaPedido);

        return new JsonResponse([
            'message' => 'Linea de pedido creada correctamente.',
            'lineaPedido' => $lineaPedido,
        ], Response::HTTP_CREATED);
    }

    #[Route('/show/{id}', name: 'api_alfareria_lineasPedidos_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $lineaPedido = $this->lineasPedidosRepository->find($id);

        if (!$lineaPedido) {
            return new JsonResponse([
                'message' => 'La linea de pedido no existe.',
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'lineaPedido' => $lineaPedido,
        ], Response::HTTP_OK);
    }

    #[Route('/update/{id}', name: 'api_alfareria_lineasPedidos_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->request->all();

        $lineaPedido = $this->lineasPedidosRepository->find($id);

        if (!$lineaPedido) {
            return new JsonResponse([
                'message' => 'La linea de pedido no existe.',
            ], Response::HTTP_NOT_FOUND);
        }

        $lineaPedido->setCantidad($data['cantidad'] ?? $lineaPedido->getCantidad());
        $lineaPedido->setPrecio($data['precio'] ?? $lineaPedido->getPrecio());
        $lineaPedido->setIdProducto($data['idProducto'] ?? $lineaPedido->getIdProducto());
        $lineaPedido->setIdPedido($data['idPedido'] ?? $lineaPedido->getIdPedido());

        $this->lineasPedidosRepository->save($lineaPedido);

        return new JsonResponse([
            'message' => 'Linea de pedido actualizada correctamente.',
            'lineaPedido' => $lineaPedido,
        ], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'api_alfareria_lineasPedidos_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $lineaPedido = $this->lineasPedidosRepository->find($id);

        if (!$lineaPedido) {
            return new JsonResponse([
                'message' => 'La linea de pedido no existe.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->lineasPedidosRepository->delete($lineaPedido);

        return new JsonResponse([
            'message' => 'Linea de pedido eliminada correctamente.',
        ], Response::HTTP_OK);
    }

    // #[Route('/{id}', name: 'api_alfareria_usuarios_show', methods: ['GET'])]
    // public function show(Usuarios $usuario): JsonResponse
    // {
    //     $pedidos = [];
    //         foreach ($usuario->getPedidos() as $pedido) {
    //             // var_dump($pedido);
    //             $pedidos[] = [
    //                 'id' => $pedido->getId(),
    //                 'fecha' => $pedido->getFecha(),
    //                 'total' => $pedido->getTotal(),
    //                 'estado' => $pedido->getEstado(),
    //                 // Agregar cualquier otra información que desee mostrar
    //             ];
    //         }
    //     $data[] = [
    //         'id' => $usuario->getId(),
    //         'nombre' => $usuario->getNombre(),
    //         'email' => $usuario->getEmail(),
    //         'password' => $usuario->getPassword(),
    //         'es_admin' => $usuario->isEsAdmin(),
    //         'pedidos' => $pedidos
    //     ];


    //     return new JsonResponse($data, Response::HTTP_OK);
    // }
    // // #[Route('/{id}', name: 'api_alfareria_usuarios_show', methods: ['GET'])]
    // // public function show(int $id): JsonResponse
    // // {
    // //     $usuario = $this->usuariosRepository->find($id);

    // //     if (!$usuario) {
    // //         return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    // //     }

    // //     $data = [
    // //         'id' => $usuario->getId(),
    // //         'nombre' => $usuario->getNombre(),
    // //         'email' => $usuario->getEmail(),
    // //         'password' => $usuario->getPassword(),
    // //         'es_admin' => $usuario->isEsAdmin()
    // //     ];

    // //     return new JsonResponse($data, Response::HTTP_OK);
    // // }

    // #[Route('/new/', name: 'api_alfareria_usuarios_new', methods: ['POST'])]
    // public function new(Request $request): JsonResponse
    // {

    //     $data = json_decode($request->getContent());

    //     $usuario = new Usuarios();
    //     $usuario->setNombre($data->nombre)
    //         ->setEmail($data->email)
    //         ->setPassword($data->password)
    //         ->setEsAdmin($data->es_admin);

    //     $this->usuariosRepository->save($usuario, true);

    //     return new JsonResponse($data, Response::HTTP_CREATED);
    // }

    // #[Route('/edit/{id}', name: 'api_alfareria_usuarios_edit', methods: ['PUT','PATCH'])]
    // public function edit($id, Request $request): JsonResponse
    // {
    //     $usuario = $this->usuariosRepository->find($id);
    //     $data = json_decode($request->getContent());

    //     empty($data->nombre) ? true : $usuario->setNombre($data->nombre);
    //     empty($data->email) ? true : $usuario->setEmail($data->email);
    //     empty($data->password) ? true : $usuario->setPassword($data->password);
    //     empty($data->setEsAdmin) ? true : $usuario->setEsAdmin($data->setEsAdmin);
    //     $this->usuariosRepository->save($usuario, true);

    //     $mensaje = "Usuario ".$data->nombre." actualizado"; 

    //     return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    // }

    // #[Route('/delete/{id}', name: "api_alfareria_usuarios_delete", methods: ['DELETE'])]
    // public function remove(Usuarios $usuario): JsonResponse
    // {
    //     $usuarioNombre = $usuario->getNombre();
    //     $this->usuariosRepository->remove($usuario, true);
    //     return new JsonResponse(['status' => 'Usuario ' . $usuarioNombre . ' borrado'], Response::HTTP_OK);
    // }
}
