<?php

namespace App\View\Components;

use App\Models\Budget;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BudgetDrowpdown extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Budget $budget) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.budget-drowpdown');
    }
}
