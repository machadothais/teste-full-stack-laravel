<?php

namespace App\Http\Controllers;

use App\Jobs\ExportarRelatorio;
use App\Models\Colaborador;
use App\Models\Relatorios;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index()
    {
        $colaborador = Colaborador::all();
        $total = $colaborador->sum('valor');
        return view('relatorios.index', compact('colaborador', 'total'));
    }

    public function gerarPDF(Request $request)
    {

        $filter1 = $request->input('filter1');
        $filter2 = $request->input('filter2');

        $reports = Colaborador::all();

        $data = [
            'title' => 'Relatório de Colaboradores',
            'date' => date('m/d/Y'),
            'depesas' => \App\Models\Colaborador::all()
        ];

        $pdf = FacadePdf::loadView('relatorios.pdf', compact('data', 'reports'));
        return $pdf->download('relatorio.pdf');
    }


    public function filter(Request $request)
    {
        $query = Colaborador::query();

        if ($request->has('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', $request->input('email'));
        }

        $colaborador = $query->orderBy('created_at', 'desc')->get();

        return view('relatorios.pdf', compact('colaborador'));
    }

    public function exportarPDF()
    {
        $dados = Relatorios::all();
        $pdf = FacadePdf::loadView('relatorios.pdf', compact('dados'));
        return $pdf->download('relatorio-colaborador.pdf');
    }

    public function comparar(Request $request)
    {
        $periodo1 = $request->input('periodo1');
        $periodo2 = $request->input('periodo2');

        $colaboradorPeriodo1 = Colaborador::whereMonth('data', date('m', strtotime($periodo1)))
            ->whereYear('data', date('Y', strtotime($periodo1)))
            ->sum('valor');

        $colaboradorPeriodo2 = Colaborador::whereMonth('data', date('m', strtotime($periodo2)))
            ->whereYear('data', date('Y', strtotime($periodo2)))
            ->sum('valor');

        return view('relatorios.comparacao', compact('colaboradorPeriodo1', 'colaboradorPeriodo2', 'periodo1', 'periodo2'));
    }


    public function exportReport(Request $request)
    {
        $employeeIds = $request->input('employee_ids');

        ExportarRelatorio::dispatch($employeeIds);

        return response()->json(['message' => 'Exportação de relatório iniciada.']);
    }
}
