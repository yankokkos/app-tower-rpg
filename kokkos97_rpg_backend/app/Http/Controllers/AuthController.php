<?php

namespace App\Http\Controllers;

use App\Models\Usuario; // Esta linha deve estar no início do arquivo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'Nome' => 'required|string|max:255',
            'senha' => 'required|string|min:6',
        ]);

        $user = Usuario::create([
            'Nome' => $request->Nome,
            'senha' => Hash::make($request->senha),
            'função' => 3, // 3 para visitante
        ]);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'Nome' => 'required|string',
                'senha' => 'required|string',
            ]);

            $usuario = Usuario::where('Nome', $request->Nome)->first();

            if ($usuario && Hash::check($request->senha, $usuario->senha)) {
                return response()->json(['success' => true, 'user' => $usuario]);
            }

            return response()->json(['success' => false, 'message' => 'Nome de usuário ou senha inválidos.'], 401);
        } catch (\Exception $e) {
            return response()->view('errors.errors', ['message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        // Busca todos os usuários
        $usuarios = Usuario::all();

        // Retorna os usuários em formato JSON
        return response()->json(['success' => true, 'usuarios' => $usuarios]);
    }
}