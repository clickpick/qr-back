<?php

namespace App;

use App\Events\ProjectCreated;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * App\Project
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $raised_funds
 * @property int $goal_funds
 * @property string $prize
 * @property int $winners_count
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Project newModelQuery()
 * @method static Builder|Project newQuery()
 * @method static Builder|Project query()
 * @method static Builder|Project whereCreatedAt($value)
 * @method static Builder|Project whereDescription($value)
 * @method static Builder|Project whereGoalFunds($value)
 * @method static Builder|Project whereId($value)
 * @method static Builder|Project whereIsActive($value)
 * @method static Builder|Project whereName($value)
 * @method static Builder|Project wherePrize($value)
 * @method static Builder|Project whereRaisedFunds($value)
 * @method static Builder|Project whereUpdatedAt($value)
 * @method static Builder|Project whereWinnersCount($value)
 * @mixin Eloquent
 * @property-read Collection|ProjectKey[] $projectKeys
 * @property-read int|null $project_keys_count
 * @property-read Collection|ProjectFact[] $projectFacts
 * @property-read int|null $project_facts_count
 * @property int $status
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|Project whereStatus($value)
 */
class Project extends Model implements HasMedia
{
    use HasMediaTrait;

    public const WAITING = 0;
    public const APPROVED = 1;
    public const DECLINE = 2;

    protected $fillable = [
        'name',
        'description',
        'raised_funds',
        'goal_funds',
        'prize',
        'winners_count',
        'is_active',
        'status'
    ];

    protected $dispatchesEvents = [
        'created' => ProjectCreated::class
    ];

    public function projectKeys() {
        return $this->hasMany(ProjectKey::class);
    }

    public function projectFacts() {
        return $this->hasMany(ProjectFact::class);
    }

    public function scopeVisibleForUsers(Builder $query) {
        return $query->where('status', self::APPROVED);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('poster')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('card')
                    ->crop(Manipulations::CROP_TOP, 50 * 3, 50 * 3);
            });

        $this->addMediaCollection('banner')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('card')
                    ->crop(Manipulations::CROP_TOP, 335 * 3, 138 * 3);
            });
    }

    public function generateProjectKeys($count = 5) {

        $minRate = 10;

        $otherRate = round((100 - $minRate) / ($count - 1), 2);

        $randOrder = rand(0, $count);

        for ($i = 0; $i < $count; $i++) {
            $this->projectKeys()->create([
                'value' => Str::random(1),
                'order' => $i,
                'drop_rate' => $i === $randOrder ? $minRate : $otherRate
            ]);
        }
    }


    public function getRandomProjectKey() {
        $dispersion = $this->projectKeys->reduce(function($carry, ProjectKey $projectKey) {
            return array_merge($carry, array_fill(1, $projectKey->drop_rate * 100, $projectKey->id));
        }, []);


        $projectKeyId = Arr::random($dispersion);

        return ProjectKey::find($projectKeyId);
    }
}
