<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UpdateProfileController extends Controller
{
    //
    public function edit(){

        return Inertia::render('Settings/UpdateProfile');

    }

    public function update(UpdateProfileRequest $request){
        $user = Auth::user();

        $user->update($request->validated());

        return redirect()->route('settings.profile')->with('success', 'Perfil actualizado exitosamente');
    }
}
