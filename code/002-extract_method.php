<?php
/**
 * In this step we use "Extract Method" to move the switch statement from 
 * Customer->statement() to its own method named amountFor(). Then we call this
 * new amountFor() method while passing it $each as a param, which is a Rental object.
 *
 * For a video showing this step, see: https://youtu.be/mtBrzU13Yqc
 */

namespace Refactoring002;

class Customer {
    protected string $name;
    protected array $rentals;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function addRental(Rental $rental) {
        $this->rentals[] = $rental;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function statement(): string {
        $totalAmount = 0;
        $frequentRenterPoints = 0;
        $result = "Rental Record for " . $this->getName() . "\n";
        
        foreach ($this->rentals as $each) {
            
            $thisAmount = $this->amountFor($each);

            $frequentRenterPoints++;

            // add bonus for a two-day new release rental
            if (($each->movie->getPriceCode() == Movie::NEW_RELEASE) && ($each->getDaysRented() > 1)) {
                $frequentRenterPoints++;
            }

            $result .= "\t" . $each->movie->getTitle() . "\t" . $thisAmount . "\n";
            $totalAmount += $thisAmount;
        }

        // add footer lines
        $result .= "Amount owed is " . $totalAmount . "\n";
        $result .= "You earned " . $frequentRenterPoints . " frequent renter points";
        
        return $result;
    }
    
    public function amountFor($each): float {
        $thisAmount = 0;
        
        switch ($each->movie->getPriceCode()) {
            case Movie::REGULAR:
                $thisAmount += 2;
                if ($each->getDaysRented() > 2) {
                    $thisAmount += ($each->getDaysRented() - 2) * 1.5;
                }
                break;

            case Movie::NEW_RELEASE:
                $thisAmount += $each->getDaysRented() * 3;
                break;

            case Movie::CHILDREN:
                $thisAmount += 1.5;
                if ($each->getDaysRented() > 3) {
                    $thisAmount += ($each->getDaysRented() - 3) * 1.5;
                }
                break;
        }
        
        return $thisAmount;
    }
}

class Movie {
    const CHILDREN = 2;
    const REGULAR = 0;
    const NEW_RELEASE = 1;

    protected string $title;
    protected int $priceCode;
    
    public function __construct($title, $priceCode) {
        $this->title = $title;
        $this->setPriceCode($priceCode);
    }
    
    public function getPriceCode(): int {
        return $this->priceCode;
    }
    
    public function setPriceCode($priceCode) {
        $this->priceCode = $priceCode;
    }
    
    public function getTitle(): string {
        return $this->title;
    }
}

class Rental {
    public Movie $movie;
    protected int $daysRented;
    
    public function __construct(Movie $movie, $daysRented) {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
    }
    
    public function getDaysRented(): int {
        return $this->daysRented;
    }
    
    public function getMovie(): Movie {
        return $this->movie;
    }
}


// define customer
$customer = new Customer('Adam Culp');

// choose movie to be rented, define rental, add it to the customer
$movie = new Movie('Gladiator', 0);
$rental = new Rental($movie, 1);
$customer->addRental($rental);

// choose 2nd movie to be rented, define rental, add it to the customer
$movie = new Movie('Spiderman', 1);
$rental = new Rental($movie, 2);
$customer->addRental($rental);

// print the statement
echo $customer->statement();
