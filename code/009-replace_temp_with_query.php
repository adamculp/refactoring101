<?php
/**
 * In this step we "Replace Temp with Query" and move the calculation of temporary
 * variable $frequentRenterPoints to its own method and named getTotalFrequentRenterPoints().
 *
 * We are now able to remove all instances of the temp variable $frequentRenterPoints.
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
        $result = "Rental Record for " . $this->getName() . "\n";
        
        foreach ($this->rentals as $rental) {
            $result .= "\t" . $rental->movie->getTitle() . "\t" . $rental->getCharge() . "\n";
        }

        // add footer lines
        $result .= "Amount owed is " . $this->getTotalCharge() . "\n";
        $result .= "You earned " . $this->getTotalFrequentRenterPoints() . " frequent renter points";
        
        return $result;
    }
    
    public function getTotalFrequentRenterPoints() {
        $result = 0;
        
        foreach ($this->rentals as $rental) {
            $result += $rental->getFrequentRenterPoints();
        }
        
        return $result;
    }
    
    public function getTotalCharge() {
        $result = 0;
        
        foreach ($this->rentals as $rental) {
            $result += $rental->getCharge();
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

    public function getCharge() {
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

            case Movie::CHILDRENS:
                $result += 1.5;
                if ($this->getDaysRented() > 3) {
                    $result += ($this->getDaysRented() - 3) * 1.5;
                }
                break;
        }

        return $result;
    }

    public function getFrequentRenterPoints() {
        // add bonus for a two day release rental
        if (($this->movie->getPriceCode() == Movie::NEW_RELEASE) && ($this->getDaysRented() > 1)) {
            return 2;
        } else {
            return 1;
        }
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
