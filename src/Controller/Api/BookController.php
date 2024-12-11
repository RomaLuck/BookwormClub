<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Serializer\SerializationContextProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/books')]
final class BookController extends AbstractController
{

    public function __construct(private readonly SerializationContextProvider $contextProvider)
    {
    }

    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository, SerializerInterface $serializer, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 2);
        $pagesTotal = (int)ceil($bookRepository->count() / $limit);

        $books = $serializer->serialize([
            'data' => $bookRepository->findBooks($page, $limit),
            'currentPage' => $page,
            'pagesTotal' => $pagesTotal
        ], 'json', $this->contextProvider->getContext());

        return new Response($books, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/', name: 'app_book_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator
    ): Response
    {
        $requestData = $request->getContent();
        $book = $serializer->deserialize($requestData, Book::class, 'json');

        $errors = $validator->validate($book);
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Validation failed', 'errors' => $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->json(['message' => 'Book created!', 'data' => $book->toArray()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show(Book $book, SerializerInterface $serializer): Response
    {
        $foundBook = $serializer->serialize([
            'data' => $book
        ], 'json', $this->contextProvider->getContext());
        return new Response($foundBook, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_book_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        Request                $request,
        Book                   $book,
        EntityManagerInterface $entityManager,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator
    ): Response
    {
        $requestData = $request->getContent();
        $updatedBook = $serializer->deserialize($requestData, Book::class, 'json');

        $errors = $validator->validate($updatedBook);
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Validation failed', 'errors' => $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $book->setTitle($updatedBook->getTitle());
        $book->setAuthor($updatedBook->getAuthor());
        $book->setDescription($updatedBook->getDescription());
        $book->setRating($updatedBook->getRating());

        $entityManager->flush();

        return $this->json(['message' => 'Book updated!', 'data' => $book->toArray()]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->json(['message' => 'Book deleted!']);
    }
}
