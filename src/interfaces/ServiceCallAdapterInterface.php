<?php

namespace Mindwingx\ServiceCallAdapter\interfaces;

interface ServiceCallAdapterInterface
{
    public function call(array $payload = []);
}
