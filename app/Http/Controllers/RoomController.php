<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => [
                'required',
                // Mencari apakah kombinasi room_number DAN type sudah ada
                Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                }),
            ],
            'type' => 'required',
            'price' => 'required|numeric|min:0',
        ], [
            'room_number.unique' => 'Nomor kamar ' . $request->room_number . ' dengan tipe ' . $request->type . ' sudah ada.'
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambah!');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $room->id,
            'type' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:empty,occupied',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Data kamar diperbarui.');
    }

    public function destroy(Room $room)
    {
        // 1. Pengecekan manual (Pastikan lowercase)
        if (strtolower($room->status) === 'occupied') {
            return redirect()->route('rooms.index')->with('error', 'Kamar ' . $room->room_number . ' tidak bisa dihapus karena masih ada penghuninya!');
        }

        try {
            // 2. Cek relasi secara database untuk memastikan keamanan data
            if ($room->tenants()->exists()) {
                return redirect()->route('rooms.index')->with('error', 'Gagal hapus! Masih ada data penghuni yang terikat ke kamar ini.');
            }

            $room->delete();
            return redirect()->route('rooms.index')->with('success', 'Kamar ' . $room->room_number . ' berhasil dihapus.');
            
        } catch (\Exception $e) {
            // 3. Nangkap error database (Foreign Key error dsb)
            return redirect()->route('rooms.index')->with('error', 'Sistem menolak penghapusan karena kamar ini memiliki riwayat transaksi.');
        }
    }
}