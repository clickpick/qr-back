<?php

namespace App;

use App\Events\ProjectKeyActivated;
use App\Events\UserCreated;
use App\Services\VkClient;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Regex\Regex;
use Intervention\Image\Facades\Image;

/**
 * App\User
 *
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 * @property int $id
 * @property int $vk_user_id
 * @property bool $notifications_are_enabled
 * @property bool $messages_are_enabled
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $avatar_200
 * @property string|null $bdate
 * @property int|null $utc_offset
 * @property string|null $visited_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|User whereAvatar200($value)
 * @method static Builder|User whereBdate($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereMessagesAreEnabled($value)
 * @method static Builder|User whereNotificationsAreEnabled($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUtcOffset($value)
 * @method static Builder|User whereVisitedAt($value)
 * @method static Builder|User whereVkUserId($value)
 * @property int $sex
 * @method static Builder|User whereSex($value)
 * @property bool $is_admin
 * @method static Builder|User whereIsAdmin($value)
 * @property-read Collection|ProjectKey[] $projectKeys
 * @property-read int|null $project_keys_count
 * @property-read Collection|ProjectKey[] $activatedProjectKeys
 * @property-read int|null $activated_project_keys_count
 * @property-read Collection|VkPayOrder[] $vkPayOrders
 * @property-read int|null $vk_pay_orders_count
 * @property-read Collection|AvailableCheat[] $availableCheats
 * @property-read int|null $available_cheats_count
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vk_user_id',
        'utc_offset',
        'notifications_are_enabled',
        'messages_are_enabled',
        'visited_at'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projectKeys()
    {
        return $this->belongsToMany(ProjectKey::class)->withPivot('token');
    }

    public function activatedProjectKeys()
    {
        return $this->belongsToMany(ProjectKey::class, 'activated_project_key_user');
    }

    public function vkPayOrders()
    {
        return $this->hasMany(VkPayOrder::class);
    }

    public function availableCheats()
    {
        return $this->hasMany(AvailableCheat::class);
    }


    public function fillPersonalInfoFromVk($data = null)
    {
        $data = $data ?? (new VkClient())->getUsers($this->vk_user_id, ['first_name', 'last_name', 'photo_200', 'timezone', 'sex', 'bdate']);

        $this->first_name = $data['first_name'] ?? null;
        $this->last_name = $data['last_name'] ?? null;
        $this->avatar_200 = $data['photo_200'] ?? null;
        $this->sex = $data['sex'] ?? 0;


        if (isset($data['bdate'])) {
            $reYear = Regex::match('/\d{1,2}.\d{1,2}.\d{4}/', $data['bdate']);
            $reDay = Regex::match('/\d{1,2}.\d{1,2}/', $data['bdate']);

            if ($reYear->hasMatch()) {
                $this->bdate = Carbon::parse($data['bdate']);
            } elseif ($reDay->hasMatch()) {

                $date = explode('.', $data['bdate']);

                $bdate = new Carbon();

                $bdate->setYear(1);
                $bdate->setMonth($date[1]);
                $bdate->setDay($date[0]);

                $this->bdate = $bdate;

            } else {
                $this->bdate = null;
            }
        }

        if (isset($data['timezone'])) {
            $this->utc_offset = $data['timezone'] * 60;
        }

        $this->save();
    }

    /**
     * @param $vkId
     * @return User
     */
    public static function getByVkId($vkId): ?self
    {

        if (!$vkId) {
            return null;
        }

        return self::firstOrCreate(['vk_user_id' => $vkId]);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }


    public function getProjectKeyForProject(Project $project)
    {
        if ($this->projectKeys()->where('project_id', $project->id)->exists()) {
            return $this->projectKeys()->where('project_id', $project->id)->first();
        }

        $randomProductKey = $project->getRandomProjectKey();

        $this->projectKeys()->sync([$randomProductKey->id => [
            'token' => Str::random()
        ]]);

        return $this->projectKeys()->where('project_id', $project->id)->first();
    }

    public function disableNotifications()
    {
        $this->notifications_are_enabled = false;
        $this->save();
    }

    public function getActivatedProjectKeys(Project $project)
    {
        $activatedIds = DB::table('activated_project_key_user')
            ->where('project_keys.project_id', $project->id)
            ->where('activated_project_key_user.user_id', $this->id)
            ->select('activated_project_key_user.project_key_id')
            ->join('project_keys', 'activated_project_key_user.project_key_id', '=', 'project_keys.id')
            ->get()
            ->map(function ($item) {
                return $item->project_key_id;
            });

        return ProjectKey::whereIn('id', $activatedIds)->get();
    }

    public function hasAvailableNotFiredCheatForProject(Project $project)
    {
        return $this->availableCheats()
            ->where('project_id', $project->id)
            ->where('is_fired', false)
            ->exists();
    }

    public function hasAvailableCheatForProject(Project $project)
    {
        return $this->availableCheats()
            ->where('project_id', $project->id)
            ->exists();
    }


    public function hasAvailableNotFiredCheatForActiveProject()
    {
        $activeProject = Project::where('is_active', true)->first();

        /** @var Project $activeProject */
        return $this->hasAvailableNotFiredCheatForProject($activeProject);
    }

    public function addCheatForProject(Project $project)
    {
        return $this->availableCheats()->create([
            'project_id' => $project->id
        ]);
    }

    public function removeCheatForProject(Project $project)
    {
        return $this->availableCheats()
            ->where('project_id', $project->id)
            ->where('is_fired', false)
            ->delete();
    }

    public function getAvailableCheatForProject(Project $project) : AvailableCheat {
        return $this->availableCheats()
            ->where('project_id', $project->id)
            ->first();
    }


    public function addProjectKey(ProjectKey $projectKey) {
        $this->activatedProjectKeys()->attach($projectKey->id);
        event(new ProjectKeyActivated($projectKey, $this));
    }

    public function postStory($base64Image, $uploadUrl)
    {
        $image = Image::make($base64Image);
        (new VkClient())->postStory($uploadUrl, $image);
    }
}
