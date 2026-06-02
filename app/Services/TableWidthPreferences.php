<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TableWidthPreferences
{
    public const PREF_KEY = 'table_widths';

    private const HARDCODED_DEFAULTS = [
        // Cases index table (percent widths by column index)
        'casesTable_widths' => ['17%', '17%', '18%', '14.4%', '7%', '14.4%', '12.2%'],
        //'casesTable_widths' => ['17%', '17%', '18%', '14.4%', '7%', '14.4%', '12.2%'],


    ];

    private static $resolvedConfig = null;

    private static function resolveConfig(): ?array
    {
        if (self::$resolvedConfig !== null) {
            return self::$resolvedConfig;
        }

        $table = config('app.user_preferences_table', 'user_preferences');
        try {
            if (!Schema::hasTable($table)) {
                self::$resolvedConfig = null;
                return null;
            }

            $columns = Schema::getColumnListing($table);
        } catch (\Throwable $e) {
            self::$resolvedConfig = null;
            return null;
        }
        $keyColumn = in_array('pref_key', $columns, true) ? 'pref_key' : (in_array('pref_id', $columns, true) ? 'pref_id' : null);
        $scopeColumn = in_array('pref_scope', $columns, true) ? 'pref_scope' : null;
        $valueColumn = in_array('pref_value', $columns, true) ? 'pref_value' : (in_array('pref_data', $columns, true) ? 'pref_data' : null);

        if (!$keyColumn || !$valueColumn) {
            self::$resolvedConfig = null;
            return null;
        }

        self::$resolvedConfig = [
            'table' => $table,
            'keyColumn' => $keyColumn,
            'scopeColumn' => $scopeColumn,
            'valueColumn' => $valueColumn,
            'hasCreatedAt' => in_array('created_at', $columns, true),
            'hasUpdatedAt' => in_array('updated_at', $columns, true),
        ];

        return self::$resolvedConfig;
    }

    private static function encodeScopeKey(string $scope): string
    {
        return self::PREF_KEY . ':' . $scope;
    }

    private static function extractScopeFromKey(string $key): ?string
    {
        $prefix = self::PREF_KEY . ':';
        if (strpos($key, $prefix) !== 0) {
            return null;
        }
        return substr($key, strlen($prefix));
    }

    private static function decodeValue($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value === null) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    public static function getForUser(int $userId): array
    {
        $config = self::resolveConfig();
        if (!$config) {
            return [];
        }

        $query = DB::table($config['table'])->where('user_id', $userId);
        if ($config['scopeColumn']) {
            $query->where($config['keyColumn'], self::PREF_KEY);
        } else {
            $query->where($config['keyColumn'], 'like', self::PREF_KEY . ':%');
        }

        $prefs = [];
        foreach ($query->get() as $row) {
            $scope = $config['scopeColumn']
                ? $row->{$config['scopeColumn']}
                : self::extractScopeFromKey($row->{$config['keyColumn']});

            if (!$scope) {
                continue;
            }

            $prefs[$scope] = self::decodeValue($row->{$config['valueColumn']});
        }

        return $prefs;
    }

    public static function getDefaults(): array
    {
        return self::HARDCODED_DEFAULTS;
    }

    public static function save(int $userId, string $scope, array $widths): bool
    {
        $config = self::resolveConfig();
        if (!$config) {
            return false;
        }

        $now = now();
        $keyValue = $config['scopeColumn'] ? self::PREF_KEY : self::encodeScopeKey($scope);

        $where = [
            'user_id' => $userId,
            $config['keyColumn'] => $keyValue,
        ];
        if ($config['scopeColumn']) {
            $where[$config['scopeColumn']] = $scope;
        }

        $data = [
            $config['valueColumn'] => json_encode($widths),
        ];
        if ($config['hasUpdatedAt']) {
            $data['updated_at'] = $now;
        }

        $query = DB::table($config['table'])->where($where);
        if ($query->exists()) {
            $query->update($data);
            return true;
        }

        if ($config['hasCreatedAt']) {
            $data['created_at'] = $now;
        }

        DB::table($config['table'])->insert(array_merge($where, $data));
        return true;
    }

    public static function delete(int $userId, ?string $scope = null): int
    {
        $config = self::resolveConfig();
        if (!$config) {
            return 0;
        }

        $query = DB::table($config['table'])->where('user_id', $userId);
        if ($config['scopeColumn']) {
            $query->where($config['keyColumn'], self::PREF_KEY);
            if ($scope !== null) {
                $query->where($config['scopeColumn'], $scope);
            }
        } else {
            if ($scope !== null) {
                $query->where($config['keyColumn'], self::encodeScopeKey($scope));
            } else {
                $query->where($config['keyColumn'], 'like', self::PREF_KEY . ':%');
            }
        }

        return $query->delete();
    }
}
