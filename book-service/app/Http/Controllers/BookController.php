<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return response()->json([
            'message' => 'Daftar products berhasil diambil',
            'data' => $books
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2100',
            'stock' => 'required|integer|min:0',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Product buku berhasil ditambahkan',
            'data' => $book
        ], 201);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Product buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail product buku berhasil diambil',
            'data' => $book
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Product buku tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:150',
            'author' => 'sometimes|required|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2100',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Product buku berhasil diperbarui',
            'data' => $book
        ], 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Product buku tidak ditemukan'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Product buku berhasil dihapus'
        ], 200);
    }
}