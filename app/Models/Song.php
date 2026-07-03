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

class Song extends Model implements ContractsAuditable
{
    //

    public const META = [
        "label" => "Utwory",
        "icon" => "music",
        "description" => "",
        "role" => "",
        // "checkOwnerUnless" => "", // for roles above, allow to see only one's own objects unless they're also other role
        "ordering" => 2,
        // "listScope" => "", // default scope to list items in model editor, empty defaults to forAdminList
        "defaultSort" => "title", // default sort, as it appears in url
        // "defaultFltr" => "", // default filters //todo expand
    ];

    use SoftDeletes, Userstamps, Auditable;

    protected $fillable = [
        "name",
        "visible",
        "album_id",
        "file",
        "order",
        "description",
        "project_name",
        "released_at",
    ];

    #region presentation
    /**
     * Pretty display of a model - can use components and stuff
     */
    public function __toString(): string
    {
        return "$this->name ($this->album)";
    }

    /**
     * Display for select options - text only
     */
    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->album > $this->order: $this->name",
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
                "slot" => $this->name,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => null,
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
        "name" => [
            "type" => "text",
            "label" => "Tytuł",
            "icon" => "format-title",
            "required" => true,
        ],
        "file" => [
            "type" => "url-storage",
            "label" => "Plik",
            "icon" => "file-music",
            "hint" => "Ścieżka do OGGa z utworem.",
        ],
        "order" => [
            "type" => "number",
            "label" => "Numer ścieżki w albumie",
            "icon" => "format-list-numbered",
            "required" => true,
        ],
        "description" => [
            "type" => "TEXT",
            "label" => "Opis",
            "icon" => "text",
        ],
        "project_name" => [
            "type" => "text",
            "label" => "Projekt",
            "icon" => "traffic-cone",
            "hint" => "Jeśli utwór ma swój projekt (np. F2D Rath), to opisz tutaj.",
        ],
        "released_at" => [
            "type" => "date",
            "label" => "Data wydania",
            "icon" => "calendar",
        ],
    ];

    public const CONNECTIONS = [
        "album" => [
            "model" => Album::class,
            "mode" => "one",
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
        "title" => [
            "label" => "Tytuł",
            "compare-using" => "field",
            "discr" => "name",
        ],
    ];

    public const FILTERS = [
        "title" => [
            "label" => "Tytuł",
            "compare-using" => "function",
            "discr" => "name",
            "type" => "text",
            "operator" => "~*",
            "icon" => "text",
        ],
        "album" => [
            "label" => "Album",
            "icon" => "disc",
            "compare-using" => "field",
            "discr" => "album_id",
            "type" => "select",
            "operator" => "=",
            "selectData" => [
                "optionsFromScope" => [
                    Album::class,
                    "forConnection",
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
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            "released_at" => "datetime:d.m.Y"
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
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
    #endregion

    #region helpers
    #endregion
}
