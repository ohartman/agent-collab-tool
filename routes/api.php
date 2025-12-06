<?php

use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\DocumentController;
use Illuminate\Support\Facades\Route;

// Conversations
Route::get('conversations', [ConversationController::class, 'index']);
Route::post('conversations', [ConversationController::class, 'store']);
Route::get('conversations/{conversation}', [ConversationController::class, 'show']);

// Messages
Route::post('messages', [MessageController::class, 'store']);
Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);

// Documents
Route::post('documents', [DocumentController::class, 'store']);
Route::get('conversations/{conversation}/documents', [DocumentController::class, 'index']);
Route::get('documents/{document}/download', [DocumentController::class, 'download']);
