<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Plan
 * 
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $limit_vcards
 * @property string|null $features
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|User[] $users
 * @property Collection|Template[] $templates
 *
 * @package App\Models
 */
class Plan extends Model
{
	protected $table = 'plans';

	protected $casts = [
		'price' => 'float',
		'limit_vcards' => 'int'
	];

	protected $fillable = [
		'name',
		'price',
		'limit_vcards',
		'features'
	];

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function templates()
	{
		return $this->belongsToMany(Template::class)
					->withPivot('id')
					->withTimestamps();
	}
}
