<?php
class OfferTable {
    public static $instance;
    public static $file = '/xmls/offers.xml';

    private $_database;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new OfferTable();
        }
        return static::$instance;
    }

    private function __construct() {
        $this->_database = Database::openFromFile(static::$file);
    }

    public function save() {
        $this->_database->saveDatabase();
    }

    public function size() {
        return $this->_database->size();
    }

    public function getAllOffer() {
        $result = $this->_database->searchNodes("/list/offer");
        $arr = [];
        foreach ($result as $offer) {
            $arr[] = new Offer($offer);
        }
        $arr = array_reverse($arr);
        return $arr;
    }

    public function getOffer($id) {
        $result = $this->_database->searchNodes("/list/offer", NULL, array("id" => $id));
        $len = sizeof($result);
        if ($len > 1) {
            echo "Fatal Error: More than 1 offer is found";
            return NULL;
        } else if ($len == 0) {
            echo "No offer can be found";
            return NULL;
        } else {
            return new Offer($result[0]);
        }
    }

    public function getOfferByDriverID($id) {
        $result = $this->getAllOffer();
        $arr = [];
        foreach ($result as $offer) {
            if ($offer->getDriverID() == $id) {
                $arr[] = $offer;
            }
        }
        return $arr;
    }

    public function addOffer($info) {
        $this->_database->getXML()->attributes()->count = $this->_database->getXML()->attributes()->count + 1;
        $offer = $this->_database->addNode("offer");
        $offer->addAttribute("id", $this->_database->getXML()->attributes()->count);
        $offer->addChild("userid", $info['userid']); 
        $offer->addChild("username", $info['username']); 
        $offer->addChild("date", $info['date']);
        $offer->addChild("time", $info['time']);
        $offer->addChild("start", $info['start']);
        $offer->addChild("end", $info['end']);
        $offer->addChild("seats", $info['seats']);
        $offer->addChild("riders")->addAttribute("count", 0);
        return new Offer($offer);
    }

    public function removeOffer($id) {
        $this->getOffer($id)->remove();
    }
}

?>