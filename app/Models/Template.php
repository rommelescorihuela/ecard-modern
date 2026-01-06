<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Template
 * 
 * @property int $id
 * @property string $name
 * @property string $identifier
 * @property string|null $preview_image
 * @property bool $is_premium
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Plan[] $plans
 * @property Collection|Vcard[] $vcards
 *
 * @package App\Models
 */
class Template extends Model
{
	protected $table = 'templates';

	protected $casts = [
		'is_premium' => 'bool'
	];

	protected $fillable = [
		'name',
		'identifier',
		'preview_image',
		'is_premium'
	];

	public function plans()
	{
		return $this->belongsToMany(Plan::class)
					->withPivot('id')
					->withTimestamps();
	}

	public function vcards()
	{
		return $this->hasMany(Vcard::class);
	}
}
