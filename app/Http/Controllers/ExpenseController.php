<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExpenseRequest;
use App\http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;

class ExpenseController extends Controller
{
    public function index(Request $request) 
    {
        $query = $request->user()->expenses()->with('category');

        if ($request->has('category_id'))
            {
                $query->where('category_id', $request->category_id);
            }

        if ($request->has('date_from'))
            {
                $query->where('date', '>=', $request->date_from);
            }

        if ($request->has('date_to'))
            {
                $query->where('date', '<=', $request->date_to);
            }

        $expenses = $query->get();

        return ExpenseResource::collection($expenses);
    }

    public function store(StoreExpenseRequest $request) 
    {
        $expense = $request->user()->expenses()->create([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->decsription,
            'date'        => $request->date,
        ]);

        return new ExpenseResource($expense->load('category'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        if ($expense->user_id !== $request->user()->id)
            {
                return response()->json(['message' => 'Forbidden'], 403);
            }

        $expense->update([
            'category_id' => $request->category_id ?? $expense->category_id,
            'amount'      => $request->amount ?? $expense->amount,
            'description' => $request->description ?? $expense->description,
            'date'        => $request->date ?? $expense->date,
        ]);

        return new ExpenseResource($expense->load('category'));
    }

    public function destroy(Request $request, Expense $expense) 
    {
        if ($expense->user_id !== $request->user()->id)
           {
               return response()->json(['message' => 'Forbidden'], 403);
           }

        $expense->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function summary(Request $request)
    {
        $sumarry = $request->user()->expenses()
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json(['data' => $sumarry]);
    }
}
