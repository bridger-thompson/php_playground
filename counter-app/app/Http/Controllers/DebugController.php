<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DebugController extends Controller
{
    public function showSession(Request $request)
    {
        return response()->json([
            'session' => [
                'id' => $request->session()->getId(),
                'count' => session('count'),
                'all' => $request->session()->all()
            ],
            'cookies' => $request->cookies->all(),
            'routes' => [
                'counter.increment' => route('counter.increment'),
                'counter.reset' => route('counter.reset'),
                'ajax.counter.increment' => route('ajax.counter.increment'),
                'ajax.counter.reset' => route('ajax.counter.reset'),
            ],
            'request' => [
                'ajax' => $request->ajax(),
                'wantsJson' => $request->wantsJson(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'headers' => $request->headers->all()
            ]
        ]);
    }
}
