<?php
namespace App\Http\Controllers\API\Finanzas;
use App\Http\Controllers\Controller;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with('factura');

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('id_factura')) {
            $query->where('id_factura', $request->id_factura);
        }

        if ($request->has('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->paginate(15);
        return response()->json($pagos);
    }

    public function show($id)
    {
        $pago = Pago::with('factura')->findOrFail($id);
        return response()->json($pago);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'estado' => 'sometimes|in:verificado,pendiente,rechazado',
            'observaciones' => 'nullable|string'
        ]);

        $pago->update($request->all());

        return response()->json([
            'message' => 'Pago actualizado exitosamente',
            'data' => $pago
        ]);
    }

    public function verificar($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->verificar();

        return response()->json([
            'message' => 'Pago verificado exitosamente',
            'data' => $pago->fresh()
        ]);
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string'
        ]);

        $pago = Pago::findOrFail($id);
        $pago->update([
            'estado' => 'rechazado',
            'observaciones' => $request->observaciones
        ]);

        return response()->json([
            'message' => 'Pago rechazado',
            'data' => $pago
        ]);
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        if ($pago->estado === 'verificado') {
            return response()->json([
                'message' => 'No se puede eliminar un pago verificado'
            ], 400);
        }

        $pago->delete();

        return response()->json([
            'message' => 'Pago eliminado exitosamente'
        ]);
    }
}