<?php

namespace App\Services;

use App\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AuditLogger
{
    protected static bool $checkedSchema = false;
    protected static bool $canWrite = true;

    /**
     * Record a new audit log entry.
     *
     * @param  string  $action
     * @param  \Illuminate\Database\Eloquent\Model|array|string|int|null  $subject
     * @param  array  $properties
     * @param  string|null  $description
     */
    public static function log(string $action, $subject = null, array $properties = [], ?string $description = null): void
    {
        if (!self::shouldLog()) {
            return;
        }

        [$subjectType, $subjectId] = self::resolveSubject($subject);

        /** @var Request|null $request */
        $request = null;

        try {
            $request = request();
        } catch (\Throwable $th) {
            // Request helper is not available (e.g., during CLI runs)
        }

        $data = [
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'properties' => empty($properties) ? null : $properties,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? substr((string) $request->userAgent(), 0, 500) : null,
        ];

        try {
            AuditLog::create($data);
        } catch (\Throwable $th) {
            // Never block user flow because logging failed
            Log::warning('Audit log write failed', [
                'action' => $action,
                'error' => $th->getMessage(),
            ]);
        }
    }

    protected static function shouldLog(): bool
    {
        if (function_exists('site_config') && !site_config('audit_logging', true)) {
            return false;
        }

        if (!self::$checkedSchema) {
            try {
                self::$canWrite = Schema::hasTable('audit_logs');
            } catch (\Throwable $th) {
                self::$canWrite = false;
            }

            self::$checkedSchema = true;
        }

        return self::$canWrite;
    }

    /**
     * Resolve the subject polymorphic info.
     *
     * @param  mixed  $subject
     * @return array{0:?string,1:?int}
     */
    protected static function resolveSubject($subject): array
    {
        if ($subject instanceof Model) {
            return [get_class($subject), $subject->getKey()];
        }

        if (is_array($subject) && isset($subject['type'], $subject['id'])) {
            return [$subject['type'], (int) $subject['id']];
        }

        if (is_numeric($subject)) {
            return [null, (int) $subject];
        }

        return [null, null];
    }
}
