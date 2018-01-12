<?php
class Database {
    private $_xml;
    private $_file;

    function __construct($file) {
        $xmlDoc = file_get_contents($file);
        $xml = simplexml_load_string($xmlDoc) or die("Error: Cannot create object " . __LINE__);
        $this->_xml = $xml;
        $this->_file = $file;
    }

    function addNode($name) {
        $node = $this->_xml->addChild($name);
        return $node;
    }

    /**
     * remove a node with the given attribute and value.
     * 
     * @param ele -> name of the element
     * @param name -> the name of the attribute
     * @param value -> the value of the attribute
     * @return SimpleXMLElement -> Returns FALSE if no match item is found
     */
    function searchNode($ele, $name, $value) {
        foreach($this->_xml->children() as $item) {
            if($item->getName() == $ele && $item[$name] == $value) {
                return $item;
            }
        }
        return FALSE;
    }

    /**
     * remove a node with the given attribute and value.
     * 
     * @param ele -> name of the element
     * @param name -> the name of the attribute
     * @param value -> the value of the attribute
     * @return boolean -> TRUE on success and FALSE on failure
     */
    function removeNode($ele, $name, $value) {
        $item = $this->searchNode($ele, $name, $value);
        // return if no match item is found
        if (!$item) return FALSE;
        // remove the node from DOM
        $dom = dom_import_simplexml($item);
        $dom->parentNode->removeChild($dom);
        return TRUE;
    }

    function putIfAbsent($ele, $name, $value) {
        $node = $this->searchNode($ele, $name, $value);
        if ($node == FALSE) {
            $node = $this->addNode($ele);
            $node->addAttribute($name, $value);
        }
        return $node;
    }

    function size() {
        return $this->_xml->count();
    }

    function children() {
        return $this->_xml->children();
    }

    function saveDatabase() {
        if (!($this->_xml->saveXML($this->_file))) {
            echo "Error occurs when saving the ". $this->_file . "<br>";
        }
    }

    public function getXML() {
        return $this->_xml;
    }
}
?>