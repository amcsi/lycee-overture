<?php
declare(strict_types=1);

/**
 * Configuration for the importer.
 */
return [
    'importBaseUrl' => 'https://lycee-tcg.com/card',
    'importQueryParameters' => ['limit' => '100000', 'output' => 'csv'],
];
