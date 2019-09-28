<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\ProjectFact
 *
 * @property int $id
 * @property int $project_id
 * @property string $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ProjectFact newModelQuery()
 * @method static Builder|ProjectFact newQuery()
 * @method static Builder|ProjectFact query()
 * @method static Builder|ProjectFact whereCreatedAt($value)
 * @method static Builder|ProjectFact whereId($value)
 * @method static Builder|ProjectFact whereProjectId($value)
 * @method static Builder|ProjectFact whereText($value)
 * @method static Builder|ProjectFact whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Project $project
 */
class ProjectFact extends Model
{
    protected $fillable = [
        'text',
        'project_id'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
