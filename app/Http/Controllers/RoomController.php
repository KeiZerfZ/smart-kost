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
        // Validasi: Jangan hapus kalau masih ada penghuninya
        if ($room->status === 'occupied') {
            return redirect()->back()->with('error', 'Kamar tidak bisa dihapus karena masih ada penghuni.');
        }

        $room->delete();
        return redirect()->back()->with('success', 'Kamar berhasil dihapus.');
    }
}