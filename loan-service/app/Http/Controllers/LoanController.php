<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::all();

        return response()->json([
            'message' => 'Riwayat peminjaman berhasil diambil',
            'data' => $loans
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'book_id' => 'required|integer',
            'due_date' => 'nullable|date',
        ]);

        $userResponse = Http::get(env('USER_SERVICE_URL') . '/users/' . $request->user_id);

        if ($userResponse->failed()) {
            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $bookResponse = Http::get(env('BOOK_SERVICE_URL') . '/products/' . $request->book_id);

        if ($bookResponse->failed()) {
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $book = $bookResponse->json('data');

        if ($book['stock'] <= 0) {
            return response()->json([
                'message' => 'Stok buku habis'
            ], 400);
        }

        $newStock = $book['stock'] - 1;

        Http::put(env('BOOK_SERVICE_URL') . '/products/' . $request->book_id, [
            'stock' => $newStock
        ]);

        $loan = Loan::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrowed_at' => now()->toDateString(),
            'due_date' => $request->due_date,
            'status' => 'borrowed',
        ]);

        return response()->json([
            'message' => 'Buku berhasil dipinjam',
            'data' => $loan
        ], 201);
    }

    public function returnBook($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json([
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($loan->status === 'returned') {
            return response()->json([
                'message' => 'Buku sudah pernah dikembalikan'
            ], 400);
        }

        $bookResponse = Http::get(env('BOOK_SERVICE_URL') . '/products/' . $loan->book_id);

        if ($bookResponse->failed()) {
            return response()->json([
                'message' => 'Buku tidak ditemukan di Book Service'
            ], 404);
        }

        $book = $bookResponse->json('data');
        $newStock = $book['stock'] + 1;

        Http::put(env('BOOK_SERVICE_URL') . '/products/' . $loan->book_id, [
            'stock' => $newStock
        ]);

        $loan->update([
            'returned_at' => now()->toDateString(),
            'status' => 'returned',
        ]);

        return response()->json([
            'message' => 'Buku berhasil dikembalikan',
            'data' => $loan
        ], 200);
    }
}