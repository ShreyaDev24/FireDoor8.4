<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Models\FavoriteItem;
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
        $favorites = Favorite::with('user')->where(['userId'=>Auth::id()])->latest()->get();
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
        $html = '';

        $UserIds = CompanyMultiUsers();
        $Favorite = FavoriteItem::join('quotation', 'quotation.id', 'favorite_item.quotationId')
        ->join('favorite','favorite.id','favorite_item.favorite_id')
        ->select('favorite_item.*', 'quotation.configurableitems','favorite.name')
        ->where('favorite_item.favorite_id',$favorite->id)
        ->wherein('favorite_item.userId', $UserIds)->get();

        if (!empty($Favorite) && $Favorite != '') {
            $html .= '<table class="table table-bordered">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Favorite Type</th>';
            $html .= '<th>Door Type</th>';
            $html .= '<th>Edit</th>';
            $html .= '<th>Delete</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach ($Favorite as $value) {
                $html .= '<tr>';
                $html .= '<td>' . $value->name . '</td>';
                $html .= '<td>' . $value->DoorType . '</td>';

                $html .= '<td>';
                $html .= '<a href="' . ConfigurationURL($value->configurableitems, $value->itemId, $value->versionId) . '" class="btn btn-info">Edit</a>';
                $html .= '</td>';

                $html .= '<td>';
                $html .= '<button onclick="favoriteDeleteItem(\'' . $value->id . '\')" class="btn btn-danger">Delete</button>';
                $html .= '</td>';

                $html .= '</tr>';
            }

            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $html .= '<p>Data not found!</p>';
        }

        return view('favorites.show', compact('favorite','html'));
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
