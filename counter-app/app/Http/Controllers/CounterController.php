<?php

namespace App\Http\Controllers;

use App\Services\CounterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CounterController extends Controller
{
    protected $counterService;

    public function __construct(CounterService $counterService)
    {
        $this->counterService = $counterService;
    }

    public function index(Request $request)
    {
        $count = $this->counterService->getCurrentCount();
        return view('counter', compact('count'));
    }

    public function increment(Request $request)
    {
        $oldCount = $this->counterService->getCurrentCount();
        $newCount = $this->counterService->increment();
        
        Log::info('Counter incremented', [
            'old_count' => $oldCount,
            'new_count' => $newCount,
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'session_id' => $request->session()->getId()
        ]);
        
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') == 'application/json') {
            return response()->json([
                'count' => $newCount,
                'was' => $oldCount,
                'success' => true
            ]);
        }
        
        return redirect()->route('counter.index');
    }

    public function reset(Request $request)
    {
        $oldCount = $this->counterService->getCurrentCount();
        $this->counterService->reset();
        
        Log::info('Counter reset', [
            'old_count' => $oldCount,
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'session_id' => $request->session()->getId()
        ]);
        
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') == 'application/json') {
            return response()->json([
                'count' => 0,
                'was' => $oldCount,
                'success' => true
            ]);
        }
        
        return redirect()->route('counter.index');
    }
}
