<?php
class Offer {
    private $_offer;
    private $_parser;

    function __construct($offer) {
        $this->_offer = $offer;
        $this->_parser = Database::openFromNode($offer);
    }

    function remove() {
        Database::removeChild($this->_offer);
    }

    function getID() {
        return $this->_offer->attributes()->id->__toString();
    }

    function getDriverID() {
        return $this->_offer->userid->__toString();
    }

    function getDriverName() {
        return $this->_offer->username->__toString();
    }

    function getDate() {
        return $this->_offer->date->__toString();
    }

    function getTime() {
        return $this->_offer->time->__toString();
    }

    function getStartLocation() {
        return $this->_offer->start->__toString();
    }

    function getDestination() {
        return $this->_offer->end->__toString();
    }

    function getSeats() {
        return $this->_offer->seats->__toString();
    }

    function addRider($id) {
        $this->_offer->riders->attributes()->count = $this->_offer->riders->attributes()->count + 1;
        $rider = $this->_offer->riders->addChild("rider");
        $rider->addAttribute("id", $id);      
    }

    function hasRider($id) {
        $result = $this->_parser->searchNodes("riders/rider", NULL, array("id" => $id));
        if (sizeof($result) == 1) {
            return $result[0];
        }
        return NULL;
    }

    function removeRider($id) {
        $rider = $this->hasRider($id);
        return Database::removeChild($rider);
    }

}
?>