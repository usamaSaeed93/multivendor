<?php

namespace App\Models;

use App\Jobs\ProcessNotification;
use App\Jobs\ProcessSMS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;


/**
 * @property mixed $notifiable_id
 * @property mixed $notifiable_type
 * @property mixed $message
 * @method static where(string $string, string $string1, mixed $user_id)
 */
class ShortMessage extends Model
{
    //===================== Defaults  ====================================//

    protected $guarded = [];

    public static string $order_type = 'order';
    public static string $other_type = 'other';


    //===================== Functionalities  ====================================//

    public function send_sms(){
        ProcessSMS::dispatch($this);
    }

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }



}
