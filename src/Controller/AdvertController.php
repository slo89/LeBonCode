<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/advert")
 */
class AdvertController extends AbstractController
{
    private $serializer;
    private $advertRepository;

    /**
     * AdvertController constructor.
     * @param SerializerInterface $serializer
     * @param AdvertRepository $advertRepository
     */
    public function __construct(SerializerInterface $serializer, AdvertRepository $advertRepository)
    {
        $this->serializer=$serializer;
        $this->advertRepository=$advertRepository;
    }

    /**
     * @Route(name="get_adverts", methods={"GET"})
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $adverts = $this->advertRepository->findAll();
        $advertsJson=$this->serializer->serialize($adverts, 'json', []);
        return new JsonResponse($advertsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route(name="advertCreate", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $advert = $this->serializer->deserialize($request->getContent(), Advert::class, 'json');

        $this->advertRepository->add($advert);

        $jsonAdvert = $this->serializer->serialize($advert, 'json');

        return new JsonResponse($jsonAdvert, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/{id}", name="delete_advert", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $advert = $this->advertRepository->find($id);

        if (!$advert) {
            return new JsonResponse(['error' => 'Advert not found.'], Response::HTTP_NOT_FOUND);
        }

        $this->advertRepository->disable($advert);

        return new JsonResponse(['message' => 'Advert deleted successfully.'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_advert", methods={"PATCH"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $advert = $this->advertRepository->find($id);

        if (!$advert) {
            return new JsonResponse(['error' => 'Advert not found'], Response::HTTP_NOT_FOUND);
        }

        $updatedAdvert = $this->serializer->deserialize($request->getContent(), Advert::class, 'json');

        if ($updatedAdvert->getTitle() != null and ($updatedAdvert->getTitle() !== $advert->getTitle())) {
            return new JsonResponse(['error' => 'Cannot update the title of the Advert'], Response::HTTP_BAD_REQUEST);
        }

        $this->advertRepository->update($advert, $updatedAdvert);

        $jsonAdvert = $this->serializer->serialize($advert, 'json', []);

        return new JsonResponse($jsonAdvert, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/search", name="search_advert", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $title = $request->query->get('title');
        $priceMin = $request->query->get('price_min');
        $priceMax = $request->query->get('price_max');

        $adverts = $this->advertRepository->search($title, $priceMin, $priceMax);
        $jsonAdverts = $this->serializer->serialize($adverts, 'json', []);

        return new JsonResponse($jsonAdverts, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/{id}",name="advertDetails", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function searchById(int $id): JsonResponse
    {
        $advert = $this->advertRepository->find($id);
        if ($advert) {
            $jsonAdvert = $this->serializer->serialize($advert, 'json', []);
            return new JsonResponse($jsonAdvert, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

}
