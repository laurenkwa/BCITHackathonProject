<?php
class User {
    private $_user;

    function __construct($user) {
        $this->_user = $user;
        
    }

    function getName() {
        return $this->_user->attributes()->name;
    }

    function getID() {
        return $this->_user->attributes()->id;
    }

    function getNotification() {

    }

    function addRequest($id) {
        $this->_user->requestlist->addChild("request", $id);
    }

    function removeRequest($id) {
        
    }

}
?>