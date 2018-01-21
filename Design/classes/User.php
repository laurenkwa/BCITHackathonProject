<?php
class User {
    private $_user;
    private $_parser;

    function __construct($user) {
        $this->_user = $user;
        $this->_parser = Database::openFromNode($user);
    }

    function getName() {
        return $this->_user->attributes()->name->__toString();
    }

    function getID() {
        return $this->_user->attributes()->id->__toString();
    }

    function addNotification($title, $msg) {
        $this->_user->notification->attributes()->count = $this->_user->notification->attributes()->count + 1;
        $msg = $this->_user->notification->addChild("msg", $msg);
        $msg->addAttribute("id", $this->_user->notification->attributes()->count);
        $msg->addAttribute("checked", false);
        $msg->addAttribute("title", $title);
        $dt = new DateTime("now", new DateTimeZone('America/Vancouver'));
        $msg->addAttribute("time", $dt->format("Y-m-d H:i:s"));

        return $msg;
    }

    function getAllNotification($checked = NULL) {
        if ($checked)
            $arr = $this->_parser->searchNodes("notification/msg", NULL, array("checked" => $checked));
        else
            $arr = $this->_parser->searchNodes("notification/msg");
        $arr = array_reverse($arr);
        return $arr;
    }

    function getNotificationByID($id) {
        $result = $this->_parser->searchNodes("notification/msg", NULL, array("id" => $id));
        if (sizeof($result) == 1) {
            return $result[0];
        }
        return NULL;
    }

    function addRequest($id) {
        return $this->_user->requestlist->addChild("request")->addAttribute("id", $id);
    }

    function hasRequest($id) {
        $result = $this->_parser->searchNodes("requestlist/request", NULL, array("id" => $id));
        if (sizeof($result) == 1) {
            return $result[0];
        }
        return NULL;
    }

    function removeRequest($id) {
        $request = $this->hasRequest($id);
        return Database::removeChild($request);
    }

    function addReceived($id) {
        return $this->_user->receivedlist->addChild("received")->addAttribute("id", $id);
    }

    function hasReceived($id) {
        $result = $this->_parser->searchNodes("receivedlist/received", NULL, array("id" => $id));
        if (sizeof($result) == 1) {
            return $result[0];
        }
        return NULL;
    }

    function removeReceived($id) {
        $received = $this->hasReceived($id);
        return Database::removeChild($received);
    }

}
?>