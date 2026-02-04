<?php

namespace App\Http\Controllers;

use App\Models\AppLink;
use Illuminate\Http\Request;

class AppLinkController extends Controller
{
    public function index()
    {
        $apps = AppLink::orderBy('name')->get();
        return view('applink.index', compact('apps'));
    }

    public function create()
    {
        return view('applink.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'url' => 'required|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $payload = [
            'name' => $data['name'],
            'url' => $data['url'],
        ];

        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image')->store('applinks', 'public');
        }

        AppLink::create($payload);

        return redirect()->route('applink.index')->with('success', 'Aplikasi berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $app = AppLink::findOrFail($id);
        return view('applink.edit', compact('app'));
    }

    public function update(Request $request, string $id)
    {
        $app = AppLink::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'url' => 'required|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $payload = [
            'name' => $data['name'],
            'url' => $data['url'],
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($app->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($app->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($app->image);
            }
            $payload['image'] = $request->file('image')->store('applinks', 'public');
        }

        $app->update($payload);

        return redirect()->route('applink.index')->with('success', 'Aplikasi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $app = AppLink::findOrFail($id);
        $app->delete();

        return redirect()->route('applink.index')->with('success', 'Aplikasi berhasil dihapus!');
    }
}

