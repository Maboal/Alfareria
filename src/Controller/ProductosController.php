<?php

namespace App\Controller;

use App\Entity\Productos;
use App\Repository\ProductosRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/productos', name: 'api_alfareria_productos')]
class ProductosController extends AbstractController
{
    private ProductosRepository $productosRepository;

    public function __construct(ProductosRepository $productosRepo)
    {
        $this->productosRepository = $productosRepo;
    }

    #[Route('/', name: 'api_alfareria_productos_showAll', methods: ['GET'])]
    public function showAll(): JsonResponse
    {
        $productos = $this->productosRepository->findAll();
        $data = [];

        foreach ($productos as $producto) {
            $data[] = [
                'id' => $producto->getId(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'precio' => $producto->getPrecio(),
                'stock' => $producto->getStock(),
                'imagen_url' => $producto->getImagenUrl(),
                'categoria' => $producto->getCategoria()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_alfareria_productos_show', methods: ['GET'])]
    public function show(Productos $producto): JsonResponse
    {
        $data[] = [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'stock' => $producto->getStock(),
            'imagen_url' => $producto->getImagenUrl(),
            'categoria' => $producto->getCategoria()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/new/', name: 'api_alfareria_productos_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $producto = new Productos();
        $producto->setNombre($data->nombre)
            ->setDescripcion($data->descripcion)
            ->setPrecio($data->precio)
            ->setStock($data->stock)
            ->setImagenUrl($data->imagen_url)
            ->setCategoria($data->categoria);

        $this->productosRepository->save($producto, true);

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/edit/{id}', name: 'api_alfareria_productos_edit', methods: ['PUT','PATCH'])]
    public function edit($id, Request $request): JsonResponse
    {
        $producto = $this->productosRepository->find($id);
        $data = json_decode($request->getContent());

        empty($data->nombre) ? true : $producto->setNombre($data->nombre);
        empty($data->descripcion) ? true : $producto->setDescripcion($data->descripcion);
        empty($data->precio) ? true : $producto->setPrecio($data->precio);
        empty($data->stock) ? true : $producto->setStock($data->stock);
        empty($data->imagen_url) ? true : $producto->setImagenUrl($data->imagen_url);
        empty($data->categoria) ? true : $producto->setCategoria($data->categoria);

        $this->productosRepository->save($producto, true);

        $mensaje = "Producto ".$data->nombre." actualizado";

        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: "api_alfareria_productos_delete", methods: ['DELETE'])]
    public function remove(Productos $producto): JsonResponse
    {
        $productoNombre = $producto->getNombre();
        $this->productosRepository->remove($producto, true);
        return new JsonResponse(['status' => 'Producto ' . $productoNombre . ' borrado'], Response::HTTP_OK);
    }
}