<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::latest()->get(); // latest = data terbaru tampil paling atas

        return response()->json([
            'status'    => true,
            'message'   => 'Data berhasil diambil',
            'data'      => $notes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * (DIUBAH: Dari 'create' menjadi 'store' agar sesuai route API)
     */
    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required|string',
            'content'      => 'required|string',
            'categori' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'Validator error',
                'errors'    => $validator->errors()
            ], 422);
        }

        // 'tanggal_buat' dihapus dari sini karena sudah otomatis terisi di Model/Database
        $note = Note::create([
            'title'    => $request->title,
            'content'      => $request->content,
            'categori' => $request->categori,
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Data berhasil dibuat',
            'data'      => $note,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'status'  => false,
                'message' => 'Catatan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail catatan berhasil diambil',
            'data'    => $note
        ], 200);
    }

    /**
     * Mengubah data catatan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'status'  => false,
                'message' => 'Catatan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'    => 'sometimes|required|string|max:255',
            'content'      => 'sometimes|required|string',
            'categori' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $note->update([
                'title' => $request->title,
                'content' => $request->content,
                'categori' => $request->categori,
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Catatan berhasil diperbarui',
            'data'    => $note
        ], 200);
    }

    /**
     * Menghapus catatan dari database.
     */
    public function destroy($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'status'  => false,
                'message' => 'Catatan tidak ditemukan'
            ], 404);
        }

        $note->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Catatan berhasil dihapus'
        ], 200);
    }
}