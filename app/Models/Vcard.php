<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

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
class Vcard extends Model implements Tenant
{
	use HasDomains, CentralConnection, \App\Traits\ActivityLogger;

	protected $casts = [
		'user_id' => 'int',
		'is_active' => 'bool',
		'template_id' => 'int',
		'content' => 'json',
		'last_built_at' => 'datetime',
		'onboarding_step' => 'integer',
		'has_appointments' => 'boolean',
		'has_contact_form' => 'boolean'
	];

	protected $fillable = [
		'user_id',
		'slug',
		'template_identifier',
		'content',
		'is_active',
		'template_id',
		'last_built_at',
		'onboarding_step',
		'has_appointments',
		'has_contact_form'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function template()
	{
		return $this->belongsTo(Template::class);
	}

	public function domains()
	{
		return $this->hasMany(Domain::class, 'vcard_id');
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

	public function getTenantKeyName(): string
	{
		return 'id';
	}

	public function getTenantKey()
	{
		return $this->getAttribute($this->getTenantKeyName());
	}

	public function run(callable $callback)
	{
		// For single DB, we just set the tenant in context
		// But Stancl expects this method.
		// In Single DB, run is less relevant than scoping, but required by interface
		$originalTenant = tenant();
		tenancy()->initialize($this);
		$result = $callback($this);
		if ($originalTenant) {
			tenancy()->initialize($originalTenant);
		} else {
			tenancy()->end();
		}
		return $result;
	}

	public function getInternal(string $key)
	{
		return $this->getAttribute($key);
	}

	public function setInternal(string $key, $value)
	{
		$this->setAttribute($key, $value);
		return $this;
	}
}
