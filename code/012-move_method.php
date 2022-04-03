<?php
/**
 * In this step we "Move Method" getFrequentRenterPoints() to the Movie class
 * because it relies on attributes of the Movie object. ($priceCode) And if the
 * users want to add more types later it will be easier to alter in the Movie
 * class.
 *
 * This causes the least impact if/when new types or frequent renter points are
 * introduced.
 * 
 */

namespace Refactoring012;

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
    
    public function statementText(): string {
        $result = "Rental Record for " . $this->getName() . "\n";
        
        foreach ($this->rentals as $rental) {
            $result .= "\t" . $rental->movie->getTitle() . "\t" . $rental->getCharge() . "\n";
        }

        // add footer lines
        $result .= "Amount owed is " . $this->getTotalCharge() . "\n";
        $result .= "You earned " . $this->getTotalFrequentRenterPoints() . " frequent renter points";
        
        return $result;
    }

    public function statementHtml(): string {
        $result = "<h1>Rental Record for <em>" . $this->getName() . "</em></h1>\n";

        foreach ($this->rentals as $rental) {
            $result .= $rental->movie->getTitle() . " " . $rental->getCharge() . "<br />\n";
        }

        // add footer lines
        $result .= "<p>Amount owed is <em>" . $this->getTotalCharge() . "</em></p>\n";
        $result .= "<p>You earned <em>" . $this->getTotalFrequentRenterPoints() . "</em> frequent renter points</p>";

        return $result;
    }
    
    public function getTotalFrequentRenterPoints(): int {
        $result = 0;
        
        foreach ($this->rentals as $rental) {
            $result += $rental->getFrequentRenterPoints();
        }
        
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

    public function getCharge($daysRented): float {
        $result = 0;

        switch ($this->getPriceCode()) {
            case self::REGULAR:
                $result += 2;
                if ($daysRented > 2) {
                    $result += ($daysRented - 2) * 1.5;
                }
                break;

            case self::NEW_RELEASE:
                $result += $daysRented * 3;
                break;

            case self::CHILDREN:
                $result += 1.5;
                if ($daysRented > 3) {
                    $result += ($daysRented - 3) * 1.5;
                }
                break;
        }

        return $result;
    }

    public function getFrequentRenterPoints($daysRented): int {
        $result = 0;

        // add bonus for a two-day new release rental
        if (($this->getPriceCode() == self::NEW_RELEASE) && ($daysRented > 1)) {
            $result += 2;
        } else {
            $result += 1;
        }

        return $result;
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
        return $this->movie->getCharge($this->getDaysRented());
    }

    public function getFrequentRenterPoints(): int {
        return $this->movie->getFrequentRenterPoints($this->getDaysRented());
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
echo $customer->statementText();
echo $customer->statementHtml();
