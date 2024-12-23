<?php

namespace App\Tests\Features;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearchTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $em = self::getContainer()->get('doctrine.orm.entity_manager');

        $book1 = (new Book())
            ->setTitle('The Hobbit')
            ->setAuthor('J.R.R. Tolkien')
            ->setDescription('The Hobbit is a children\'s fantasy novel by English author J. R. R. Tolkien.');
        $em->persist($book1);

        $book2 = (new Book())
            ->setTitle('Harry Potter and the Philosopher\'s Stone')
            ->setAuthor('J.K. Rowling')
            ->setDescription('Harry Potter and the Philosopher\'s Stone is a fantasy novel written by British author J. K. Rowling.');
        $em->persist($book2);

        $em->flush();
    }

    public function testSearchVector()
    {
        $books = self::getContainer()->get('App\Repository\BookRepository')->findAll();
        foreach ($books as $book) {
            dump($book->getSearchVector());
            self::assertIsString($book->getSearchVector());
            self::assertNotEmpty($book->getSearchVector());
        }
    }

    public function testSearch(): void
    {
        $bookRepository = self::getContainer()->get('App\Repository\BookRepository');
        $books = $bookRepository->search('Harry Potter');
        self::assertIsArray($books);
        self::assertNotEmpty($books);
    }
}