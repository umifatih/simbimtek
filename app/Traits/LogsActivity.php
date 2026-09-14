<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(fn ($model) => $model->catatLog('created'));
        static::updated(fn ($model) => $model->catatLog('updated'));
        static::deleted(fn ($model) => $model->catatLog('deleted'));
    }

    protected function catatLog(string $action): void
    {
        $adminId = Auth::guard('admin')->id();

        if (!$adminId) {
            return;
        }

        $label = method_exists($this, 'labelAktivitas')
            ? $this->labelAktivitas()
            : class_basename($this) . ' #' . $this->id;

        $deskripsi = match ($action) {
            'created' => "Menambahkan {$label}",
            'updated' => "Mengubah {$label}",
            'deleted' => "Menghapus {$label}",
        };

        ActivityLog::create([
            'admin_id' => $adminId,
            'action' => $action,
            'model_type' => class_basename($this),
            'model_id' => $this->id,
            'description' => $deskripsi,
            'old_values' => $action === 'updated' ? $this->getOriginal() : null,
            'new_values' => $action !== 'deleted' ? $this->getChanges() : null,
        ]);
    }
}