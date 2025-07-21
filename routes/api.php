<?php

$apiRouteDir = __DIR__ . '/api';

foreach (glob($apiRouteDir . '/*.php') as $file) {
    require $file;
}
