<?php

namespace App\Services;

use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Log;

class CounterService
{
    protected $session;
    protected $key = 'count';

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function getCurrentCount(): int
    {
        $value = (int) $this->session->get($this->key, 0);
        Log::debug('Getting current count', ['value' => $value, 'session_id' => $this->session->getId()]);
        return $value;
    }

    public function increment(): int
    {
        $currentCount = $this->getCurrentCount();
        $newCount = $currentCount + 1;
        
        $this->session->put($this->key, $newCount);
        $this->session->save();
        
        Log::debug('Incremented counter', [
            'from' => $currentCount, 
            'to' => $newCount,
            'session_id' => $this->session->getId()
        ]);
        
        return $newCount;
    }

    public function reset(): int
    {
        $oldCount = $this->getCurrentCount();
        $this->session->put($this->key, 0);
        $this->session->save();
        
        Log::debug('Reset counter', [
            'from' => $oldCount, 
            'to' => 0,
            'session_id' => $this->session->getId()
        ]);
        
        return 0;
    }
}
