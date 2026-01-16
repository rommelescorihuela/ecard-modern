<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlanTemplate
 * 
 * @property int $id
 * @property int $plan_id
 * @property int $template_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Plan $plan
 * @property Template $template
 *
 * @package App\Models
 */
class PlanTemplate extends Model
{
	protected $table = 'plan_template';

	protected $casts = [
		'plan_id' => 'int',
		'template_id' => 'int'
	];

	protected $fillable = [
		'plan_id',
		'template_id'
	];

	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}

	public function template()
	{
		return $this->belongsTo(Template::class);
	}
}
