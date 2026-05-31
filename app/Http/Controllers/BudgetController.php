<?php

namespace App\Http\Controllers;

use App\Enum\ExpenseCategory;
use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

    #[Authorize('view', 'budget')]
    public function show(Budget $budget)
    {

        // $expenses = Expense::where('budget_id', '=',  $budget->id)->get();
        // $expenses = $budget->expenses()->latest()->get();
        $budget->load([
            'expenses' => fn($query) => $query->latest()
        ]);

        $spent = $budget->expenses->sum('amount');

        // dd($total);

        $categories = collect(ExpenseCategory::cases())->map(fn($category) => [
            'value' => $category->value,
            'label' => $category->label(),
        ]);
        // dd($categories);


        return Inertia::render('Budgets/Show', [
            'budget' => $budget,
            'spent' => $spent,
            'categories' => $categories
        ]);
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

        return redirect()->route('dashboard')->with('success', 'Presupuesto actualizado correctamente');
    }

    #[Authorize('delete', 'budget')]
    public function destroy(Budget $budget)
    {
        //
        $budget->delete();
        return redirect()->route('dashboard')->with('success', 'Presupuesto eliminado correctamente');
    }
}
