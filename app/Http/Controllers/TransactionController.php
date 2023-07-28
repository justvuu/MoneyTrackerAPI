<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function addTransaction(Request $request)
    {
        $amount = $request->input('amount');
        $categoryId = $request->input('categoryId');
        $date = $request->input('date');
        $userId = $request->input('userId');
        $description = $request->input('description');

        $transaction = new Transaction();
        $transaction->amount = $amount;
        $transaction->category_id_fk = $categoryId;
        $transaction->date = $date;
        $transaction->user_id_fk = $userId;
        $transaction->description = $description;

        $transaction->save();

        return response()->json(['message' => 'Transaction added successfully'], 200);
    }

    public function updateTransaction(Request $request, $transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->amount = $request->input('amount');
        $transaction->category_id_fk = $request->input('categoryId');
        $transaction->date = $request->input('date');
        $transaction->user_id_fk = $request->input('userId');
        $transaction->description = $request->input('description');

        $transaction->save();

        return response()->json(['message' => 'Transaction updated successfully'], 200);
    }

    public function deleteTransaction($transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }

    public function getTransactionById($transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction, 200);
    }

    public function getRecentTransaction($userId)
    {
        $transactions = Transaction::where('user_id_fk', $userId)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return response()->json($transactions, 200);
    }

    public function getTransactionByMonth($month, $userId)
    {
        // $months = substr("2023-14-7", 8, 1);
        // dd($months);
        $transactions = Transaction::where('user_id_fk', $userId)
            ->whereRaw("MONTH(STR_TO_DATE(date, '%Y-%e-%c')) = ?", [$month])
            ->get();

        return response()->json($transactions, 200);
    }

    public function getIncomeCategoryTotal($month, $userId)
    {
        $transactions = Transaction::where('user_id_fk', $userId)
            ->whereRaw("MONTH(STR_TO_DATE(date, '%Y-%e-%c')) = ?", [$month])
            ->where('amount', '>', 0)
            ->with('category')
            ->get();

        $categoryTotals = [];

        foreach ($transactions as $transaction) {
            if ($transaction->category->type == 0) {
                continue;
            }

            $categoryId = $transaction->category_id_fk;
            $categoryName = $transaction->category->category_name;
            $amount = $transaction->amount;

            if (!isset($categoryTotals[$categoryId])) {
                $categoryTotals[$categoryId] = [
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'total_income' => $amount,
                ];
            } else {
                $categoryTotals[$categoryId]['total_income'] += $amount;
            }
        }

        usort($categoryTotals, function ($a, $b) {
            return $b['total_income'] - $a['total_income'];
        });

        return response()->json(array_values($categoryTotals), 200);
    }

    public function getExpenseCategoryTotal($month, $userId)
    {
        $transactions = Transaction::where('user_id_fk', $userId)
            ->whereRaw("MONTH(STR_TO_DATE(date, '%Y-%e-%c')) = ?", [$month])
            ->where('amount', '>', 0)
            ->with('category')
            ->get();

        $categoryTotals = [];

        foreach ($transactions as $transaction) {
            if ($transaction->category->type == 1) {
                continue;
            }

            $categoryId = $transaction->category_id_fk;
            $categoryName = $transaction->category->category_name;
            $amount = $transaction->amount;

            if (!isset($categoryTotals[$categoryId])) {
                $categoryTotals[$categoryId] = [
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'total_income' => $amount,
                ];
            } else {
                $categoryTotals[$categoryId]['total_income'] += $amount;
            }
        }

        usort($categoryTotals, function ($a, $b) {
            return $b['total_income'] - $a['total_income'];
        });

        return response()->json(array_values($categoryTotals), 200);
    }

}
