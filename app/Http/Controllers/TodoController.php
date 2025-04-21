<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // Menampilkan daftar todo
    public function index()
    {
        $todos = Todo::orderBy('created_at', 'desc')->get();
        return view('main', compact('todos'));
    }

    // Menyimpan tugas baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'datetime' => 'required|date',
        ]);

        Todo::create([
            'title' => $validated['title'],
            'datetime' => $validated['datetime'],
            'priority' => $request->priority,
            'completed' => false,
            
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    // Menampilkan form edit yang terpisah
    public function edit(Todo $todo)
    {
        return view('edit', compact('todo'));
    }

    // Memperbarui tugas di database
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'datetime' => 'required|date',
            'priority' => 'required|string',
        ]);

        $todo->update([
            'title' => $validated['title'],
            'datetime' => $validated['datetime'],
            'priority' => $validated['priority'],
        ]);

        return redirect('/')->with('success', 'Tugas berhasil diperbarui!');
    }

    // Menghapus tugas 
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }

    // Menandai tugas beres/tidak
    public function toggleComplete($id)
    {
        $todo = Todo::findOrFail($id);

        $todo->update([
            'completed' => !$todo->completed,
        ]);

        return redirect()->back()->with('success', 'Status tugas diperbarui!');
    }
}
