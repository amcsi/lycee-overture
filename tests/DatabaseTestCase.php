<?php
declare(strict_types=1);

namespace Tests;

use amcsi\LyceeOverture\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Abstract test class for being able to access the DB.
 */
abstract class DatabaseTestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Status asserter which gives more info.
     */
    protected static function assertStatus(int $status, TestResponse $response): TestResponse
    {
        try {
            return $response->assertStatus($status);
        } catch (ExpectationFailedException $expectationFailedException) {
            self::fail($expectationFailedException->getMessage() . "\nResponse:\n" . $response->baseResponse);
        }
    }

    /**
     * Asserts a 200 OK response and returns the response.
     * Otherwise throws an exception and outputs the repsonse.
     */
    protected static function assertOk(TestResponse $response)
    {
        try {
            return $response->assertOk();
        } catch (ExpectationFailedException $expectationFailedException) {
            self::fail($expectationFailedException->getMessage() . "\nResponse:\n" . $response->baseResponse);
        }
    }

    /**
     * Asserts a 200 OK response and returns the data in the response's "data" key.
     */
    protected static function assertSuccessfulResponseData(TestResponse $response)
    {
        try {
            return $response->assertSuccessful()->assertJsonStructure(['data'])->json('data');
        } catch (ExpectationFailedException $expectationFailedException) {
            self::fail($expectationFailedException->getMessage() . "\nResponse:\n" . $response->baseResponse);
        }
    }
}
