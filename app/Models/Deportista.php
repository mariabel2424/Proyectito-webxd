<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deportista extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'deportistas';
    protected $primaryKey = 'id_deportista';
    
    protected $fillable = [
        'id_usuario',
        'id_categoria',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'tipo_documento',
        'numero_documento',
        'foto',
        'direccion',
        'correo',
        'telefono',
        'altura',
        'peso',
        'pie_habil',
        'numero_camiseta',
        'estado',
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono',
        'contacto_emergencia_relacion',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'altura' => 'decimal:2',
        'peso' => 'decimal:2',
        'numero_camiseta' => 'integer'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function posiciones()
    {
        return $this->belongsToMany(Posicion::class, 'deportista_posiciones', 'id_deportista', 'id_posicion')
                    ->withPivot('principal')
                    ->withTimestamps();
    }

    public function jugadorClubes()
    {
        return $this->hasMany(JugadorClub::class, 'id_deportista', 'id_deportista');
    }

    public function clubes()
    {
        return $this->belongsToMany(Club::class, 'jugador_clubes', 'id_deportista', 'id_club')
                    ->withPivot('fecha_ingreso', 'fecha_salida', 'estado', 'numero_camiseta', 'observaciones')
                    ->withTimestamps();
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'id_deportista', 'id_deportista');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_deportista', 'id_deportista');
    }

    public function lesiones()
    {
        return $this->hasMany(Lesion::class, 'id_deportista', 'id_deportista');
    }

    public function estadisticas()
    {
        return $this->hasMany(EstadisticaJugador::class, 'id_deportista', 'id_deportista');
    }

    // Métodos auxiliares
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento->age;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function posicionPrincipal()
    {
        return $this->posiciones()->wherePivot('principal', true)->first();
    }

    public function clubActual()
    {
        return $this->jugadorClubes()
                    ->where('estado', 'activo')
                    ->whereNull('fecha_salida')
                    ->with('club')
                    ->first();
    }

    public function tienelesionActiva()
    {
        return $this->lesiones()->where('estado', 'activa')->exists();
    }
}
