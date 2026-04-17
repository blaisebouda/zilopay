<?php

namespace App\Models\Traits;

use App\Models\Enums\LockActiveStatus;

trait HasLockActiveStatus
{
    public function isActive(): bool
    {
        return $this->status->equals(LockActiveStatus::ACTIVE);
    }

    public function isLocked(): bool
    {
        return $this->status->equals(LockActiveStatus::LOCKED);
    }

    public function lock()
    {
        $this->status = LockActiveStatus::LOCKED;
        $this->save();
    }

    public function unlock()
    {
        $this->status = LockActiveStatus::ACTIVE;
        $this->save();
    }

    public function toggle()
    {
        if ($this->isActive()) {
            $this->lock();
        } else {
            $this->unlock();
        }
    }
}
