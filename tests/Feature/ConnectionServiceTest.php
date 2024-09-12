<?php

namespace Tests\Feature;

use Tests\BaseTest;
use App\Providers\MultiChatServiceProvider;

class ConnectionServiceTest extends BaseTest
{
    protected MultiChatServiceProvider $app;

    protected function setUp(): void
    {
        // Initialize the service provider
        $this->app = MultiChatServiceProvider::getInstance();

        // Register shared connection service
        $this->app->registerSharedServices();
    }

    public function testSharedConnectionServiceRegistration()
    {
        // Assert that the 'connection' service is registered
        $this->assertTrue($this->app->has('connection'), "Connection service should be registered");
    }

    public function testConnectionServiceIsSingleton()
    {
        // Resolve the 'connection' service twice
        $connection1 = $this->app->resolve('connection');
        $connection2 = $this->app->resolve('connection');

        // Assert both instances are the same
        $this->assertSame($connection1, $connection2, "Connection service should return the same instance");
    }

    public function testConnectionServiceUniqueId()
    {
        // Resolve the 'connection' service twice
        $connection1 = $this->app->resolve('connection');
        $connection2 = $this->app->resolve('connection');

        // Get the unique IDs of both instances
        $id1 = $connection1->getConnectionId();
        $id2 = $connection2->getConnectionId();

        // Assert that both IDs are the same
        $this->assertEquals($id1, $id2, "Connection service should have the same unique ID for both instances");
    }
}
