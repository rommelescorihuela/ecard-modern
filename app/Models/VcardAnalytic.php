<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VcardAnalytic
 * 
 * @property int $id
 * @property int $vcard_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $referrer
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Vcard $vcard
 *
 * @package App\Models
 */
class VcardAnalytic extends Model
{
	protected $table = 'vcard_analytics';

	protected $casts = [
		'vcard_id' => 'int'
	];

	protected $fillable = [
		'vcard_id',
		'ip_address',
		'user_agent',
		'referrer'
	];

	public function vcard()
	{
		return $this->belongsTo(Vcard::class);
	}
}
