<?php
namespace App\Http\Controllers\API\Clubes;

use App\Http\Controllers\Controller;
use App\Models\Campeonato;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampeonatoController extends Controller
{
    public function index(Request $request)
    {
        $query = Campeonato::with('clubes');

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }

        $campeonatos = $query->paginate(15);
        return response()->json($campeonatos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'categoria' => 'required|string|max:50',
            'representante' => 'required|string|max:100',
            'email_representante' => 'nullable|email|max:100',
            'telefono_representante' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
            'reglas' => 'nullable|array'
        ]);

        $data = $request->except('imagen');
        $data['slug'] = Str::slug($request->nombre);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('campeonatos', 'public');
        }

        $campeonato = Campeonato::create($data);

        return response()->json([
            'message' => 'Campeonato creado exitosamente',
            'data' => $campeonato
        ], 201);
    }

    public function show($id)
    {
        $campeonato = Campeonato::with([
            'clubes',
            'partidos',
            'estadisticas'
        ])->findOrFail($id);

        return response()->json($campeonato);
    }

    public function update(Request $request, $id)
    {
        $campeonato = Campeonato::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'categoria' => 'sometimes|string|max:50',
            'representante' => 'sometimes|string|max:100',
            'email_representante' => 'nullable|email|max:100',
            'telefono_representante' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|in:planificado,en_curso,finalizado,cancelado',
            'imagen' => 'nullable|image|max:2048',
            'reglas' => 'nullable|array'
        ]);

        $data = $request->except('imagen');
        
        if ($request->filled('nombre')) {
            $data['slug'] = Str::slug($request->nombre);
        }

        if ($request->hasFile('imagen')) {
            if ($campeonato->imagen) {
                \Storage::disk('public')->delete($campeonato->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('campeonatos', 'public');
        }

        $campeonato->update($data);

        return response()->json([
            'message' => 'Campeonato actualizado exitosamente',
            'data' => $campeonato
        ]);
    }

    public function destroy($id)
    {
        $campeonato = Campeonato::findOrFail($id);
        $campeonato->delete();

        return response()->json([
            'message' => 'Campeonato eliminado exitosamente'
        ]);
    }

    // Métodos adicionales
    public function tablaPosiciones($id)
    {
        $campeonato = Campeonato::findOrFail($id);
        $tabla = $campeonato->getTablaPosiciones();
        
        return response()->json($tabla);
    }

    public function inscribirClub(Request $request, $id)
    {
        $request->validate([
            'id_club' => 'required|exists:clubes,id_club',
            'fecha_inscripcion' => 'required|date'
        ]);

        $campeonato = Campeonato::findOrFail($id);
        
        $campeonato->clubes()->attach($request->id_club, [
            'fecha_inscripcion' => $request->fecha_inscripcion,
            'estado' => 'inscrito',
            'puntos' => 0,
            'partidos_jugados' => 0,
            'partidos_ganados' => 0,
            'partidos_empatados' => 0,
            'partidos_perdidos' => 0,
            'goles_favor' => 0,
            'goles_contra' => 0
        ]);

        return response()->json([
            'message' => 'Club inscrito exitosamente'
        ]);
    }

    public function fixture($id)
    {
        $campeonato = Campeonato::with('partidos.clubLocal', 'partidos.clubVisitante', 'partidos.escenario')
                                ->findOrFail($id);
        
        return response()->json($campeonato->partidos);
    }

    public function goleadores($id)
    {
        $campeonato = Campeonato::findOrFail($id);
        
        $goleadores = $campeonato->estadisticas()
                                 ->with('deportista')
                                 ->selectRaw('id_deportista, SUM(goles) as total_goles')
                                 ->groupBy('id_deportista')
                                 ->orderBy('total_goles', 'desc')
                                 ->limit(10)
                                 ->get();
        
        return response()->json($goleadores);
    }
}