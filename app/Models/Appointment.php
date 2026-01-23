<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Appointment
 * 
 * @property int $id
 * @property int $vcard_id
 * @property string $client_name
 * @property string $client_email
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Vcard $vcard
 *
 * @package App\Models
 */
class Appointment extends Model
{
	use \App\Traits\BelongsToVcard;

	protected $table = 'appointments';

	protected $casts = [
		'vcard_id' => 'int',
		'start_time' => 'datetime',
		'end_time' => 'datetime'
	];

	protected $fillable = [
		'vcard_id',
		'client_name',
		'client_email',
		'start_time',
		'end_time',
		'status'
	];

	public function vcard()
	{
		return $this->belongsTo(Vcard::class);
	}
}
