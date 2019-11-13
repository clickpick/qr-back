<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Key
 *
 * @property int $id
 * @property string $value
 * @property int $order
 * @property int $project_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProjectKey newModelQuery()
 * @method static Builder|ProjectKey newQuery()
 * @method static Builder|ProjectKey query()
 * @method static Builder|ProjectKey whereCreatedAt($value)
 * @method static Builder|ProjectKey whereId($value)
 * @method static Builder|ProjectKey whereOrder($value)
 * @method static Builder|ProjectKey whereProjectId($value)
 * @method static Builder|ProjectKey whereUpdatedAt($value)
 * @method static Builder|ProjectKey whereValue($value)
 * @mixin Eloquent
 * @property float $drop_rate
 * @property-read Project $project
 * @method static Builder|ProjectKey whereDropRate($value)
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 */
class ProjectKey extends Model
{
    protected $fillable = [
        'value',
        'order',
        'drop_rate'
    ];


    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('token');
    }

    public function isRare() {
        $rareProjectKey = $this->project->projectKeys()->orderBy('drop_rate', 'asc')->first();

        return $rareProjectKey->id === $this->id;
    }
}
