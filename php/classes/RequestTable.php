<?php
class RequestTable {
    public static $instance;
    public static $file = '/xmls/requests.xml';

    private $_database;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new RequestTable();
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

    public function ifUserReserved($offer_id, $user_id) {
        $requests = $this->getRequestByOfferID($offer_id);
        foreach ($requests as $request) {
            if ($request->getRiderID() == $user_id) {
                return true;
            }
        }
        return false;
    }

    public function getAllRequest() {
        $result = $this->_database->searchNodes("/list/request");
        $arr = [];
        foreach ($result as $request) {
            $arr[] = new Request($request);
        }
        return $arr;
    }

    public function getRequestByID($id) {
        $result = $this->_database->searchNodes("/list/request", NULL, array("id" => $id));
        $len = sizeof($result);
        if ($len > 1) {
            echo "Fatal Error: More than 1 offer is found";
            return NULL;
        } else if ($len == 0) {
            echo "No offer can be found";
            return NULL;
        } else {
            return new Request($result[0]);
        }
    }

    public function getRequestByOfferID($id) {
        $result = $this->_database->searchNodes("/list/request", NULL, array("offer_id" => $id));
        $arr = [];
        foreach ($result as $request) {
            $arr[] = new Request($request);
        }
        return $arr;
    }

    public function getRequestByDriver($id) {
        $result = $this->getAllRequest();
        $arr = [];
        foreach ($result as $request) {
            if ($request->getDriverID() == $id) {
                $arr[] = $request;
            }
        }
        return $arr;
    }

    public function getRequestByRider($id) {
        $result = $this->getAllRequest();
        $arr = [];
        foreach ($result as $request) {
            if ($request->getRiderID() == $id) {
                $arr[] = $request;
            }
        }
        return $arr;
    }

    public function addRequest($info) {
        $this->_database->getXML()->attributes()->count = $this->_database->getXML()->attributes()->count + 1;
        $request = $this->_database->addNode("request");
        $request->addAttribute("id", $this->_database->getXML()->attributes()->count);
        $request->addAttribute("offer_id",$info['offer_id']);
        $request->addChild("driver_id", $info['driver_id']);
        $request->addChild("rider_id", $info['rider_id']);
        $dt = new DateTime("now", new DateTimeZone('America/Vancouver'));
        $request->addChild("request_time", $dt->format("Y-m-d H:i:s"));
        $request->addChild("msg", $info['msg']);
        return new Request($request);
    }

    public function removeRequest($id) {
        $this->getRequest($id)->remove();
    }
}

?>