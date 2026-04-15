<?php
declare(strict_types=1);

/**
 * Configuration for the importer.
 */
return [
    'importBaseUrl' => 'https://lycee-tcg.com/card',
    // Base query parameters; pagination parameters are added at call time.
    'importQueryParameters' => ['output' => 'csv'],
];
