<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use App\Traits\BelongsToVcard;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $plan_id
 *
 * @property Plan|null $plan
 * @property Collection|Vcard[] $vcards
 *
 * @package App\Models
 */
class User extends Authenticatable implements FilamentUser, HasTenants
{
	use HasFactory, HasRoles, BelongsToVcard, \App\Traits\ActivityLogger, \Laravel\Cashier\Billable;

	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'plan_id' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'plan_id',
		'vcard_id'
	];

	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}

	public function vcards()
	{
		return $this->hasMany(Vcard::class);
	}

	public function canAccessPanel(Panel $panel): bool
	{
		if ($panel->getId() === 'admin') {
			return $this->vcard_id === null;
		}

		if ($panel->getId() === 'app') {
			return $this->vcard_id !== null;
		}

		return true;
	}

	public function getTenants(Panel $panel): Collection
	{
		return Collection::make($this->vcard ? [$this->vcard] : []);
	}

	public function canAccessTenant(Model $tenant): bool
	{
		return $this->vcard_id === $tenant->id;
	}
}
