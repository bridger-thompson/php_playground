<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounterControllerTest extends TestCase
{
    /** @test */
    public function it_displays_counter_page_with_initial_count()
    {
        $this->flushSession();
        
        $response = $this->get(route('counter.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('counter');
        $response->assertViewHas('count', 0);
    }
    
    /** @test */
    public function it_increments_counter_via_post_request()
    {
        $this->flushSession();
        $this->withSession(['count' => 5]);
        
        $response = $this->post(route('counter.increment'));
        
        $response->assertRedirect(route('counter.index'));
        $this->assertEquals(6, session('count'));
    }
    
    /** @test */
    public function it_resets_counter_via_post_request()
    {
        $this->flushSession();
        $this->withSession(['count' => 10]);
        
        $response = $this->post(route('counter.reset'));
        
        $response->assertRedirect(route('counter.index'));
        $this->assertEquals(0, session('count'));
    }
    
    /** @test */
    public function it_increments_counter_via_api_request()
    {
        $this->flushSession();
        $this->withSession(['count' => 7]);
        
        $response = $this->postJson(route('counter.increment'));
        
        $response->assertStatus(200);
        $response->assertJson(['count' => 8]);
        $this->assertEquals(8, session('count'));
    }
    
    /** @test */
    public function it_resets_counter_via_api_request()
    {
        $this->flushSession();
        $this->withSession(['count' => 15]);
        
        $response = $this->postJson(route('counter.reset'));
        
        $response->assertStatus(200);
        $response->assertJson(['count' => 0]);
        $this->assertEquals(0, session('count'));
    }
}
