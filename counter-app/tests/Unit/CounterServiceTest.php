<?php

namespace Tests\Unit;

use App\Services\CounterService;
use Illuminate\Session\SessionManager;
use Tests\TestCase;

class CounterServiceTest extends TestCase
{
  protected $sessionMock;
  protected $counterService;

  public function setUp(): void
  {
    parent::setUp();

    $this->sessionMock = \Mockery::mock(SessionManager::class);
    $this->sessionMock->shouldReceive('getId')
      ->andReturn('test-session-id');
    $this->counterService = new CounterService($this->sessionMock);
  }

  /** @test */
  public function it_gets_current_count()
  {
    $this->sessionMock->shouldReceive('get')
      ->with('count', 0)
      ->once()
      ->andReturn(5);

    $result = $this->counterService->getCurrentCount();

    $this->assertEquals(5, $result);
  }

  /** @test */
  public function it_increments_count()
  {
    $this->sessionMock->shouldReceive('get')
      ->with('count', 0)
      ->once()
      ->andReturn(5);

    $this->sessionMock->shouldReceive('put')
      ->with('count', 6)
      ->once();

    $this->sessionMock->shouldReceive('save')
      ->once();

    $result = $this->counterService->increment();
    $this->assertEquals(6, $result);
  }

  /** @test */
  public function it_resets_count()
  {
    $this->sessionMock->shouldReceive('get')
      ->with('count', 0)
      ->once()
      ->andReturn(10);

    $this->sessionMock->shouldReceive('put')
      ->with('count', 0)
      ->once();

    $this->sessionMock->shouldReceive('save')
      ->once();

    $result = $this->counterService->reset();

    $this->assertEquals(0, $result);
  }
}
