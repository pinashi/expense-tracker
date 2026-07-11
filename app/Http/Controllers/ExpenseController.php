<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExpenseRequest;
use App\http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the authenticated user's expenses with optional filters.
     */
    public function index(Request $request): AnonymousResourceCollection 
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

    /**
     * Store a newly created expense in storage.
     */
    public function store(StoreExpenseRequest $request): ExpenseResource
    {
        $expense = $request->user()->expenses()->create([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->decsription,
            'date'        => $request->date,
        ]);

        return new ExpenseResource($expense->load('category'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): ExpenseResource|JsonResponse
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

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id)
           {
               return response()->json(['message' => 'Forbidden'], 403);
           }

        $expense->delete();

        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Return a summary of expenses grouped by month.
     */
    public function summary(Request $request): JsonResponse
    {
        $sumarry = $request->user()->expenses()
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json(['data' => $sumarry]);
    }
}
