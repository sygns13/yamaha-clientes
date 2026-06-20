<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UsuariosController extends Controller
{
    /**
     * Muestra la lista paginada de usuarios.
     * Usa UsuarioResource para transformar cada modelo de forma estandarizada.
     */
    public function index(): Response
    {
        $usuarios = User::orderBy('name')
            ->paginate(15);

        return Inertia::render('Admin/Usuarios/Index', [
            // UsuarioResource::collection conserva la paginación de Laravel
            'usuarios' => UsuarioResource::collection($usuarios),
        ]);
    }

    /**
     * Crea un nuevo usuario con contraseña hasheada.
     */
    public function store(StoreUsuarioRequest $request): RedirectResponse
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'activo'   => $request->boolean('activo', true),
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Actualiza datos del usuario. La contraseña solo se cambia si se envía.
     */
    public function update(UpdateUsuarioRequest $request, User $usuario): RedirectResponse
    {
        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'activo' => $request->boolean('activo', true),
        ];

        // Solo actualizar contraseña si el campo viene relleno
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Activa o desactiva un usuario. No se permite sobre uno mismo.
     */
    public function toggleActivo(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        $estado = $usuario->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario {$estado} correctamente.");
    }

    /**
     * Elimina (soft delete) un usuario. No se permite sobre uno mismo.
     */
    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
