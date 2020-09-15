<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string name
 * @property integer type
 * @property integer parent_id
 * @property Location parent
 * @property Location[] children
 * @property integer ostan_id
 * @property Location ostan
 * @property integer shahr_id
 * @property Location shahr
 * @property integer bakhsh_id
 * @property Location bakhsh
 * @property integer dehestan_id
 * @property Location dehestan
 * @property array geo_data
 * @property boolean is_fetched
 *
 * Class Location
 * @package App\Models
 */
class Location extends Model
{
    use HasFactory;

    protected $table = "locations";
    protected $fillable = ["id", "name", "type", "parent_id", "ostan_id", "shahr_id", "bakhsh_id", "dehestan_id"];

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, "parent_id", "id");
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(Location::class, "parent_id", "id");
    }

    /**
     * @return BelongsTo
     */
    public function ostan()
    {
        return $this->belongsTo(Location::class, "ostan_id", "id");
    }

    /**
     * @return BelongsTo
     */
    public function shahr()
    {
        return $this->belongsTo(Location::class, "shahr_id", "id");
    }

    /**
     * @return BelongsTo
     */
    public function bakhsh()
    {
        return $this->belongsTo(Location::class, "bakhsh_id", "id");
    }

    /**
     * @return BelongsTo
     */
    public function dehestan()
    {
        return $this->belongsTo(Location::class, "dehestan_id", "id");
    }
}
