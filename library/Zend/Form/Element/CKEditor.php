<?php

class Zend_Form_Element_CKEditor extends Zend_Form_Element_Textarea {

    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);
        $view = $this->getView();
        $view->headScript()->appendFile('/ckeditor/ckeditor.js', 'text/javascript');
        $this->setAttrib('class', 'ckeditor');
    }

}
