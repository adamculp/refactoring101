<?php
/**
 * In this step we "Move Method" getCharge() to the Movie class because a switch
 * statement should not rely on attributes of another object. ($priceCode) And if
 * the users want to add more types later it will be easier to alter in the Movie
 * class without touching Rental.
 * 
 * This causes the least impact if new types are introduced.
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
    
    public function statementText() {
        $result = "Rental Record for " . $this->getName() . "\n";
        
        foreach ($this->rentals as $rental) {
            $result .= "\t" . $rental->movie->getTitle() . "\t" . $rental->getCharge() . "\n";
        }

        // add footer lines
        $result .= "Amount owed is " . $this->getTotalCharge() . "\n";
        $result .= "You earned " . $this->getTotalFrequentRenterPoints() . " frequent renter points";
        
        return $result;
    }

    public function statementHtml() {
        $result = "<h1>Rental Record for <em>" . $this->getName() . "</em></h1><p>\n";

        foreach ($this->rentals as $rental) {
            $result .= $rental->movie->getTitle() . " " . $rental->getCharge() . "<br />\n";
        }

        // add footer lines
        $result .= "<p>Amount owed is <em>" . $this->getTotalCharge() . "</em></p>\n";
        $result .= "<p>You earned <em>" . $this->getTotalFrequentRenterPoints() . "</em> frequent renter points</p>";

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

    public function getCharge($daysRented) {
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

            case self::CHILDRENS:
                $result += 1.5;
                if ($daysRented > 3) {
                    $result += ($daysRented - 3) * 1.5;
                }
                break;
        }

        return $result;
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
        return $this->movie->getCharge($this->getDaysRented());
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
echo $customer->statementText();
echo $customer->statementHtml();
