<?php

/**
 * Custom extension for view
 * It must implement Zend_View_Interface
 *
 * @see Zend_View_Interface
 */
class My_View extends Zend_View implements Zend_View_Interface
{
    /**
     * Automatically extract all variables from array to local scope
     * Warning: this will overwrite any existing variables with the same name !
     *
     * @return void
     */
    protected function _run()
    {
        extract($this->getVars(), EXTR_OVERWRITE);
        if ($this->_useViewStream && $this->useStreamWrapper()) {
            include 'zend.view://' . func_get_arg(0);
        } else {
            include func_get_arg(0);
        }
    }
}