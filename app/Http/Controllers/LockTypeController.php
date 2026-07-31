<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LockTypeController extends Controller
{
    /**
     * Lock Type dropdown (door_configuration_lock_type) is read directly from the
     * generic `options` table by the door configuration pages (Halspan/Vicaima),
     * filtered by editBy via CompanyUsers(). This controller manages that same
     * table/slug so newly added lock types appear there automatically.
     */
    private const OPTION_SLUG = 'door_configuration_lock_type';
    private const OPTION_NAME = 'Door Configuration Lock Type';

    // configurableitems ids that exist in the configurableitems table (3 = NormaDoorCore has none),
    // ordered as: Streboard, Halspan, Flamebreak, Stredor, VicaimaDoorCore, Seadec, Deanta, MMM.
    private const CONFIGURABLE_ITEM_IDS = [1, 2, 7, 8, 4, 5, 6, 9];

    /**
     * Brand name => configurableitems id, using the app's shared configurationDoor()
     * helper so the naming stays identical to every other brand-scoped module.
     */
    private static function brands(): array
    {
        $brands = [];
        foreach (self::CONFIGURABLE_ITEM_IDS as $id) {
            $brands[configurationDoor($id)] = $id;
        }

        return $brands;
    }

    public function index()
    {
        $auth = Auth::user();
        $userIds = CompanyUsers();

        $items = Option::where('OptionSlug', self::OPTION_SLUG)
            ->where('is_deleted', 0)
            ->whereIn('editBy', $userIds)
            ->orderBy('configurableitems')
            ->orderBy('OptionValue')
            ->get();

        $brands = self::brands();

        return view('SelectedOptions.lock_type.index', compact('items', 'auth', 'brands'));
    }

    public function create()
    {
        return view('SelectedOptions.lock_type.create', ['brands' => self::brands()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'LockType'  => 'required|string|max:191',
            'brands'    => 'required|array|min:1',
            'brands.*'  => 'in:' . implode(',', self::brands()),
        ]);

        $name = trim($request->LockType);

        DB::transaction(function () use ($request, $name): void {
            foreach ($request->brands as $configurableItemId) {
                $option = new Option();
                $option->OptionName = self::OPTION_NAME;
                $option->OptionSlug = self::OPTION_SLUG;
                $option->OptionKey = $name;
                $option->OptionValue = $name;
                $option->OptionStatus = 1;
                $option->is_deleted = 0;
                $option->configurableitems = $configurableItemId;
                $option->editBy = Auth::user()->id;
                $option->save();
            }
        });

        return redirect()->route('Lock-Type.index')->with('success', 'Lock Type added successfully');
    }

    public function edit($id)
    {
        $item = Option::where('OptionSlug', self::OPTION_SLUG)->findOrFail($id);
        $this->authorizeOption($item);

        return view('SelectedOptions.lock_type.edit', ['item' => $item, 'brands' => self::brands()]);
    }

    public function update(Request $request, $id)
    {
        $item = Option::where('OptionSlug', self::OPTION_SLUG)->findOrFail($id);
        $this->authorizeOption($item);

        $request->validate([
            'LockType' => 'required|string|max:191',
            'brand'    => 'required|in:' . implode(',', self::brands()),
        ]);

        $name = trim($request->LockType);

        $item->OptionKey = $name;
        $item->OptionValue = $name;
        $item->configurableitems = $request->brand;
        $item->save();

        return redirect()->route('Lock-Type.index')->with('success', 'Lock Type updated successfully');
    }

    public function destroy($id)
    {
        $item = Option::where('OptionSlug', self::OPTION_SLUG)->findOrFail($id);
        $this->authorizeOption($item);

        $item->is_deleted = 1;
        $item->save();

        return back()->with('success', 'Lock Type deleted successfully');
    }

    private function authorizeOption(Option $item): void
    {
        $auth = Auth::user();
        abort_unless($item->editBy == $auth->id || $auth->UserType == 1, 403);
    }
}
