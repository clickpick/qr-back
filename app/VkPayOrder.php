<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


/**
 * App\VkPayOrder
 *
 * @property string $id
 * @property int $user_id
 * @property float $amount
 * @property int $status
 * @property mixed|null $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|VkPayOrder newModelQuery()
 * @method static Builder|VkPayOrder newQuery()
 * @method static Builder|VkPayOrder query()
 * @method static Builder|VkPayOrder whereAmount($value)
 * @method static Builder|VkPayOrder whereCreatedAt($value)
 * @method static Builder|VkPayOrder whereId($value)
 * @method static Builder|VkPayOrder wherePayload($value)
 * @method static Builder|VkPayOrder whereStatus($value)
 * @method static Builder|VkPayOrder whereUpdatedAt($value)
 * @method static Builder|VkPayOrder whereUserId($value)
 * @mixin Eloquent
 * @property string $destination
 * @method static Builder|VkPayOrder whereDestination($value)
 */
class VkPayOrder extends Model
{
    const CREATED = 0;
    const PAID = 1;
    const HOLD = 2;
    const DECLINED = 3;

    const DONATE = 'donate';
    const CHEAT = 'cheat';

    const CHEAT_VALUE = 50;

    protected $fillable = [
        'amount',
        'payload',
        'status',
        'destination'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = (string)Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    private function convertStatus($status) {
        switch ($status) {
            case 'PAID':
                return self::PAID;

            case 'HOLD':
                return self::HOLD;
            default:
                return self::CREATED;
        }
    }

    public function approve($status, $payload) {

        if ($this->status === self::PAID) {
            return;
        }

        $status = $this->convertStatus($status);

        if ($status === self::CREATED) {
            return;
        }

        $this->status = $status;
        $this->payload = $payload;

        $this->save();
    }
}
