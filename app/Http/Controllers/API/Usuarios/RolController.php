<?php
namespace App\Http\Controllers\API\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RolController extends Controller
{
    // Listar TODOS los roles sin paginación (para selects)
    public function all()
    {
        $roles = Rol::where('activo', true)
                    ->select('id_rol', 'nombre', 'slug')
                    ->orderBy('nombre')
                    ->get();
        
        return response()->json($roles, 200);
    }

    // Listar roles con permisos (paginación) - para admin
    public function index()
    {
        $roles = Rol::with('permisos')->paginate(15);
        return response()->json($roles, 200);
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:rols,nombre',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'activo' => $request->activo ?? true
        ]);

        // Asignar permisos si vienen en la petición
        if ($request->has('permisos')) {
            $rol->permisos()->sync($request->permisos);
        }

        return response()->json([
            'message' => 'Rol creado exitosamente',
            'data' => $rol->load('permisos')
        ], 201);
    }

    // Mostrar un rol específico
    public function show($id)
    {
        $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
        return response()->json($rol, 200);
    }

    // Actualizar un rol
    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rols')->ignore($id, 'id_rol')
            ],
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $rol->update([
            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'activo' => $request->activo ?? $rol->activo
        ]);

        // Actualizar permisos
        if ($request->has('permisos')) {
            $rol->permisos()->sync($request->permisos);
        }

        return response()->json([
            'message' => 'Rol actualizado exitosamente',
            'data' => $rol->load('permisos')
        ], 200);
    }

    // Eliminar un rol
    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json([
            'message' => 'Rol eliminado exitosamente'
        ], 200);
    }
}
