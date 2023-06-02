<?php

namespace Mindwingx\ServiceCallAdapter;

class ServiceCall extends AbstractServiceCall
{
    /**
     * @return static
     */
    public static function handle(): static
    {
        return new static();
    }
}
