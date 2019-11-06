<?php

namespace App;

use App\Events\CityCoordsFilled;
use App\Events\CityCreated;
use App\Services\VkClient;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;

/**
 * App\City
 *
 * @property int $id
 * @property string $title
 * @property string $center
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Phaza\LaravelPostgis\Eloquent\Builder|City newModelQuery()
 * @method static \Phaza\LaravelPostgis\Eloquent\Builder|City newQuery()
 * @method static \Phaza\LaravelPostgis\Eloquent\Builder|City query()
 * @method static Builder|City whereCenter($value)
 * @method static Builder|City whereCreatedAt($value)
 * @method static Builder|City whereId($value)
 * @method static Builder|City whereTitle($value)
 * @method static Builder|City whereUpdatedAt($value)
 * @mixin Eloquent
 */
class City extends Model
{

    use PostgisTrait;

    protected $fillable = [
        'id',
        'title',
        'center'
    ];

    protected $postgisFields = [
        'center',
    ];

    protected $postgisTypes = [
        'center' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ]
    ];

    protected $dispatchesEvents = [
        'created' => CityCreated::class
    ];


    public static function createFromVk($cityId) {
        $vkCity = (new VkClient())->getCityById($cityId);

        if (!$vkCity) {
            return null;
        }

        return self::create([
           'id' => $vkCity['id'],
           'title' => $vkCity['title']
        ]);
    }

    public function fillCoords() {
        $cities = app('geocoder')->geocode($this->title)->get();

        if ($cities->count() === 0) {
            return;
        }

        $result = $cities->first();

        $this->center = new Point($result->getCoordinates()->getLatitude(), $result->getCoordinates()->getLongitude());
        $this->save();

        event(new CityCoordsFilled($this));
    }
}
