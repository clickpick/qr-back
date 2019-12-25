<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\AvailableCheat
 *
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 * @property bool $is_fired
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|AvailableCheat newModelQuery()
 * @method static Builder|AvailableCheat newQuery()
 * @method static Builder|AvailableCheat query()
 * @method static Builder|AvailableCheat whereCreatedAt($value)
 * @method static Builder|AvailableCheat whereId($value)
 * @method static Builder|AvailableCheat whereIsFired($value)
 * @method static Builder|AvailableCheat whereProjectId($value)
 * @method static Builder|AvailableCheat whereUpdatedAt($value)
 * @method static Builder|AvailableCheat whereUserId($value)
 * @mixin Eloquent
 * @property string $vk_pay_order_id
 * @method static Builder|AvailableCheat whereVkPayOrderId($value)
 * @property-read User $user
 * @property-read Project $project
 * @property int|null $project_key_id
 * @method static Builder|AvailableCheat whereProjectKeyId($value)
 */
class AvailableCheat extends Model
{
    protected $fillable = [
        'project_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachVkPayOrderId($vkPayOrderId)
    {
        $this->vk_pay_order_id = $vkPayOrderId;
        $this->save();
    }


    public function activate() : ProjectKey
    {
        $activatedKeys = $this->user->getActivatedProjectKeys($this->project);

        $projectKey = $this->project->getRandomProjectKeyExcept($activatedKeys->pluck('id')->toArray());

        DB::transaction(function() use ($projectKey) {
            $this->user->addProjectKey($projectKey);

            $this->project_key_id = $projectKey->id;
            $this->is_fired = true;
            $this->save();
        });

        return $projectKey;
    }
}
