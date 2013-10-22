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
 * @method string translate($text, $arg1 = null, $arg2 = null, $_ = null) Usage: translate('%1\$s + %2\$s', $value1, $value2, $locale);
Plural example: translate(array('plural1', 'plural2', 2), 'pl')
 * @method string inflection(array $array, $string)
 * @method string message($msg)
 * @method string number($int, $precision = 2, $locale = null)
 * @method string currency($amount, $precision = 2, $locale = null) @todo check this
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
class My_View extends Zend_View
{
    /**
     * Whether or not to use streams to mimic short tags
     * @var bool
     */
    private $_useViewStream = false;

    /**
     * Script file name to execute
     *
     * @var string
     */
    private $_file = null;

    /**
     * Stack of Zend_View_Filter names to apply as filters.
     * @var array
     */
    private $_filter = array();

    /**
     * Variables assigned to the view
     *
     * @var array|mixed[]
     */
    protected $vars = array();

    /**
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * @return Zend_Controller_Request_Http
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        }

        return $this->_request;
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return $this
     */
    public function setRequest(Zend_Controller_Request_Http $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Given a base path, sets the script, helper, and filter paths relative to it
     *
     * Assumes a directory structure of:
     * <code>
     * basePath/
     *     scripts/
     *     helpers/
     *     filters/
     * </code>
     *
     * @param  string $path
     * @param string $classPrefix
     * @internal param string $prefix Prefix to use for helper and filter paths
     * @return Zend_View_Abstract
     */
    public function setBasePath($path, $classPrefix = 'Zend_View')
    {
        $path = rtrim($path, '/');
        $path = rtrim($path, '\\');
        $path .= DIRECTORY_SEPARATOR;
        $classPrefix = rtrim($classPrefix, '_') . '_';

        $this->setScriptPath($path);
        $this->addScriptPath($path . 'scripts');
        $this->setHelperPath($path . 'helpers', $classPrefix . 'Helper');
        $this->setFilterPath($path . 'filters', $classPrefix . 'Filter');

        return $this;
    }

    /**
     * Given a base path, add script, helper, and filter paths relative to it
     *
     * Assumes a directory structure of:
     * <code>
     * basePath/
     *     scripts/
     *     helpers/
     *     filters/
     * </code>
     *
     * @param  string $path
     * @param string $classPrefix
     * @internal param string $prefix Prefix to use for helper and filter paths
     * @return Zend_View_Abstract
     */
    public function addBasePath($path, $classPrefix = 'Zend_View')
    {
        $path = rtrim($path, '/');
        $path = rtrim($path, '\\');
        $path .= DIRECTORY_SEPARATOR;
        $classPrefix = rtrim($classPrefix, '_') . '_';

        $this->addScriptPath($path);
        $this->addScriptPath($path . 'scripts');
        $this->addHelperPath($path . 'helpers', $classPrefix . 'Helper');
        $this->addFilterPath($path . 'filters', $classPrefix . 'Filter');

        return $this;
    }

    /**
     * Processes a view script and returns the output.
     * It also extracts all assigned variables (overwrites on collision)
     *
     * @param string $name The script name to process.
     * @return string The script output.
     */
    public function render($name)
    {
        // find the script file name using the parent private method
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->_file = $this->_script($name);
        unset($name); // remove $name from local scope

        ob_start();
        $this->_run($this->_file);

        return $this->_filter(ob_get_clean()); // filter output
    }

    /**
     * @return mixed
     */
    protected function _run()
    {
        extract($this->getVars(), EXTR_OVERWRITE);
        if ($this->_useViewStream && $this->useStreamWrapper()) {
            /** @noinspection PhpIncludeInspection */
            include 'zend.view://' . func_get_arg(0);
        } else {
            /** @noinspection PhpIncludeInspection */
            include func_get_arg(0);
        }
    }

    /**
     * Applies the filter callback to a buffer.
     *
     * @param string $buffer The buffer contents.
     * @return string The filtered buffer.
     */
    private function _filter($buffer)
    {
        // loop through each filter class
        foreach ($this->_filter as $name) {
            // load and apply the filter class
            $filter = $this->getFilter($name);
            $buffer = call_user_func(array($filter, 'filter'), $buffer);
        }
        // done!
        return $buffer;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (!isset($this->vars[$key])) {
            trigger_error('Key "' . $key . '" does not exist', E_USER_NOTICE);
        }
        return isset($this->vars[$key]) ? $this->vars[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $val
     * @return $this|void
     */
    public function __set($key, $val)
    {
        $this->vars[$key] = $val;
        return $this;
    }

    /**
     * Assign variables to a view
     *
     * @param array|string $spec
     * @param mixed $value
     * @return $this|\Zend_View_Abstract
     * @throws Zend_View_Exception
     */
    public function assign($spec, $value = null)
    {
        if (is_string($spec)) {
            $this->vars[$spec] = $value;
        } elseif (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $this->vars[$key] = $val;
            }
        } else {
            $e = new Zend_View_Exception('assign() expects a string or array, received ' . gettype($spec));
            $e->setView($this);
            throw $e;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Clear all assigned variables
     *
     * @return $this
     */
    public function clearVars()
    {
        $this->vars = array();
        return $this;
    }
}