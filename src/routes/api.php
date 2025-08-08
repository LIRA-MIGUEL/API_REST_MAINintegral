<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Agregar el controlador EventoController
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PonenteController;
use App\Http\Controllers\AsistenteController;

/**
* Rutas para el recurso Evento.
*/
// Recuperar todos los eventos
Route::get('/eventos', [EventoController::class, 'index']);
// Almacenar un evento nuevo
Route::post('/eventos', [EventoController::class, 'store']);
// Recuperar un evento específico
Route::get('/eventos/{id}', [EventoController::class, 'show']);
// Actualizar un evento específico
Route::put('/eventos/{evento}', [EventoController::class, 'update']);
// Eliminar un evento específico
Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);

/**
* Rutas para el recurso Ponente.
*/
// Recuperar todos los ponentes
Route::get('/ponentes', [PonenteController::class, 'index']);
// Almacenar un ponente nuevo
Route::post('/ponentes', [PonenteController::class, 'store']);
// Recuperar un ponente específico
Route::get('/ponentes/{id}', [PonenteController::class, 'show']);
// Actualizar un ponente específico
Route::put('/ponentes/{ponente}', [PonenteController::class, 'update']);
// Eliminar un ponente específico
Route::delete('/ponentes/{id}', [PonenteController::class, 'destroy']);
// Asignar ponente a evento
Route::post('/ponentes/{id}/asignar-evento', [PonenteController::class, 'asignarEvento']);
// Desasignar ponente de evento
Route::delete('/ponentes/{id}/desasignar-evento', [PonenteController::class, 'desasignarEvento']);

/**
* Rutas para el recurso Asistente.
*/
// Recuperar todos los asistentes
Route::get('/asistentes', [AsistenteController::class, 'index']);
// Almacenar un asistente nuevo
Route::post('/asistentes', [AsistenteController::class, 'store']);
// Recuperar un asistente específico
Route::get('/asistentes/{id}', [AsistenteController::class, 'show']);
// Actualizar un asistente específico
Route::put('/asistentes/{asistente}', [AsistenteController::class, 'update']);
// Eliminar un asistente específico
Route::delete('/asistentes/{id}', [AsistenteController::class, 'destroy']);
// Obtener asistentes por evento
Route::get('/eventos/{id}/asistentes', [AsistenteController::class, 'porEvento']);