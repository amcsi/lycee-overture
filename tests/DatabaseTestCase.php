<?php
declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Abstract test class for being able to access the DB.
 */
abstract class DatabaseTestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;
}
