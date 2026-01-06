<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vcard
 * 
 * @property int $id
 * @property int $user_id
 * @property string $slug
 * @property string $template_identifier
 * @property string|null $content
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $template_id
 * 
 * @property User $user
 * @property Template|null $template
 * @property Collection|VcardAnalytic[] $vcard_analytics
 * @property Collection|VcardService[] $vcard_services
 * @property Collection|Appointment[] $appointments
 *
 * @package App\Models
 */
class Vcard extends Model
{
	protected $table = 'vcards';

	protected $casts = [
		'user_id' => 'int',
		'is_active' => 'bool',
		'template_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'slug',
		'template_identifier',
		'content',
		'is_active',
		'template_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function template()
	{
		return $this->belongsTo(Template::class);
	}

	public function vcard_analytics()
	{
		return $this->hasMany(VcardAnalytic::class);
	}

	public function vcard_services()
	{
		return $this->hasMany(VcardService::class);
	}

	public function appointments()
	{
		return $this->hasMany(Appointment::class);
	}
}
