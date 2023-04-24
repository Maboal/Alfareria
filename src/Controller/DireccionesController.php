<?php

namespace App\Controller;

use App\Entity\Direcciones;
use App\Repository\DireccionesRepository;
use App\Repository\UsuariosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/direcciones', name: 'api_alfareria_direcciones')]
class DireccionesController extends AbstractController
{
    private DireccionesRepository $direccionesRepository;
    private UsuariosRepository $usuariosRepository;

    public function __construct(DireccionesRepository $direccionesRepo, UsuariosRepository $usuariosRepo)
    {
        $this->direccionesRepository = $direccionesRepo;
        $this->usuariosRepository = $usuariosRepo;
    }

    #[Route('/{id}', name: 'api_alfareria_direcciones_show', methods: ['GET'])]
    public function show(Direcciones $direccion): JsonResponse
    {
        $data[] = [
            'id' => $direccion->getId(),
            'calle' => $direccion->getCalle(),
            'ciudad' => $direccion->getCiudad(),
            'codigo_postal' => $direccion->getCodigoPostal(),
            'usuario' => [
                'id' => $direccion->getIdUsuario()->getId(),
                'nombre' => $direccion->getIdUsuario()->getNombre(),
                'email' => $direccion->getIdUsuario()->getEmail(),
            ]
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/new/', name: 'api_alfareria_direcciones_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent());
        $usuario = $this->usuariosRepository->find($data->id_usuario);
        $direccion = new Direcciones();
        $direccion  ->setCalle($data->calle)
                    ->setCiudad($data->ciudad)
                    ->setCodigoPostal($data->codigo_postal)
                    ->setIdUsuario($usuario);

        $this->direccionesRepository->save($direccion, true);

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/edit/{id}', name: 'api_alfareria_direcciones_edit', methods: ['PUT','PATCH'])]
    public function edit($id, Request $request): JsonResponse
    {
        $direccion = $this->direccionesRepository->find($id);
        $data = json_decode($request->getContent());

        empty($data->calle) ? true : $direccion->setCalle($data->calle);
        empty($data->ciudad) ? true : $direccion->setCiudad($data->ciudad);
        empty($data->cod_postal) ? true : $direccion->setCodigoPostal($data->codigo_postal);
        empty($data->id_usuario) ? true : $direccion->setIdUsuario($this->usuariosRepository->find($data->id_usuario));

        $this->direccionesRepository->save($direccion, true);

        $mensaje = "Direccion ".$data->calle." actualizada"; 

        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: "api_alfareria_direcciones_delete", methods: ['DELETE'])]
    public function remove(Direcciones $direccion): JsonResponse
    {
        $direccionCalle = $direccion->getCalle();
        $this->direccionesRepository->remove($direccion, true);
        return new JsonResponse(['status' => 'Direccion ' . $direccionCalle . ' borrada'], Response::HTTP_OK);
    }
}
