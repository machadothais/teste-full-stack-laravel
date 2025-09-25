<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Models\Unidade;
use App\Http\Requests\ColaboradorRequest;
use Illuminate\Support\Facades\Log;

class ColaboradorController extends Controller
{
    protected $colaborador;
    protected $unidade;

    public function __construct(Colaborador $colaborador, Unidade $unidade)
    {
        $this->colaborador = $colaborador;
        $this->unidade = $unidade;
    }

    public function index(Request $request)
    {
        try {
            $colaboradores = $this->colaborador->all();
            $unidades = $this->unidade->all();
            return view('colaborador.index', compact('colaboradores', 'unidades'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar colaboradores: ' . $e->getMessage());
            return back()->withErrors('Erro ao carregar colaboradores. Tente novamente mais tarde.');
        }
    }

    public function create()
    {
        return view('colaborador.create');
    }

    public function store(ColaboradorRequest $request)
    {
        try {
            $this->colaborador->create([
                'nome' => $request->nome,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'unidade_id' => $request->unidade_id,
                'usuario_cadastrante_id' => auth()->id(),
            ]);
            return redirect()->route('colaborador.index')->with('success', '✅ Colaborador criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar colaborador: ' . $e->getMessage());
            return back()->withErrors('Erro ao criar o colaborador. Tente novamente mais tarde.');
        }
    }

    public function show(Colaborador $colaborador)
    {
        try {
            return view('colaborador.show', compact('colaborador'));
        } catch (\Exception $e) {
            Log::error('Erro ao exibir colaborador: ' . $e->getMessage());
            return back()->withErrors('Erro ao exibir colaborador. Tente novamente mais tarde.');
        }
    }

    public function edit(Colaborador $colaborador)
    {
        $unidades = $this->unidade->all();
        return view('colaborador.edit', compact('colaborador', 'unidades'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        try {
            $colaborador->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'unidade_id' => $request->unidade_id,
                'usuario_alterante_id' => auth()->id(),
            ]);
            return redirect()->route('colaborador.index')->with('success', '✅ Colaborador atualizado com sucesso!');
        } catch (\Exception $e) {
            dd($e);
            Log::error('Erro ao atualizar colaborador: ' . $e->getMessage());
            return back()->withErrors('Erro ao atualizar colaborador. Tente novamente mais tarde.');
        }
    }

    public function destroy(Colaborador $colaborador)
    {
        try {
            $colaborador->delete();
            return redirect()->route('colaborador.index')->with('success', '✅ Colaborador excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir colaborador: ' . $e->getMessage());
            return back()->withErrors('Erro ao excluir o colaborador. Tente novamente mais tarde.');
        }
    }
}
