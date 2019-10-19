<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 */
class AvailableCheat extends Model
{
    protected $fillable = [
        'project_id'
    ];

    public function attachVkPayOrderId($vkPayOrderId) {
        $this->vk_pay_order_id = $vkPayOrderId;
        $this->save();
    }
}
