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
     * @param path Ex. when searches <a><b><c>, input 'a/b/c'
     * @param attr must be a dict (attr => value, ...)
     * @return array of SimpleXMLelements
     */
    function searchNodes($path, $value = NULL, $attr = NULL) {
        $result = $this->_xml->xpath($path);
        $matches = [];
        foreach ($result as $node) {
            if ($attr) {
                foreach (array_keys($attr) as $key) { 
                    $flag = ($value != NULL && $node->__toString() == $value) || $value == NULL; 
                    if ($node->attributes()[$key] != $attr[$key]) {
                        $flag = false;
                        break;
                    }
                }
                if ($flag) $matches[] = $node;
            } else {
                if (($value != NULL && $node->__toString() == $value) || $value == NULL)
                        $matches[] = $node;
            }
        }
        return $matches;
    }

    /**
     * @param path Ex. when searches <a><b><c>, input 'a/b/c'
     * @param attr must be a dict (attr => value, ...)
     * @return array of SimpleXMLelements
     */
    function removeNodes($path, $value = NULL, $attr = NULL) {
        $result = $this->searchNodes($path, $value, $attr);
        foreach ($result as $node) {
            $dom = dom_import_simplexml($node);
            $dom->parentNode->removeChild($dom);
        }
    }

    static function removeChild($item) {
        if (!$item) return FALSE;
        // remove the node from DOM
        $dom = dom_import_simplexml($item);
        $dom->parentNode->removeChild($dom);
        return TRUE;
    }

    function putIfAbsent($name, $value = NULL, $attr = NULL) {
        $node = $this->searchNodes($name, $value, $attr);
        if (empty($node)) {
            if ($value == NULL) 
                $node = $this->addNode($name);
            else
                $node = $this->addNode($name, $value);
            if ($attr != NULL) {
                foreach (array_keys($attr) as $key) {
                    $node->addAttribute($key, $attr[$key]);
                }
            }
        } else {
            $node = NULL;
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

    function getXML() {
        return $this->_xml;
    }
}

class Node {
    private $_path;
    private $_value;
    private $_attr;

    public function __construct($path, $value, $attr) {
        $this->_path = $path;
        $this->_value = $value;
        $this->_attr = $attr;
    }

    public function getPath() {
        return $this->_path;
    }

    public function getValue() {
        return $this->_value;
    }

    public function getAttr() {
        return $this->_attr;
    }
}
?>