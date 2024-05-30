<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\MongoDb\Visitors;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VisitorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_visitor()
    {
        $visitor = Visitors::create([
            'ip' => '192.168.1.1',
            'country' => 'BG'
        ]);

        $this->assertDatabaseHas('visitors', [
            'ip' => '192.168.1.1',
            'country' => 'BG'
        ]);
    }
}
