<?php

namespace App\Http\Controllers;

use App\Models\Asistente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AsistenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $asistentes = Asistente::with('evento')->get();
            return response()->json([
                'message' => 'Asistentes recuperados exitosamente',
                'data' => $asistentes,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar los asistentes',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:asistentes,email',
                'telefono' => 'required|string|max:20',
                'evento_id' => 'required|exists:eventos,id',
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'email.required' => 'El email es obligatorio',
                'email.email' => 'El email debe tener un formato válido',
                'email.unique' => 'Este email ya está registrado',
                'telefono.required' => 'El teléfono es obligatorio',
                'evento_id.required' => 'El ID del evento es obligatorio',
                'evento_id.exists' => 'El evento especificado no existe. Verifica que el ID del evento sea correcto.',
            ]);

            $asistente = Asistente::create($request->all());
            return response()->json([
                'message' => 'Asistente creado exitosamente',
                'data' => $asistente->load('evento'),
                'status' => 201
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación. Por favor verifica los datos enviados.',
                'errors' => $e->errors(),
                'status' => 422,
                'help' => 'Si el error es sobre evento_id, verifica que exista un evento con ese ID usando GET /api/eventos'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el asistente',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $asistente = Asistente::with('evento')->findOrFail($id);
            return response()->json([
                'message' => 'Asistente encontrado exitosamente',
                'data' => $asistente,
                'status' => 200
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Asistente no encontrado',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al buscar el asistente',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistente $asistente): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:asistentes,email,' . $asistente->id,
                'telefono' => 'sometimes|required|string|max:20',
                'evento_id' => 'sometimes|required|exists:eventos,id',
            ]);

            $asistente->update($request->all());
            return response()->json([
                'message' => 'Asistente actualizado exitosamente',
                'data' => $asistente->load('evento'),
                'status' => 200
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el asistente',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $asistente = Asistente::findOrFail($id);
            $asistente->delete();
            return response()->json([
                'message' => 'Asistente eliminado exitosamente',
                'status' => 200
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Asistente no encontrado',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el asistente',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Obtener asistentes por evento
     */
    public function porEvento(string $eventoId): JsonResponse
    {
        try {
            $asistentes = Asistente::where('evento_id', $eventoId)->with('evento')->get();
            return response()->json([
                'message' => 'Asistentes del evento recuperados exitosamente',
                'data' => $asistentes,
                'evento_id' => $eventoId,
                'total' => $asistentes->count(),
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar los asistentes del evento',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
