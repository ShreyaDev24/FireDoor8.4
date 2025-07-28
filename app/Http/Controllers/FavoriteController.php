<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favorites = Favorite::with('user')->where(['userId'=>Auth::id(),'status'=>1])->latest()->get();
        return view('favorites.index', compact('favorites'));
    }

    public function create()
    {
        $users = User::pluck('UserEmail', 'id'); // or any other identifier
        return view('favorites.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Favorite::create([
            'name' => $request->input('name'),
            'userId' => Auth::id(), // ✅ Set userId from session
            'status' => 1, // Optional: or default as per your logic
        ]);

        return redirect()->route('favorites.index')->with('success', 'Favorite created successfully.');
    }

    public function show(Favorite $favorite)
    {
        return view('favorites.show', compact('favorite'));
    }

    public function edit(Favorite $favorite)
    {
        $users = User::pluck('UserEmail', 'id');
        return view('favorites.edit', compact('favorite', 'users'));
    }

    public function update(Request $request, Favorite $favorite)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $favorite->update($request->all());

        return redirect()->route('favorites.index')->with('success', 'Favorite updated successfully.');
    }

    public function destroy(Favorite $favorite)
    {
        $favorite->delete();
        return redirect()->route('favorites.index')->with('success', 'Favorite deleted successfully.');
    }
}
