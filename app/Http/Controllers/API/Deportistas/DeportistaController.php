<?php
namespace App\Http\Controllers\API\Deportistas;
use App\Http\Controllers\Controller;
use App\Models\Deportista;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DeportistaController extends Controller
{
    public function index(Request $request)
    {
        $query = Deportista::with('usuario', 'categoria', 'posiciones', 'clubes');

        // Filtros
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('id_categoria')) {
            $query->where('id_categoria', $request->id_categoria);
        }

        if ($request->has('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('numero_documento', 'like', "%{$search}%");
            });
        }

        $deportistas = $query->paginate(15);
        return response()->json($deportistas);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Datos de usuario
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8',
            'id_rol' => 'required|exists:rols,id_rol',
            
            // Datos de deportista
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:masculino,femenino',
            'tipo_documento' => 'required|string|max:20',
            'numero_documento' => 'required|string|max:50|unique:deportistas,numero_documento',
            'id_categoria' => 'nullable|exists:categorias,id_categoria',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:150',
            'altura' => 'nullable|numeric|min:0|max:3',
            'peso' => 'nullable|numeric|min:0|max:200',
            'foto' => 'nullable|image|max:2048',
            
            // Posiciones
            'posiciones' => 'nullable|array',
            'posiciones.*' => 'exists:posiciones,id_posicion'
        ]);

        DB::beginTransaction();
        try {
            // Crear usuario
            $usuario = Usuario::create([
                'id_rol' => $request->id_rol,
                'nombre' => $request->nombres,
                'apellido' => $request->apellidos,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'status' => 'activo'
            ]);

            // Crear deportista
            $deportistaData = $request->except('password', 'email', 'id_rol', 'posiciones', 'foto');
            $deportistaData['id_usuario'] = $usuario->id_usuario;

            if ($request->hasFile('foto')) {
                $deportistaData['foto'] = $request->file('foto')->store('deportistas', 'public');
            }

            $deportista = Deportista::create($deportistaData);

            // Asignar posiciones
            if ($request->has('posiciones')) {
                $deportista->posiciones()->sync($request->posiciones);
            }

            DB::commit();

            return response()->json([
                'message' => 'Deportista creado exitosamente',
                'data' => $deportista->load('usuario', 'categoria', 'posiciones')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear deportista',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $deportista = Deportista::with([
            'usuario',
            'categoria',
            'posiciones',
            'clubes',
            'facturas',
            'lesiones' => function($query) {
                $query->where('estado', 'activa');
            },
            'estadisticas'
        ])->findOrFail($id);

        return response()->json($deportista);
    }

    public function update(Request $request, $id)
    {
        $deportista = Deportista::findOrFail($id);

        $request->validate([
            'nombres' => 'sometimes|string|max:100',
            'apellidos' => 'sometimes|string|max:100',
            'fecha_nacimiento' => 'sometimes|date',
            'genero' => 'sometimes|in:masculino,femenino',
            'tipo_documento' => 'sometimes|string|max:20',
            'numero_documento' => 'sometimes|string|max:50|unique:deportistas,numero_documento,' . $id . ',id_deportista',
            'id_categoria' => 'nullable|exists:categorias,id_categoria',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:150',
            'altura' => 'nullable|numeric|min:0|max:3',
            'peso' => 'nullable|numeric|min:0|max:200',
            'estado' => 'sometimes|in:activo,lesionado,suspendido,retirado',
            'foto' => 'nullable|image|max:2048',
            'posiciones' => 'nullable|array',
            'posiciones.*' => 'exists:posiciones,id_posicion'
        ]);

        $data = $request->except('foto', 'posiciones');

        if ($request->hasFile('foto')) {
            if ($deportista->foto) {
                \Storage::disk('public')->delete($deportista->foto);
            }
            $data['foto'] = $request->file('foto')->store('deportistas', 'public');
        }

        $deportista->update($data);

        // Actualizar posiciones
        if ($request->has('posiciones')) {
            $deportista->posiciones()->sync($request->posiciones);
        }

        return response()->json([
            'message' => 'Deportista actualizado exitosamente',
            'data' => $deportista->load('usuario', 'categoria', 'posiciones')
        ]);
    }

    public function destroy($id)
    {
        $deportista = Deportista::findOrFail($id);
        $deportista->delete();

        return response()->json([
            'message' => 'Deportista eliminado exitosamente'
        ]);
    }

    // Métodos adicionales
    public function estadisticas($id)
    {
        $deportista = Deportista::with('estadisticas')->findOrFail($id);
        
        $stats = [
            'total_partidos' => $deportista->estadisticas->sum('partidos_jugados'),
            'total_goles' => $deportista->estadisticas->sum('goles'),
            'total_asistencias' => $deportista->estadisticas->sum('asistencias'),
            'total_amarillas' => $deportista->estadisticas->sum('tarjetas_amarillas'),
            'total_rojas' => $deportista->estadisticas->sum('tarjetas_rojas'),
            'minutos_jugados' => $deportista->estadisticas->sum('minutos_jugados')
        ];

        return response()->json($stats);
    }

    public function lesiones($id)
    {
        $deportista = Deportista::findOrFail($id);
        $lesiones = $deportista->lesiones()->orderBy('fecha_lesion', 'desc')->get();
        
        return response()->json($lesiones);
    }

    public function clubActual($id)
    {
        $deportista = Deportista::findOrFail($id);
        $clubActual = $deportista->clubActual();
        
        return response()->json($clubActual);
    }
}