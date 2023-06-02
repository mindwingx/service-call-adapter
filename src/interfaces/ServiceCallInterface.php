<?php

namespace Mindwingx\ServiceCallAdapter\interfaces;

interface ServiceCallInterface
{
    public function preparePayload(array $payload);

    public function getResult();
}
