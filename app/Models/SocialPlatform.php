<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialPlatform
 * 
 * @property int $id
 * @property string $name
 * @property string $icon_path
 * @property string $base_url
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SocialPlatform extends Model
{
	protected $table = 'social_platforms';

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $fillable = [
		'name',
		'icon_path',
		'base_url',
		'is_active'
	];
}
