<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupoEconomico;
use App\Http\Requests\GrupoEconomicoRequest;
use Illuminate\Support\Facades\Log;

class GrupoEconomicoController extends Controller
{
    protected $grupoEconomico;

    public function __construct(GrupoEconomico $grupoEconomico)
    {
        $this->grupoEconomico = $grupoEconomico;
    }

    public function index(Request $request)
    {
        try {
            $grupos = $this->grupoEconomico->all();
            $mensagem = $request->session()->get('mensagem');

            return view('grupos.index', compact('grupos', 'mensagem'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar grupos econômicos: ' . $e->getMessage());
            return back()->withErrors('Erro ao carregar os grupos econômicos. Tente novamente mais tarde.');
        }
    }

    public function show(GrupoEconomico $grupoEconomico)
    {
        try {
            return view('grupos.show', compact('grupoEconomico'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar grupo econômico: ' . $e->getMessage());
            return back()->withErrors('Erro ao carregar o grupo econômico. Tente novamente mais tarde.');
        }
    }

    public function create()
    {
        return view('grupos.create');
    }

    public function store(GrupoEconomicoRequest $request)
    {
        try {
            $this->grupoEconomico->create([
                'nome' => $request->nome,
                'usuario_cadastrante_id' => auth()->id(),
            ]);

            return redirect()->route('grupo-economico.index')->with('success', '✅ Grupo criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar grupo econômico: ' . $e->getMessage());
            return back()->withErrors('Erro ao criar o grupo econômico. Tente novamente mais tarde.');
        }
    }

    public function edit(GrupoEconomico $grupoEconomico)
    {
        return view('grupos.edit', compact('grupoEconomico'));
    }

    public function update(GrupoEconomicoRequest $request, GrupoEconomico $grupoEconomico)
    {
        try {
            $grupoEconomico->update([
                'nome' => $request->nome,
                'usuario_alterante_id' => auth()->id(),
            ]);
            return redirect()->route('grupo-economico.index')->with('success', '✅ Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar grupo econômico: ' . $e->getMessage());
            return back()->withErrors('Erro ao atualizar o grupo econômico. Tente novamente mais tarde.');
        }
    }

    public function destroy(GrupoEconomico $grupoEconomico)
    {
        try {
            $grupoEconomico->delete();
            return redirect()->route('grupo-economico.index')->with('success', '✅ Grupo excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir grupo econômico: ' . $e->getMessage());
            return back()->withErrors('Erro ao excluir o grupo econômico. Tente novamente mais tarde.');
        }
    }
}
