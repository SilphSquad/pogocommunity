<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_roles', 'is_staff', 'is_owner', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_roles' => 'array',
        'is_staff' => 'boolean',
        'is_owner' => 'boolean',
    ];

    public function trainers()
    {
        return $this->hasMany('App\Trainer', 'user_id', 'id');
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    /**
     * @return bool
     */
    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * @return bool
     */
    public function isStaff()
    {
        return $this->is_staff == 1;
    }

    /**
     * @return bool
     */
    public function isOwner()
    {
        return $this->is_owner == 1;
    }

    // Roles

    /***
     * @param string $role
     * @return $this
     */
    public function addRole(string $role)
    {
        $roles = $this->getRoles();
        $roles[] = $role;

        $roles = array_unique($roles);
        $this->setRoles($roles);

        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->setAttribute('user_roles', $roles);
        return $this;
    }

    /***
     * @param $role
     * @return mixed
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /***
     * @param $roles
     * @return mixed
     */
    public function hasRoles($roles)
    {
        $currentRoles = $this->getRoles();
        foreach ($roles as $role) {
            if (!in_array($role, $currentRoles)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->getAttribute('user_roles');

        if (is_null($roles)) {
            $roles = [];
        }

        return $roles;
    }
}
