<?php

    namespace Tests;

    use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Schema;

    abstract class TestCase extends BaseTestCase
    {
        use CreatesApplication;

        protected function setUp() : void
        {
            parent::setUp();

            if(!Schema::hasTable('users')) {
                Artisan::call('migrate:fresh');
            }
        }
    }
