<?php
/**
 * In this step we rename variables to make more sense and be better self-documenting.
 * This is done by renaming:
 * 
 * 1 - $each to $rental in Customer->statement() and in Customer->amountFor()
 * 2 - $thisAmount to $result in Customer->amountFor()
 * 
 */
class Customer {
    public $name;
    public $rentals;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function addRental(Rental $rental) {
        $this->rentals[] = $rental;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function statement() {
        $totalAmount = 0;
        $frequentRenterPoints = 0;
        $result = "Rental Record for " . $this->getName() . "\n";
        
        foreach ($this->rentals as $rental) {
            
            $thisAmount = $this->amountFor($rental);

            $frequentRenterPoints++;

            // add bonus for a two day release rental
            if (($rental->movie->getPriceCode() == Movie::NEW_RELEASE) && ($rental->getDaysRented() > 1)) {
                $frequentRenterPoints++;
            }

            $result .= "\t" . $rental->movie->getTitle() . "\t" . $thisAmount . "\n";
            $totalAmount += $thisAmount;
        }

        // add footer lines
        $result .= "Amount owed is " . $totalAmount . "\n";
        $result .= "You earned " . $frequentRenterPoints . " frequent renter points";
        
        return $result;
    }
    
    public function amountFor($rental) {
        $result = 0;
        
        switch ($rental->movie->getPriceCode()) {
            case Movie::REGULAR:
                $result += 2;
                if ($rental->getDaysRented() > 2) {
                    $result += ($rental->getDaysRented() - 2) * 1.5;
                }
                break;

            case Movie::NEW_RELEASE:
                $result += $rental->getDaysRented() * 3;
                break;

            case Movie::CHILDRENS:
                $result += 1.5;
                if ($rental->getDaysRented() > 3) {
                    $result += ($rental->getDaysRented() - 3) * 1.5;
                }
                break;
        }
        
        return $result;
    }
}

class Movie {
    const CHILDRENS = 2;
    const REGULAR = 0;
    const NEW_RELEASE = 1;
    
    public $title;
    public $priceCode;
    
    public function __construct($title, $priceCode) {
        $this->title = $title;
        $this->setPriceCode($priceCode);
    }
    
    public function getPriceCode() {
        return $this->priceCode;
    }
    
    public function setPriceCode($priceCode) {
        $this->priceCode = $priceCode;
    }
    
    public function getTitle() {
        return $this->title;
    }
}

class Rental {
    public $movie;
    public $daysRented;
    
    public function __construct(Movie $movie, $daysRented) {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
    }
    
    public function getDaysRented() {
        return $this->daysRented;
    }
    
    public function getMovie() {
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
