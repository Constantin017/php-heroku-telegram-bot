<?php

namespace Application\Route;

/**
 * Route
 */
class Route
{
    protected $route = '';
    protected $access = false;
    
    /**
     * __construct
     *
     * @param mixed $access 
     * @param mixed $route 
     * 
     * @return void
     */
    public function __construct(bool $access, string $route)
    {
        $this->access = $access;
        $this->route = $route;
    }
    
    /**
     * Get request string.
     *
     * @return void
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * Check is access required for execution.
     *
     * @return bool
     */
    public function isAccessRequired()
    {
        return $this->access;
    }
    
    /**
     * Main execute function.
     *
     * @return void
     */
    public function execute()
    {

    }
}