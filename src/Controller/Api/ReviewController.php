<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Serializer\SerializationContextProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/reviews')]
final class ReviewController extends AbstractController
{

    public function __construct(private readonly SerializationContextProvider $contextProvider)
    {
    }

    #[Route('/', name: 'app_review_index', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository, SerializerInterface $serializer): Response
    {
        $reviews = $serializer->serialize($reviewRepository->findAll(), 'json', $this->contextProvider->getContext());
        return new Response($reviews, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/', name: 'app_review_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestData = $request->toArray();

        $book = $entityManager->getRepository(Book::class)->find($requestData['book']);
        if (!$book) {
            return $this->json(['message' => 'Book not found!'], Response::HTTP_NOT_FOUND);
        }

        $review = new Review();
        $review->setBody($requestData['body']);
        $review->setRating($requestData['rating']);
        $review->setAuthor($requestData['author']);
        $review->setBook($book);

        $entityManager->persist($review);
        $entityManager->flush();

        return $this->json(['message' => 'Review created!', 'review' => $review->toArray()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_review_show', methods: ['GET'])]
    public function show(Review $review, SerializerInterface $serializer): Response
    {
        $foundReview = $serializer->serialize($review, 'json', $this->contextProvider->getContext());
        return new Response($foundReview, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_review_edit', methods: ['PUT'])]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $requestData = $request->getContent();
        $updatedReview = $serializer->deserialize($requestData, Review::class, 'json');

        $review->setBody($updatedReview->getBody());
        $review->setRating($updatedReview->getRating());
        $review->setAuthor($updatedReview->getAuthor());

        $entityManager->flush();

        return $this->json(['message' => 'Review updated!', 'review' => $review->toArray()]);
    }

    #[Route('/{id}', name: 'app_review_delete', methods: ['POST'])]
    public function delete(Review $review, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($review);
        $entityManager->flush();

        return $this->json(['message' => 'Review deleted!']);
    }
}
