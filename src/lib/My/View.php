<?php

/**
 * Custom extension for view
 * It must implement Zend_View_Interface
 *
 * @see Zend_View_Interface
 *
 * @method Zend_View_Helper_Navigation navigation()
 * @method string action($action, $controller, $module = null, array $params = array())
 * @method string route($routeName = null, array $options = null, $reset = false, $encode = true)
 * @method string baseUrl($relativePath = null)
 * @method string errors($errors, $message = null, $class = 'alert')
 * @method string translate($text, $arg1 = null, $arg2 = null, $_ = null) Usage: translate('%1\$s + %2\$s', $value1, $value2, $locale); Plural example: translate(array('plural1', 'plural2', 2), 'pl')
 * @method string inflection(array $array, $string)
 * @method string message($msg)
 * @method string number($int, $precision = 2, $locale = null)
 * @method string currency($amount, $precision = 2, $locale = null) @todo check this
 * @method Zend_Controller_Request_Http request()
 * @method string flashMessage()
 * @method string vatId($id)
 * @method string escape($value)
 * @method string absoluteUrl($url)
 * @method string formIban($name, $value = null, $attribs = null)
 * @method string formEmail($name, $value = null, $attribs = null)
 * @method string formText($name, $value = null, $attribs = null)
 * @method string formSelect($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n")
 * @method string paginationControl(Zend_Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
 * @method string fuzzyDates($timestamp = null, $timestamp2 = null, $singleValue = true)
 * @method string icon($iconName, $class = null)
 * @method string toLowercase($string, $encodinf = 'UTF-8')
 * @method string serverUrl($requestUri = null)
 * @method Zend_Layout layout() Get layout
 * @method Zend_View_Helper_HeadTitle headTitle($title = null, $type = 'APPEND') Available types: APPEND, PREPEND, SET
 * @method string url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
 *
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