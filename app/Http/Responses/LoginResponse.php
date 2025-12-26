<?php

namespace App\Http\Responses;

use App\Http\Responses\Concerns\HandlesCartIntent;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    use HandlesCartIntent;

    public function toResponse($request)
    {
        $this->handleCartIntent($request);

        $redirect = $this->sanitizeRedirect($request->input('redirect'));

        return redirect()->intended($redirect ?? config('fortify.home'));
    }
}
