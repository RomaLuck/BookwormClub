<?php

namespace App\Command;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand(
    name: 'RateBookConsoleCommand',
    description: 'Rate the books',
)]
#[AsCronTask('00 20 * * *')]
class RateBookConsoleCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $books = $this->entityManager->getRepository(Book::class)->findAll();

        $progressBar = new ProgressBar($output, count($books));
        $progressBar->start();
        foreach ($books as $book) {
            $progressBar->advance();

            $reviews = $book->getReviews();
            if (count($reviews) === 0) {
                $book->setRating(0);
                continue;
            }

            $rating = 0;
            foreach ($reviews as $review) {
                $rating += $review->getRating();
            }

            $bookRating = $rating / count($reviews);
            $book->setRating($bookRating);

            $this->entityManager->persist($book);
        }
        $progressBar->finish();

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
