<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Entity\Productos;
use App\Entity\LineasPedidos;
use App\Repository\LineasPedidosRepository;
use App\Repository\ProductosRepository;
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
    private ProductosRepository $productosRepository;

    public function __construct(PedidosRepository $pedidosRepo, UsuariosRepository $usuariosRepo, ProductosRepository $productosRepo)
    {
        $this->pedidosRepository = $pedidosRepo;
        $this->usuariosRepository = $usuariosRepo;
        $this->productosRepository = $productosRepo;

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

    #[Route('/new', name: 'api_alfareria_pedidos_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {   
        
        $data = json_decode($request->getContent());
        $usuario = $this->usuariosRepository->find($data->id_usuario);
        $lineasPedidoArray = $data->lineasPedidos;
        $pedido = new Pedidos();
        
        $pedido->setFecha(new \DateTime('now'))
        ->setTotal($data->total)
        ->setIdUsuario($usuario);
        
        foreach ($lineasPedidoArray as $value) {
            $idProducto = $value->id_producto;
            // return new JsonResponse($idProducto);

            $producto = $this->productosRepository->findOneBy(['id' => $idProducto]);
            if ($producto === null){
                continue;
                //TODO: informar productID doesn't exist
            }

            $lineaPedido = new LineasPedidos();
            $lineaPedido ->setIdProducto($producto)
            ->setCantidad($value->cantidad)
            ->setPrecio($producto->getPrecio())
            ->setIdPedido($pedido);

            $pedido->addLineasPedido($lineaPedido);
        }
        
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
