<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request, Budget $budget)
    {
        // forma tradicional
        // $data = $request->validated();
        // Expense::create([
        //     'name' => $data['name'],
        //     'amount' => $data['amount'],
        //     'category' => $data['category'],
        //     'budget_id' => $budget->id,
        //]);

        $budget->expenses()->create($request->validated());

        return redirect()
            ->route('budgets.show', $budget->id)
            ->with('success', 'Gasto creado exitosamente');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Budget $budget, Expense $expense)
    {
        //
        $expense->update($request->validated());

        return redirect()
            ->route('budgets.show', $budget->id)
            ->with('success', 'Gasto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget, Expense $expense)
    {
        //
        $expense->delete();

        return redirect()
            ->route('budgets.show', $expense->budget_id)
            ->with('success', 'Gasto eliminado exitosamente');
    }
}
