<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user()->load(["personal"]),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $usuario = Auth::user();
        $request->validate([
            'password_actual' => ['required', function ($attribute, $value, $fail) use ($usuario, $request) {
                if (!\Hash::check($request->password_actual, $usuario->password)) {
                    return $fail(__('La contraseña no coincide con la actual.'));
                }
            }],
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required|min:5'
        ], [
            "password_actual.required" => "Debes ingresar la contraña actual"
        ]);

        DB::beginTransaction();
        try {
            $usuario->password = Hash::make($request->password);
            $usuario->save();
            DB::commit();
            return Redirect::route('profile.edit')->with("bien", "Perfil actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back(500)->with("error", $e->getMessage());
        }
    }

    public function update_foto(Request $request)
    {
        $usuario = Auth::user();
        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                $antiguo = $usuario->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/personals/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $usuario->usuario . '.' . $file->getClientOriginalExtension();
                $usuario->foto = $nom_foto;
                $file->move(public_path() . '/imgs/personals/', $nom_foto);
            }
            $usuario->save();

            if ($usuario->personal) {
                $usuario->personal->foto = $usuario->foto;
                $usuario->personal->save();
            }

            DB::commit();
            return Redirect::route('profile.edit')->with("bien", "Perfil actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back(500)->with("error", $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
