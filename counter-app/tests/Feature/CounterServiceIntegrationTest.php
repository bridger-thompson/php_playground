<?php

namespace Tests\Feature;

use App\Services\CounterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounterServiceIntegrationTest extends TestCase
{
    /** @test */
    public function it_increments_counter_multiple_times()
    {
        $this->flushSession();
        
        session(['count' => 10]);
        
        $counterService = app(CounterService::class);
        
        $result1 = $counterService->increment();
        $this->assertEquals(11, $result1);
        $this->assertEquals(11, session('count'));
        
        $result2 = $counterService->increment();
        $this->assertEquals(12, $result2);
        $this->assertEquals(12, session('count'));
        
        $result3 = $counterService->increment();
        $this->assertEquals(13, $result3);
        $this->assertEquals(13, session('count'));
    }
    
    /** @test */
    public function it_resets_counter_after_incrementing()
    {
        $this->flushSession();
        
        session(['count' => 5]);
        
        $counterService = app(CounterService::class);
        
        $result1 = $counterService->increment();
        $this->assertEquals(6, $result1);
        
        $result2 = $counterService->reset();
        $this->assertEquals(0, $result2);
        $this->assertEquals(0, session('count'));
        
        $result3 = $counterService->increment();
        $this->assertEquals(1, $result3);
        $this->assertEquals(1, session('count'));
    }
}
