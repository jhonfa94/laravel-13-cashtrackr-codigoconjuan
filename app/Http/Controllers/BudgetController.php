<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

#[Middleware('auth')]
#[Middleware('verified')]
class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $budgets = Auth::user()->budgets;

        return view('dashboard', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('budgets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BudgetRequest $request)
    {
        $data = $request->validated();

        $user_id = auth()->user()->id;


        // forma explicita de hacerlo
        // $budget = Budget::create([
        //     'name' => $data['name'],
        //     'amount' => $data['amount'],
        //     'type' => $data['type'],
        //     'user_id' => $user_id,
        // ]);

        // forma recomendada
        $budget = Auth::user()->budgets()->create($data);

        return redirect()->route('dashboard')->with('success', 'Presupuesto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        //
    }

    #[Authorize('update', 'budget')]
    public function edit(Budget $budget)
    {
        //
        return view('budgets.edit', compact('budget'));
    }

    #[Authorize('update', 'budget')]
    public function update(BudgetRequest $request, Budget $budget)
    {
        //
        $budget->update($request->validated());
        // $data = $request->validated();

        // $budget->update($data);

        return redirect()->route('dashboard')->with('success', 'Presupuesto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        //
    }
}
