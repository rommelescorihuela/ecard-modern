<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VcardService
 * 
 * @property int $id
 * @property int $vcard_id
 * @property string $title
 * @property string|null $description
 * @property float|null $price
 * @property string|null $image_url
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Vcard $vcard
 *
 * @package App\Models
 */
class VcardService extends Model
{
	protected $table = 'vcard_services';

	protected $casts = [
		'vcard_id' => 'int',
		'price' => 'float',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'vcard_id',
		'title',
		'description',
		'price',
		'image_url',
		'is_active'
	];

	public function vcard()
	{
		return $this->belongsTo(Vcard::class);
	}
}
