<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidade;
use App\Models\Bandeira;
use App\Http\Requests\UnidadeRequest;
use Illuminate\Support\Facades\Log;

class UnidadeController extends Controller
{
    protected $unidade;
    protected $bandeira;

    public function __construct(Unidade $unidade, Bandeira $bandeira)
    {
        $this->unidade = $unidade;
        $this->bandeira = $bandeira;
    }

    public function index(Request $request)
    {
        try {
            $unidades = $this->unidade->all();
            $bandeiras = $this->bandeira->all();
            $mensagem = $request->session()->get('mensagem');

            return view('unidades.index', compact('unidades', 'bandeiras', 'mensagem'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar unidades: ' . $e->getMessage());
            return back()->withErrors('Erro ao carregar as unidades. Tente novamente mais tarde.');
        }
    }

    public function create()
    {
        return view('unidades.create');
    }

    public function store(UnidadeRequest $request)
    {
        try {
            $this->unidade->create([
                'nome' => $request->nome,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
                'cnpj' => $request->cnpj,
                'bandeira_id' => $request->bandeira_id,
                'usuario_cadastrante_id' => auth()->id(),
            ]);

            return redirect()->route('unidades.index')->with('success', '✅ Unidade criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar unidade: ' . $e->getMessage());
            return back()->withErrors('Erro ao criar a unidade. Tente novamente mais tarde.');
        }
    }

    public function show(Unidade $unidade,Request $request)
    {
        try {
            $bandeiras = $this->bandeira->all();
            $mensagem = $request->session()->get('mensagem');

            return view('unidades.show', compact('unidade', 'bandeiras', 'mensagem'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar unidade: ' . $e->getMessage());
            return back()->withErrors('Erro ao carregar a unidade. Tente novamente mais tarde.');
        }
    }

    public function edit(Unidade $unidade)
    {
        try {
            $bandeiras = $this->bandeira->all();
            return view('unidades.edit', compact('unidade', 'bandeiras'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar unidade para edição: ' . $e->getMessage());
            return back()->withErrors('Erro ao carregar a unidade para edição. Tente novamente mais tarde.');
        }
    }

    public function update(Request $request, Unidade $unidade)
    {
        try {
            $unidade->update([
                'nome' => $request->nome,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
                'cnpj' => $request->cnpj,
                'bandeira_id' => $request->bandeira_id,
                'usuario_alterante_id' => auth()->id(),
            ]);

            return redirect()->route('unidades.index')->with('success', '✅ Unidade atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar unidade: ' . $e->getMessage());
            return back()->withErrors('Erro ao atualizar a unidade. Tente novamente mais tarde.');
        }
    }

    public function destroy(Unidade $unidade)
    {
        try {
            $unidade->delete();
            return redirect()->route('unidades.index')->with('success', '✅ Unidade excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir unidade: ' . $e->getMessage());
            return back()->withErrors('Erro ao excluir a unidade. Tente novamente mais tarde.');
        }
    }
}
