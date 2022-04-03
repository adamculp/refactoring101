<?php
/**
 * In this step we "Replace Temp with Query" and move the calculation of temporary
 * variable $totalAmount to its own method and name the method getTotalCharge().
 *
 * For a video showing this, and prior steps, see: https://youtu.be/BV4yjt33pJ0
 */

namespace Refactoring008;

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
        $frequentRenterPoints = 0;
        $result = "Rental Record for " . $this->getName() . "\n";
        
        foreach ($this->rentals as $rental) {
            $frequentRenterPoints += $rental->getFrequentRenterPoints();

            $result .= "\t" . $rental->movie->getTitle() . "\t" . $rental->getCharge() . "\n";
        }

        // add footer lines
        $result .= "Amount owed is " . $this->getTotalCharge() . "\n";
        $result .= "You earned " . $frequentRenterPoints . " frequent renter points";
        
        return $result;
    }
    
    public function getTotalCharge(): float {
        $result = 0;
        
        foreach ($this->rentals as $rental) {
            $result += $rental->getCharge();
        }
        
        return $result;
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

    public function getCharge(): float {
        $result = 0;

        switch ($this->movie->getPriceCode()) {
            case Movie::REGULAR:
                $result += 2;
                if ($this->getDaysRented() > 2) {
                    $result += ($this->getDaysRented() - 2) * 1.5;
                }
                break;

            case Movie::NEW_RELEASE:
                $result += $this->getDaysRented() * 3;
                break;

            case Movie::CHILDREN:
                $result += 1.5;
                if ($this->getDaysRented() > 3) {
                    $result += ($this->getDaysRented() - 3) * 1.5;
                }
                break;
        }

        return $result;
    }

    public function getFrequentRenterPoints(): int {
        $result = 0;

        // add bonus for a two-day new release rental
        if (($this->movie->getPriceCode() == Movie::NEW_RELEASE) && ($this->getDaysRented() > 1)) {
            $result += 2;
        } else {
            $result += 1;
        }

        return $result;
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
