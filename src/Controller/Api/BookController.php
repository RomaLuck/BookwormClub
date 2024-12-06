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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/books')]
final class BookController extends AbstractController
{

    public function __construct(private readonly SerializationContextProvider $contextProvider)
    {
    }

    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository, SerializerInterface $serializer): Response
    {
        $books = $serializer->serialize($bookRepository->findAll(), 'json', $this->contextProvider->getContext());
        return new Response($books, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/', name: 'app_book_new', methods: ['POST'])]
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

        return $this->json(['message' => 'Book created!', 'book' => $book->toArray()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book, SerializerInterface $serializer): Response
    {
        $foundBook = $serializer->serialize($book, 'json', $this->contextProvider->getContext());
        return new Response($foundBook, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_book_edit', methods: ['PUT'])]
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

        return $this->json(['message' => 'Book updated!', 'book' => $book->toArray()]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->json(['message' => 'Book deleted!']);
    }
}
