<?php

namespace App\Support;

use App\Models\Accoustics;
use App\Models\ArchitraveType;
use App\Models\Color;
use App\Models\DoorDimension;
use App\Models\DoorLeafFacing;
use App\Models\GlassType;
use App\Models\GlazingSystem;
use App\Models\IntumescentSealColor;
use App\Models\IntumescentSealLeafType;
use App\Models\LeafType;
use App\Models\LippingSpeciesItems;
use App\Models\Option;
use App\Models\OverpanelGlassGlazing;
use App\Models\ScreenGlassType;
use App\Models\ScreenGlazingType;
use App\Models\SelectedAccoustics;
use App\Models\SelectedArchitraveType;
use App\Models\SelectedColor;
use App\Models\SelectedDoorLeafFacing;
use App\Models\SelectedDoordimension;
use App\Models\SelectedGlassType;
use App\Models\SelectedGlazingSystem;
use App\Models\SelectedIntumescentSealColor;
use App\Models\SelectedIntumescentSeals2;
use App\Models\SelectedLeafType;
use App\Models\SelectedLippingSpeciesItems;
use App\Models\SelectedOption;
use App\Models\SelectedOverpanelGlassGlazing;
use App\Models\SelectedScreenGlass;
use App\Models\SelectedScreenGlazing;
use App\Models\SettingIntumescentSeals2;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SelectedOptionModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'acoustics' => self::simple(
                'Acoustics',
                ['Accoustics', 'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedAccoustics::class,
                'userId',
                'accousticsId',
                fn (int $id, int $userId) => Accoustics::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first()
            ),
            'architrave_type' => self::simple(
                'Architrave Type',
                ['Architrave Type', 'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedArchitraveType::class,
                'userId',
                'architraveTypeId',
                fn (int $id, int $userId) => ArchitraveType::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first()
            ),
            'colour_list' => self::simple(
                'Colour List',
                ['Color Name', 'Hex', 'English Name', 'Door Leaf Facing', 'Door Configuration', 'Door Leaf Facing Value', 'Price'],
                'Price',
                'SelectedPrice',
                SelectedColor::class,
                'SelectedUserId',
                'SelectedColorId',
                fn (int $id, int $userId) => Color::query()->where('id', $id)->whereIn('editBy', [$userId, 1])->first()
            ),
            'door_dimension_standard' => self::simple(
                'Door Dimension Standard',
                ['Code', 'Height (mm)', 'Width (mm)', 'Height (inch)', 'Width (inch)', 'Fire Rating', 'Leaf Type', 'Door Leaf Facing', 'Configurable Item', 'Cost Price'],
                'Cost Price',
                'selected_cost',
                SelectedDoordimension::class,
                'doordimension_user_id',
                'doordimension_id',
                fn (int $id, int $userId) => DoorDimension::query()
                    ->where('id', $id)
                    ->whereIn('editBy', [$userId, 1])
                    ->whereIn('configurableitems', [4, 5, 6, 9])
                    ->first(),
                fn (Model $master) => [
                    'selected_configurableitems' => $master->configurableitems,
                    'selected_firerating' => (string) ($master->fire_rating ?? ''),
                    'selected_code' => $master->code,
                    'selected_mm_height' => (string) ($master->mm_height ?? ''),
                    'selected_mm_width' => (string) ($master->mm_width ?? ''),
                    'selected_sellingprice' => $master->selling_price,
                ]
            ),
            'door_dimension_custom' => [
                'label' => 'Door Dimension Custom',
                'price_mode' => 'leaf_json',
                'fixed_headers' => ['Height (mm)', 'Width (mm)', 'Fire Rating', 'Configurable Item'],
                'price_column' => 'custome_door_selected_cost',
                'model' => SelectedDoordimension::class,
                'owner' => 'doordimension_user_id',
                'foreign' => 'doordimension_id',
                'master' => fn (int $id, int $userId) => DoorDimension::query()
                    ->where('id', $id)
                    ->whereIn('editBy', [$userId, 1])
                    ->whereIn('configurableitems', [1, 2, 7, 8])
                    ->first(),
                'create_with' => fn (Model $master) => [
                    'selected_configurableitems' => $master->configurableitems,
                    'selected_firerating' => (string) ($master->fire_rating ?? ''),
                    'selected_code' => $master->code,
                    'selected_mm_height' => (string) ($master->mm_height ?? ''),
                    'selected_mm_width' => (string) ($master->mm_width ?? ''),
                    'selected_sellingprice' => $master->selling_price,
                ],
            ],
            'door_leaf_facing' => self::simple(
                'Door Leaf Facing Value',
                ['Option', 'Name', 'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedDoorLeafFacing::class,
                'userId',
                'doorLeafFacingId',
                fn (int $id, int $userId) => DoorLeafFacing::query()->where('id', $id)->whereIn('editBy', [$userId, 1])->first()
            ),
            'finish_cost' => self::simple(
                'Finish Cost',
                ['Configurable Items', 'Door Leaf Name', 'Under Attribute', 'Door Leaf', 'Price'],
                'Price',
                'SelectedOptionCost',
                SelectedOption::class,
                'SelectedUserId',
                'optionId',
                fn (int $id, int $userId) => Option::query()
                    ->where('id', $id)
                    ->whereIn('editBy', [$userId, 1])
                    ->whereIn('OptionSlug', ['Architrave_Finish', 'Frame_Finish', 'Door_Leaf_Facing', 'door_leaf_finish'])
                    ->where('is_deleted', 0)
                    ->whereNotIn('configurableitems', [3])
                    ->first(),
                fn (Model $master) => [
                    'configurableitems' => $master->configurableitems,
                    'tag' => $master->OptionSlug,
                    'SelectedUnderAttribute' => $master->UnderAttribute,
                    'SelectedOptionSlug' => (string) ($master->OptionSlug ?? ''),
                    'SelectedOptionKey' => (string) ($master->OptionKey ?? ''),
                    'SelectedOptionValue' => (string) ($master->OptionValue ?? ''),
                ]
            ),
            'glass_type' => self::simple(
                'Glass Type',
                ['Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'NFR', 'FD30', 'FD60', 'Glass Integrity', 'Glass Type', 'Glass Thickness', 'Vp Area Size', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedGlassType::class,
                'editBy',
                'glass_id',
                fn (int $id, int $userId) => GlassType::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first()
            ),
            'glazing_system' => self::simple(
                'Glazing System',
                ['Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'NFR', 'FD30', 'FD60', 'Glazing Type', 'Glazing Thickness', 'GlazingBead FixingDetail', 'Vp Area Size', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedGlazingSystem::class,
                'userId',
                'glazingId',
                fn (int $id, int $userId) => GlazingSystem::query()->where('id', $id)->whereIn('editBy', [$userId, 1])->first()
            ),
            'side_light_fanlight' => [
                'label' => 'Side Light / Fanlight',
                'price_mode' => 'columns',
                'headers' => [
                    'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'NFR', 'FD30', 'FD60', 'Integrity',
                    'Glass Name', 'Glass Thickness', 'Max Width(FL)', 'Max Height(FL)',
                    'Side Screen Width', 'Side Screen Height',
                    'MIN Fan Light/Over Panel Frame Thickness',
                    'MIN Fan Light/Over Panel Frame Depth',
                    'Glazing System', 'Glazing Thickness',
                    'Beading', 'Beading Height', 'Beading Width',
                    'Glazing Bead Fixing Detail',
                    'Glass Price Per m²', 'Glazing Price Per L/M',
                ],
                'prices' => [
                    'Glass Price Per m²' => 'glassSelectedPrice',
                    'Glazing Price Per L/M' => 'glazingSelectedPrice',
                ],
                'model' => SelectedOverpanelGlassGlazing::class,
                'owner' => 'editBy',
                'foreign' => 'glass_glazing_id',
                'master' => fn (int $id, int $userId) => OverpanelGlassGlazing::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first(),
                'create_with' => fn () => ['status' => 1],
            ],
            'intumescent_seal_arrangement' => self::simple(
                'Intumescent Seal Arrangement',
                ['Configurable Item', 'FireDoor', 'Configuration', 'Height1', 'Height2', 'Width1', 'Width2', 'Intumescent Seal', 'Brand', 'FireOnly Type', 'Leaf Type', 'Cost Price'],
                'Cost Price',
                'selected_cost',
                SelectedIntumescentSeals2::class,
                'selected_intumescentseals2_user_id',
                'intumescentseals2_id',
                fn (int $id, int $userId) => SettingIntumescentSeals2::query()->where('id', $id)->whereIn('editBy', [$userId, 1])->first(),
                fn (Model $master) => [
                    'selected_configurableitems' => $master->configurableitems,
                    'selected_configuration' => $master->configuration,
                    'selected_doorname' => $master->doorname,
                    'selected_firerating' => $master->firerating,
                    'selected_tag' => $master->tag,
                    'selected_intumescentSeals' => $master->intumescentSeals,
                    'selected_brand' => $master->brand,
                    'selected_firetested' => $master->firetested,
                    'selected_Point1height' => $master->Point1height,
                    'selected_Point1width' => $master->Point1width,
                    'selected_Point2height' => $master->Point2height,
                    'selected_Point2width' => $master->Point2width,
                    'MeetingEdges' => $master->MeetingEdges,
                ]
            ),
            'intumescent_seal_colour' => self::simple(
                'Intumescent Seal Colour',
                ['Intumescent Seal Color', 'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedIntumescentSealColor::class,
                'userId',
                'intumescentSealColorId',
                fn (int $id, int $userId) => IntumescentSealColor::query()->where('id', $id)->whereIn('editBy', [$userId, 1])->first()
            ),
            'leaf_type' => self::simple(
                'Leaf Type',
                ['Leaf Type', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²'],
                'Price Per m²',
                'selectedPrice',
                SelectedLeafType::class,
                'editBy',
                'leaf_id',
                fn (int $id, int $userId) => LeafType::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first()
            ),
            'side_screen_glass' => self::simple(
                'Side Screen Glass Type',
                ['Fire Rating', 'DF Rating', 'Glass Name', 'Width Point 1 (far right)', 'Height Point 1 (Lowest)', 'Width Point 2 (Closest)', 'Height Point 2 (Highest)', 'MIN Transom Thickness', 'MIN Transom Depth', 'MAX Area m²', 'Frame Density', 'Price Per L/M'],
                'Price Per L/M',
                'glassSelectedPrice',
                SelectedScreenGlass::class,
                'editBy',
                'glass_id',
                fn (int $id, int $userId) => ScreenGlassType::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first()
            ),
            'side_screen_glazing' => self::simple(
                'Side Screen Glazing System',
                ['Fire Rating', 'Screen Glass', 'Glazing System', 'Glazing Thickness', 'Beading', 'Beading Height', 'Beading Width', 'Fixing Details', 'Price Per L/M'],
                'Price Per L/M',
                'glazingSelectedPrice',
                SelectedScreenGlazing::class,
                'editBy',
                'glazing_id',
                fn (int $id, int $userId) => ScreenGlazingType::query()->where('id', $id)->whereIn('EditBy', [$userId, 1])->first()
            ),
            'timber_species' => self::simple(
                'Timber Species',
                ['Species', 'Inch', 'MM', 'Status', 'Price / M3'],
                'Price / M3',
                'selected_price',
                SelectedLippingSpeciesItems::class,
                'selected_user_id',
                'selected_lipping_species_items_id',
                function (int $id, int $userId) {
                    $user = User::query()->find($userId);
                    $ownerIds = $user && (int) $user->UserType === 2 ? [1, $userId] : [1];

                    return LippingSpeciesItems::query()
                        ->where('id', $id)
                        ->where('thickness', '<=', 4)
                        ->whereHas('lipping_species', function ($query) use ($ownerIds) {
                            $query->where('Status', 1)->whereIn('editBy', $ownerIds);
                        })
                        ->first();
                },
                fn (Model $master) => [
                    'selected_lipping_species_id' => $master->lipping_species_id,
                    'selected_thickness' => $master->thickness,
                    'selected_status' => $master->status,
                ]
            ),
        ];
    }

    public static function get(string $module): ?array
    {
        return self::all()[$module] ?? null;
    }

    public static function has(string $module): bool
    {
        return isset(self::all()[$module]);
    }

    public static function leafHeader(IntumescentSealLeafType $leaf): string
    {
        return $leaf->leaf_type_key . ' ' . configurationDoor($leaf->configurableitems) . ' Cost';
    }

    /**
     * @param  array<int, string>  $headers
     * @param  callable(int, int): (?Model)  $master
     * @param  (callable(Model): array<string, mixed>)|null  $createWith
     * @return array<string, mixed>
     */
    private static function simple(
        string $label,
        array $headers,
        string $priceHeader,
        string $priceColumn,
        string $model,
        string $owner,
        string $foreign,
        callable $master,
        ?callable $createWith = null
    ): array {
        return [
            'label' => $label,
            'price_mode' => 'columns',
            'headers' => $headers,
            'prices' => [$priceHeader => $priceColumn],
            'model' => $model,
            'owner' => $owner,
            'foreign' => $foreign,
            'master' => $master,
            'create_with' => $createWith,
        ];
    }
}
