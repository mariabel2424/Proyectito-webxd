<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Rol;

class RegisterController extends Controller
{
    /**
     * Registrar nuevo usuario
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validación de datos
            $validator = \Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'email' => 'required|email|unique:usuarios,email',
                'password' => 'required|string|min:8|confirmed',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string',
                'id_rol' => 'nullable|exists:rols,id_rol'
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'apellido.required' => 'El apellido es obligatorio',
                'email.required' => 'El email es obligatorio',
                'email.email' => 'El email no es válido',
                'email.unique' => 'Este email ya está registrado',
                'password.required' => 'La contraseña es obligatoria',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'id_rol.exists' => 'El rol seleccionado no existe'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Asignar rol por defecto si no se proporciona
            $idRol = $request->id_rol;
            if(!$idRol){
                $rolDeportista = Rol::where('slug', 'deportista')->first();
                $idRol = $rolDeportista ? $rolDeportista->id_rol : null;
            }

            if (!$idRol) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró un rol válido para asignar'
                ], 400);
            }

            // Crear usuario
            $usuario = Usuario::create([
                'id_rol' => $idRol,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'status' => 'activo'
            ]);

            // Generar token (opcional, si quieres login automático)
            $deviceName = $request->device_name ?? $request->userAgent();
            $token = $usuario->createToken($deviceName)->plainTextToken;

            // Cargar relaciones
            $usuario->load('rol');

            DB::commit();

            Log::info('Nuevo usuario registrado', [
                'usuario_id' => $usuario->id_usuario, 
                'email' => $usuario->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'id_usuario' => $usuario->id_usuario,
                    'nombre' => $usuario->nombre,
                    'apellido' => $usuario->apellido,
                    'email' => $usuario->email,
                    'rol' => $usuario->rol->nombre ?? null
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al registrar el usuario.',
                'error' => 'Intenta de nuevo más tarde.'
            ], 500);
        }
    }

    /**
     * Verificar email
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificarEmail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $existe = Usuario::where('email', $request->email)->exists();

        return response()->json([
            'disponible' => !$existe,
            'message' => $existe ? 'El email ya está en uso' : 'Email disponible'
        ]);
    }

    /**
     * Enviar código de verificación
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enviarCodigoVerificacion(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        // Generar código de 6 dígitos
        $codigo = random_int(100000, 999999);

        // Guardar código temporalmente
        cache()->put('verification_code_' . $usuario->id_usuario, $codigo, now()->addMinutes(10));

        // Aquí puedes enviar el código por email (esto debe implementarse en producción)
        // Mail::to($usuario->email)->send(new VerificationCodeMail($codigo));

        return response()->json([
            'message' => 'Código de verificación enviado',
        ]);
    }

    /**
     * Verificar código
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email',
            'codigo' => 'required|string|size:6'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();
        $codigoGuardado = cache()->get('verification_code_' . $usuario->id_usuario);

        if (!$codigoGuardado || $codigoGuardado != $request->codigo) {
            return response()->json([
                'message' => 'Código de verificación inválido o expirado'
            ], 400);
        }

        // Marcar email como verificado
        $usuario->update(['email_verified_at' => now()]);

        // Eliminar código del cache
        cache()->forget('verification_code_' . $usuario->id_usuario);

        return response()->json([
            'message' => 'Email verificado exitosamente'
        ]);
    }
}