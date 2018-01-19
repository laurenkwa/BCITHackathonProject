<?php
class Request {
    private $_request;
    private $_parser;

    function __construct($request) {
        $this->_request = $request;
        $this->_parser = Database::openFromNode($request);
    }

    function remove() {
        Database::removeChild($this->_request);
    }

    function getID() {
        return $this->_request->attributes()->id->__toString();
    }

    function getOfferID() {
        return $this->_request->attributes()->offer_id->__toString();
    }

    function getDriverID() {
        return $this->_request->driver_id->__toString();
    }

    function getRiderID() {
        return $this->_request->rider_id->__toString();
    }

    function getTime() {
        return $this->_request->request_time->__toString();
    }

    function getMsg() {
        return $this->_request->msg->__toString();
    }

}
?>