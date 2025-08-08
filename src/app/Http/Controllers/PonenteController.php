<?php

namespace App\Http\Controllers;

use App\Models\Ponente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PonenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $ponentes = Ponente::all();
            return response()->json([
                'message' => 'Ponentes recuperados exitosamente',
                'data' => $ponentes,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar los ponentes',
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
                'biografia' => 'required|string',
                'especialidad' => 'required|string|max:255',
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'biografia.required' => 'La biografía es obligatoria',
                'especialidad.required' => 'La especialidad es obligatoria',
            ]);

            $ponente = Ponente::create([
                'nombre' => $request->nombre,
                'biografia' => $request->biografia,
                'especialidad' => $request->especialidad,
            ]);

            return response()->json([
                'message' => 'Ponente creado exitosamente',
                'data' => $ponente,
                'status' => 201
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación. Por favor verifica los datos enviados.',
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el ponente',
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
            $ponente = Ponente::findOrFail($id);
            return response()->json([
                'message' => 'Ponente encontrado exitosamente',
                'data' => $ponente,
                'status' => 200
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ponente no encontrado',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al buscar el ponente',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ponente $ponente): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'biografia' => 'sometimes|required|string',
                'especialidad' => 'sometimes|required|string|max:255',
            ]);

            $ponente->update($request->all());
            return response()->json([
                'message' => 'Ponente actualizado exitosamente',
                'data' => $ponente,
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
                'message' => 'Error al actualizar el ponente',
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
            $ponente = Ponente::findOrFail($id);
            $ponente->delete();
            return response()->json([
                'message' => 'Ponente eliminado exitosamente',
                'status' => 200
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ponente no encontrado',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el ponente',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Asignar ponente a un evento
     */
    public function asignarEvento(Request $request, string $ponenteId): JsonResponse
    {
        try {
            $request->validate([
                'evento_id' => 'required|exists:eventos,id'
            ]);

            $ponente = Ponente::findOrFail($ponenteId);
            
            // Verificar si ya está asignado
            if ($ponente->eventos()->where('evento_id', $request->evento_id)->exists()) {
                return response()->json([
                    'message' => 'El ponente ya está asignado a este evento',
                    'status' => 409
                ], 409);
            }
            
            $ponente->eventos()->attach($request->evento_id);

            return response()->json([
                'message' => 'Ponente asignado al evento exitosamente',
                'ponente_id' => $ponenteId,
                'evento_id' => $request->evento_id,
                'status' => 200
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ponente no encontrado',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al asignar el ponente al evento',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Desasignar ponente de un evento
     */
    public function desasignarEvento(Request $request, string $ponenteId): JsonResponse
    {
        try {
            $request->validate([
                'evento_id' => 'required|exists:eventos,id'
            ]);

            $ponente = Ponente::findOrFail($ponenteId);
            
            // Verificar si está asignado
            if (!$ponente->eventos()->where('evento_id', $request->evento_id)->exists()) {
                return response()->json([
                    'message' => 'El ponente no está asignado a este evento',
                    'status' => 409
                ], 409);
            }
            
            $ponente->eventos()->detach($request->evento_id);

            return response()->json([
                'message' => 'Ponente desasignado del evento exitosamente',
                'ponente_id' => $ponenteId,
                'evento_id' => $request->evento_id,
                'status' => 200
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ponente no encontrado',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al desasignar el ponente del evento',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
