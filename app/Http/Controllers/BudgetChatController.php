<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BudgetAssistant;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth')]
#[Middleware('verified')]
class BudgetChatController extends Controller
{
    //
    public function store(Request $request, Budget $budget){
        //
        $messages = $request->input('messages', []);
        $lastMessage = collect($messages)->last();



        $prompt = collect(data_get($lastMessage, 'parts', []))
        ->where('type', 'text')
        ->pluck('text')
        ->implode(' ')
        ?: data_get($lastMessage, 'content', '')
        ;

        $agent = new BudgetAssistant();
        $agent->budgetId = $budget->id;
        $agent->hasCategories = !$budget->isGoal();

        if ($budget->isGoal()) {
            $agent->budgetContext = "Este presupuesto es de tipo Meta/Objetivo llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos NO tienen categorías, solo nombre y monto.";
        } else {
            $agent->budgetContext = "Este presupuesto es de tipo General llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos tienen nombre, monto y categoría.";
        }

        return $agent->stream(
            $prompt,
            provider: 'openrouter',
            model: 'poolside/laguna-xs.2:free'
            // model: 'google/gemma-4-26b-a4b-it:free'
            // model: 'nvidia/nemotron-3-nano-omni-30b-a3b-reasoning:free'
            // model: 'qwen/qwen3-next-80b-a3b-instruct:free'

        )->usingVercelDataProtocol();
    }
}
