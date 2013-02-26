<?php
/**
 * In this step we "Replace Type Code with State", which requires quite a few changes:
 * 
 * 1 - We create an abstract Price class with abstract getPriceCode() method.
 * 2 - We create sub-classes of Price for each type code that return the priceCode.
 * 3 - We create a switch in Movie->setPriceCode() to use the new constructs.
 * 4 - $priceCode becomes $price as it will be carrying the Price object.
 * 5 - In the Movie->__construct we populate the $price object by calling setPriceCode().
 * 6 - Finally, we alter Movie->getPriceCode() to return the code from $price.
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
    public $price;
    
    public function __construct($title, $priceCode) {
        $this->title = $title;
        $this->setPrice($priceCode);
    }
    
    public function getPriceCode() {
        return $this->price->getPriceCode();
    }
    
    public function setPrice($priceCode) {

        switch ($priceCode) {
            case self::REGULAR:
                $this->price = new RegularPrice();
                break;

            case self::CHILDRENS:
                $this->price = new ChildrensPrice();
                break;

            case self::NEW_RELEASE:
                $this->price = new NewReleasePrice();
                break;

            default:
                throw new Exception('Incorrect Price Code.');
                break;
        }
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

    public function getFrequentRenterPoints($daysRented) {
        // add bonus for a two day release rental
        if (($this->getPriceCode() == self::NEW_RELEASE) && ($daysRented > 1)) {
            return 2;
        } else {
            return 1;
        }
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
        return $this->movie->getFrequentRenterPoints($this->getDaysRented());
    }
}

abstract class Price {
    abstract protected function getPriceCode();
}

class ChildrensPrice extends Price {
    public function getPriceCode() {
        return Movie::CHILDRENS;
    }
}

class NewReleasePrice extends Price {
    public function getPriceCode() {
        return Movie::NEW_RELEASE;
    }
}

class RegularPrice extends Price {
    public function getPriceCode() {
        return Movie::REGULAR;
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
