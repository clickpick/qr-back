<?php

namespace App;

use App\Events\ProjectCreated;
use App\Events\ProjectHasFinished;
use App\Services\VkClient;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
 * @property bool $is_finished
 * @method static Builder|Project whereIsFinished($value)
 * @property string|null $link
 * @property string|null $contact
 * @method static Builder|Project whereContact($value)
 * @method static Builder|Project whereLink($value)
 * @property string|null $big_description
 * @method static Builder|Project whereBigDescription($value)
 */
class Project extends Model implements HasMedia
{
    use HasMediaTrait;

    public const WAITING = 0;
    public const APPROVED = 1;
    public const DECLINE = 2;


    const MAX_PERCENT = 10000;


    const KEYS_COUNT = 5;

    protected $fillable = [
        'name',
        'description',
        'big_description',
        'raised_funds',
        'goal_funds',
        'prize',
        'winners_count',
        'is_active',
        'status',
        'link',
        'contact'
    ];

    protected $dispatchesEvents = [
        'created' => ProjectCreated::class
    ];

    public function projectKeys()
    {
        return $this->hasMany(ProjectKey::class);
    }

    public function projectFacts()
    {
        return $this->hasMany(ProjectFact::class);
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

    public function generateProjectKeys($count = self::KEYS_COUNT)
    {
        $minRate = 1;
        $rate = $minRate;
        $sum = 0;

        for ($i = 0; $i < $count; $i++) {

            if ($i > 0 && $i < $count - 1) {
                $maxRate = self::MAX_PERCENT - $sum - $minRate * ($count - $i);

                $rate = rand($minRate, $maxRate);
            }

            if ($i === $count - 1) {
                $rate = self::MAX_PERCENT - $sum;
            }

            $this->projectKeys()->create([
                'value' => Str::random(1),
                'order' => $i,
                'drop_rate' => $rate
            ]);

            $sum += $rate;
        }
    }


    public function getRandomProjectKey()
    {
        $dispersion = $this->projectKeys->reduce(function ($carry, ProjectKey $projectKey) {
            return array_merge($carry, array_fill(1, $projectKey->drop_rate, $projectKey->id));
        }, []);


        $projectKeyId = Arr::random($dispersion);

        return ProjectKey::find($projectKeyId);
    }

    public function getRandomProjectKeyExcept(array $ids)
    {
        $dispersion = $this->projectKeys()->whereNotIn('id', $ids)->get()->reduce(function ($carry, ProjectKey $projectKey) {
            return array_merge($carry, array_fill(1, $projectKey->drop_rate * 100, $projectKey->id));
        }, []);


        $projectKeyId = Arr::random($dispersion);

        return ProjectKey::find($projectKeyId);
    }

    public function checkIsFinished()
    {
        $keysCount = $this->projectKeys()->count();


        $winners = DB::table('activated_project_key_user')
            ->join('project_keys', 'activated_project_key_user.project_key_id', '=', 'project_keys.id')
            ->where('project_keys.project_id', $this->id)
            ->having(DB::raw('count(activated_project_key_user.id)'), '>=', $keysCount)
            ->groupBy('activated_project_key_user.user_id')
            ->select(DB::raw('count(activated_project_key_user.id) as count'))
            ->get();

        if ($winners->count() >= $this->winners_count) {
            $this->is_finished = true;
            $this->save();

            event(new ProjectHasFinished($this));
        }
    }

    public function sendFinishedNotification()
    {
        $usersIdsObject = DB::table('activated_project_key_user')
            ->where('project_keys.project_id', $this->id)
            ->join('project_keys', 'activated_project_key_user.project_key_id', '=', 'project_keys.id')
            ->distinct('user_id')
            ->select('user_id')->get();

        $userIds = $usersIdsObject->pluck('user_id');

        $users = User::whereIn('id', $userIds)
            ->where('notifications_are_enabled', true)
            ->select('vk_user_id')
            ->get();

        $vkIds = $users->pluck('vk_user_id');


        (new VkClient())->sendPushes($vkIds, "Спасибо за участие! За время флэшмоба мы собрали {$this->raised_funds}₽ на проект ”{$this->name}”. 
Несмотря на то, что проект закончился, вы можете
пожертвовать средства, которые пойдут на этот или другие проекты этого фонда.");
    }

    public function activate()
    {
        DB::transaction(function () {
            Project::whereIsActive(true)->update([
                'is_active' => false
            ]);

            $this->is_active = true;
            $this->save();
        });
    }

    public function addFunds($value)
    {
        $this->raised_funds += $value;
        $this->save();
    }


    public static function getActive()
    {
        return self::whereIsActive(true)->first();
    }

    public function getWinners()
    {
        $r = DB::table('activated_project_key_user')
            ->join('project_keys', 'activated_project_key_user.project_key_id', '=', 'project_keys.id')
            ->where('project_keys.project_id', $this->id)
            ->having(DB::raw('count(activated_project_key_user.id)'), '>=', $this->projectKeys()->count())
            ->groupBy('activated_project_key_user.user_id')
            ->select(DB::raw('count(activated_project_key_user.id) as count, max(activated_project_key_user.user_id) as user_id'))
            ->orderBy(DB::raw('max(activated_project_key_user.id)'))
            ->get();

        $users = User::whereIn('id', $r->pluck('user_id'))->get()->mapWithKeys(function (User $user) {
            return [
                $user->id => $user
            ];
        });

        return $r->map(function ($item) use ($users) {
            return $users->get($item->user_id);
        });
    }
}
