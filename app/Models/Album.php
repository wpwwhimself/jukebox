<?php

namespace App\Models;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\View\ComponentAttributeBag;
use Mattiverse\Userstamps\Traits\Userstamps;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Album extends Model implements ContractsAuditable
{
    //

    public const META = [
        "label" => "Albumy",
        "icon" => "disc",
        "description" => "Albumy grupują utwory.",
        "role" => "",
        // "checkOwnerUnless" => "", // for roles above, allow to see only one's own objects unless they're also other role
        "ordering" => 1,
        // "listScope" => "", // default scope to list items in model editor, empty defaults to forAdminList
        "defaultSort" => "-year", // default sort, as it appears in url
        // "defaultFltr" => "", // default filters //todo expand
    ];

    use SoftDeletes, Userstamps, Auditable;

    protected $fillable = [
        "name",
        "visible",
        "image",
        "color",
        "is_normal",
        "description",
        "years",
    ];

    #region presentation
    /**
     * Pretty display of a model - can use components and stuff
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Display for select options - text only
     */
    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    /**
     * Pretty display for model tiles
     */
    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->years,
        );
    }

    public function displayPreTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => null,
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.model.connections-preview", [
                "connections" => self::getConnections(),
                "model" => $this,
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "image" => [
            "type" => "url-storage",
            "label" => "Obraz",
            "icon" => "image",
        ],
        "color" => [
            "type" => "color",
            "label" => "Kolor",
            "icon" => "palette",
        ],
        "is_normal" => [
            "type" => "checkbox",
            "label" => "Normalny",
            "icon" => "shape",
            "hint" => "Grupuje albumy na stronie głównej.",
        ],
        "description" => [
            "type" => "TEXT",
            "label" => "Opis",
            "icon" => "text",
        ],
        "years" => [
            "type" => "text",
            "label" => "Lata",
            "icon" => "calendar",
            "hint" => "W jakich latach powstawał album.",
        ],
    ];

    public const CONNECTIONS = [
        "songs" => [
            "model" => Song::class,
            "mode" => "many-reverse",
            // "field_name" => "",
            // "field_label" => "",
            // "readonly" => true,
        ],
    ];

    public const ACTIONS = [
        // [
        //     "icon" => "",
        //     "label" => "",
        //     "show-on" => "<list|edit>",
        //     "route" => "",
        //     "role" => "",
        //     "dangerous" => true,
        // ],
    ];

    /**
     * extended form validation on model save
     * set result to true if everything is ok, false with message to force back with toast
     */
    // public static function validateOnSave($data): array
    // {
    //     $res = [
    //         "result" => true/false,
    //         "message" => "",
    //     ];
    //
    //     // validation...
    //
    //     return $res;
    // }

    /**
     * extended form fields autofill on model save
     * add or update fields inside $data to trigger additional changes based on existing form data
     * then return updated $data
     */
    // public static function autofillOnSave(array $data): array
    // {
    //     return $data;
    // }
    #endregion

    public const SORTS = [
        "year" => [
            "label" => "Lata",
            "compare-using" => "field",
            "discr" => "years",
        ],
    ];

    public const FILTERS = [
        "normal" => [
            "label" => "Normalny",
            "icon" => "shape",
            "compare-using" => "field",
            "discr" => "is_normal",
            "type" => "select",
            "operator" => "=",
            "selectData" => [
                "options" => [
                    ["label" => "Tak", "value" => 1],
                    ["label" => "Nie", "value" => 0],
                ],
                "emptyOption" => "Wszystkie",
            ],
        ],
    ];

    public const EXTRA_SECTIONS = [
        // "<id>" => [
        //     "title" => "",
        //     "icon" => "",
        //     "show-on" => "<list|edit>",
        //     "component" => "<component_name>",
        //     "role" => "",
        // ],
    ];

    #region scopes
    use HasStandardScopes;

    public function scopeVisible()
    {
        return $this->orderByDesc("years");
    }
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            //
        ];
    }

    protected $appends = [

    ];

    use HasStandardAttributes;

    // public function badges(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => [
    //             [
    //                 "label" => "",
    //                 "icon" => "",
    //                 "class" => "",
    //                 "style" => "",
    //                 "condition" => "",
    //             ],
    //             [
    //                 "html" => "",
    //             ],
    //         ],
    //     );
    // }

    //? override add button on model list
    // public static function modelAddButton(): string
    // {
    //     return view("components.shipyard.ui.button", [
    //         "icon" => "plus",
    //         "label" => "Dodaj",
    //         "action" => route(...),
    //         "attributes" => new ComponentAttributeBag([
    //             "class" => "primary",
    //         ]),
    //     ])->render();
    // }

    //? override edit button on model list
    // public function modelEditButton(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => view("components.shipyard.ui.button", [
    //             "icon" => "pencil",
    //             "label" => "Edytuj",
    //             "action" => route(...),
    //         ])->render(),
    //     );
    // }
    #endregion

    #region relations
    public function songs()
    {
        return $this->hasMany(Song::class)->visible();
    }
    #endregion

    #region helpers
    #endregion
}
