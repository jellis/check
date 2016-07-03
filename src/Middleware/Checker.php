<?php

namespace Jellis\Check\Middleware;

use Closure;
use Jellis\Check\Check;
use Illuminate\Http\Request;

class Checker
{
    /**
     * @var Check
     */
    private $check;

    public function __construct(Check $check)
    {
        $this->check = $check;
    }

    /**
     * Allow the request to proceed if the user is allowed
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getName() && $this->check->can($request->route()->getName())) {
            return $next($request);
        }
        abort(403);
    }

}