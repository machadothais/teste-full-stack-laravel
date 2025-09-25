<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bandeira;
use App\Models\GrupoEconomico;
use App\Http\Requests\BandeiraRequest;
use Illuminate\Support\Facades\Log;

class BandeiraController extends Controller
{
    protected $bandeira;
    protected $grupoEconomico;

    public function __construct(Bandeira $bandeira, GrupoEconomico $grupoEconomico)
    {
        $this->bandeira = $bandeira;
        $this->grupoEconomico = $grupoEconomico;
    }

    public function index(Request $request)
    {
        try {
            $bandeiras = $this->bandeira->all();
            $grupos = $this->grupoEconomico->all();
            $mensagem = $request->session()->get('mensagem');

            return view('bandeira.index', compact('bandeiras', 'mensagem', 'grupos'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar a lista de bandeiras: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao carregar a lista de bandeiras.');
        }
    }

    public function create()
    {
        return view('bandeiras.create');
    }

    public function store(BandeiraRequest $request)
    {
        try {
            $this->bandeira->create([
                'nome' => $request->nome,
                'grupo_economico_id' => $request->grupo_economico_id,
                'usuario_cadastrante_id' => auth()->id(),
            ]);

            return redirect()->route('bandeira.index')->with('success', '✅ Bandeira criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar bandeira: ' . $e->getMessage());
            return back()->withErrors('Erro ao criar a bandeira. Tente novamente mais tarde.');
        }
    }

    public function show(Bandeira $bandeira)
    {
        return view('bandeiras.show', compact('bandeira'));
    }

    public function edit(Bandeira $bandeira)
    {
        try {
            $slot = 'Editar Bandeira';
            $header = 'Editar Bandeira';
            $grupos = $this->grupoEconomico->all();

            return view('bandeira.edit', compact('bandeira', 'grupos', 'header', 'slot'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar a bandeira para edição: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao carregar a bandeira para edição.');
        }
    }

    public function update(BandeiraRequest $request, Bandeira $bandeira)
    {
        try {
            $bandeira->update([
                'nome' => $request->nome,
                'grupo_economico_id' => $request->grupo_economico_id,
                'usuario_alterante_id' => auth()->id(),
            ]);

            return redirect()->route('bandeira.index')->with('success', '✅ Bandeira atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar bandeira: ' . $e->getMessage());
            return back()->withErrors('Erro ao atualizar a bandeira. Tente novamente mais tarde.');
        }
    }

    public function destroy(Bandeira $bandeira)
    {
        try {
            $bandeira->delete();

            return redirect()->route('bandeira.index')->with('success', '✅ Bandeira excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir bandeira: ' . $e->getMessage());
            return back()->withErrors('Erro ao excluir a bandeira. Tente novamente mais tarde.');
        }
    }
}
