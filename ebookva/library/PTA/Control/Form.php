<?php

abstract class PTA_Control_Form extends PTA_Object 
{
    protected $_elements = array();
    protected $_data = array();
    protected $_submited = false;
    
    /**
     * 
     */
    function __construct ($prefix, $title = '')
    {
        $this->setPrefix($prefix);
        
        $this->setName($prefix);
        $this->setMethod('post');
        $this->setEnctype('application/x-www-form-urlencoded');
        $this->setTitle($title);
        $this->setAction('?');
    }
    
    public function init()
    {
        parent::init();        

        $this->initForm();
        $this->_initVisualElements();
    }

    public function run()
    {
        parent::run();
        
        if ($this->submitted()) {
            $data = $this->_runVisualElements();
            $this->onSubmit($data);
        } else {
            $data = $this->onLoad();
            $this->_runVisualElements($data);
        }
    }
    
    private function _initVisualElements()
    {
        $elements = $this->getVisualAll();
        
        if (!empty($elements)) {
            foreach ($elements as $element) {
                $element->init();
            }
        }
    }
    
    private function _runVisualElements($data = null)
    {
        $elements = $this->getVisualAll();
        
        if (empty($data)) {
            $data = new stdClass();
        }

        $dataKeys = array_map('strtolower', array_keys((array)$data));
        
        if (!empty($elements)) {
            foreach ($elements as $element) {
                //list($name) = explode('[', $element->getName());
                $name = $element->getName();
//var_dump($element->getName(), $name);
                $fullName = $this->getPrefix() . "_$name";
                if (isset($data->{$name})) {
                    $value = $data->{$name};                    
                } else {
                	if ($this->submitted()) {
                		$value = $this->getHttpVar($fullName);
                	} else {
                		$value = $element->getValue();
                	}
                	
                }
               
                $element->setValue($value);
                $data->$name = $value; 

                $element->run();
            }
        }
        return $data;
    }
    
    public function initForm() {}
    
    public function onLoad() {}
    
    public function submitted()
    {
        $submit = $this->getVisualByType(PTA_Control_Form_Field::TYPE_SUBMIT , true);
        $submitImage = $this->getVisualByType(PTA_Control_Form_Field::TYPE_IMAGE, true);
        $submitElement = (empty($submit) ? $submitImage : $submit);
        
        if (!empty($submitElement)) {
            $httpSubmit = $this->getHttpVar($this->getPrefix() . '_' . $submitElement->getName());
            if (!empty($httpSubmit)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function onSubmit(&$data) {}
    
    /**
     * prepeare form for template
     *
     * @return stdClass
     */
    public function toString()
    {
        $object = parent::toString();
        
        $elements = $this->getVisualAll();
        $data = array();
        foreach ($elements as $element) {
            $element->setName($this->getPrefix() . '_' . $element->getName());
           	$data[$element->getPrefix()] = $element->toString();
        }
//var_dump($data);        
        usort($data, array($this, "sortData"));
        $object->data = $data;
         
        return $object;
    }
    
    public static function sortData($a, $b)
    {
        $orderA = (isset($a->sortOrder) ? $a->sortOrder : 0); 
        $orderB = (isset($b->sortOrder) ? $b->sortOrder : 0);
        
        if ( $orderA == $orderB) {
            return 0;
        }
        return ($orderA > $orderB) ? +1 : -1;
    }
    
    public function addVisual($element)
    {
        $element->setFormPrefix($this->getPrefix());
        $this->_elements[$element->getName()] = $element;
    }
    
    public function getMethod()
    {
        return $this->getVar('method');
    }
    
    public function setMethod($value)
    {
        $this->setVar('method', $value);
    }
    
    public function getEnctype()
    {
        return $this->getVar('enctype');
    }
    
    public function setEnctype($value)
    {
        $this->setVar('enctype', $value);
    }
    
    public function getCssClass()
    {
        return $this->getVar('cssClass');
    }
    
    public function setCssClass($value)
    {
        $this->setVar('cssClass', $value);
    }

    public function getTitle()
    {
        return $this->getVar('title');
    }
    
    public function setTitle($value)
    {
        $this->setVar('title', $value);
    }
    
    public function getName()
    {
        return $this->getVar('name');
    }
    
    public function setName($value)
    {
        $this->setVar('name', $value);
    }
    
    public function getAction()
    {
        return $this->getVar('action');
    }
    
    public function setAction($value)
    {
        $this->setVar('action', $value);
    }
    
    public function getVisualAll()
    {
        return $this->_elements;
    }
    
    public function getVisual($prefix)
    {
        return (isset($this->_elements[$prefix]) ? $this->_elements[$prefix] : null);
    }
    
    public function getVisualByType($type, $single = false)
    {
        $elements = $this->getVisualAll();
        
        $res = array();
        if (!empty($elements)) {
            foreach ($elements as $element) {
                if ($element->getFieldType() == $type) {
                    if ($single) {
                        return $element;
                    }
                    
                    $res[] = $element;
                }
            }
        }
        
        return $res;
    }
    
    public function validate($data)
    {
        $elements = $this->getVisualAll();
        $notValidElements = array();
        
        foreach ($elements as $element) {
            $name = $element->getName();
            if ($element->isMamdatory() && empty($data->$name)) {
                $notValidElements[] = $element;
            }
        }
        
        return $notValidElements;
    }
    
}
