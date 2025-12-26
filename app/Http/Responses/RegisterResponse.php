<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\HandlesCartIntent;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    use HandlesCartIntent;

    public function toResponse($request)
    {
        $this->handleCartIntent($request);

        $redirect = $this->sanitizeRedirect($request->input('redirect'));

        return redirect()->intended($redirect ?? config('fortify.home'));
    }
}
