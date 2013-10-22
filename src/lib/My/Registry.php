<?php

/**
 * Zend_Registry replacement using Pimple (My_ServiceManager)
 *
 * @see Pimple
 * @see My_ServiceManager
 * @see Zend_Registry
 */
class My_Registry extends Zend_Registry
{
    /**
     * @var Pimple
     */
    protected $pimple;

    /**
     * @var ArrayObject
     */
    private static $_registry;

    /**
     * @param Pimple $container
     */
    public function __construct(Pimple $container = null)
    {
        if(!$container){
            $container = new Pimple();
        }
        $this->setContainer($container);
        parent::__construct();
    }

    /**
     * Retrieves the default registry instance.
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (self::$_registry === null) {
            self::init();
        }
        return self::$_registry;
    }

    /**
     * Initialize the default registry instance.
     *
     * @return void
     */
    protected static function init()
    {
        self::$_registry = new self();
        self::setInstance(self::$_registry);
    }

    /**
     * @param string $index
     * @return mixed
     */
    public static function get($index)
    {
        return self::getInstance()->offsetGet($index);
    }

    /**
     * @param string $index
     * @param mixed  $value
     */
    public static function set($index, $value)
    {
        self::getInstance()->offsetSet($index, $value);
    }

    /**
     * @return My_ServiceManager
     */
    public function getContainer()
    {
        return $this->pimple;
    }

    /**
     * @param Pimple $pimple
     */
    public function setContainer(Pimple $pimple)
    {
        $this->pimple = $pimple;
    }

    /**
     * @param callable $callable
     * @return \Closure
     */
    public static function share(\Closure $callable)
    {
        return self::getInstance()->getContainer()->share($callable);
    }

    /**
     * @param string $index
     * @return bool
     */
    public function offsetExists($index)
    {
        return $this->getContainer()->offsetExists($index);
    }

    /**
     * @param string $index
     * @return mixed
     */
    public function offsetGet($index)
    {
        return $this->getContainer()->offsetGet($index);
    }

    /**
     * @param mixed $index
     * @param mixed $newval
     */
    public function offsetSet($index, $newval)
    {
        $this->getContainer()->offsetSet($index, $newval);
    }

    /**
     * @param mixed $index
     */
    public function offsetUnset($index)
    {
        $this->getContainer()->offsetUnset($index);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getContainer()->keys());
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator((array) $this->getContainer());
    }

    /**
     * Unset singleton instance
     */
    public static function _unsetInstance()
    {
        self::$_registry = null;
        parent::_unsetInstance();
    }
}