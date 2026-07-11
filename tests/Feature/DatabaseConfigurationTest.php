<?php

namespace Tests\Feature;

use Tests\TestCase;

class DatabaseConfigurationTest extends TestCase
{
    public function test_application_uses_sqlite_database_for_local_development(): void
    {
        $this->assertSame('sqlite', config('database.default'));
    }
}
