<?php

namespace App\Models\Concerns;

use App\Models\MemberLog;

/**
 * Journal des modifications du module Adhérents : création, modification (ancienne → nouvelle valeur), suppression.
 * Le modèle fournit memberIdForLog() et logSubject().
 */
trait LogsMemberChanges
{
    /** Désactiver le journal (ex. : import, qui écrit sa propre ligne). */
    public static bool $logging = true;

    protected static function bootLogsMemberChanges(): void
    {
        static::created(fn ($model) => $model->writeLog('created', null));
        static::updated(function ($model) {
            $changes = [];
            foreach ($model->getChanges() as $field => $new) {
                if (in_array($field, ['updated_at', 'created_at'], true)) {
                    continue;
                }
                $changes[$field] = [$model->getOriginal($field), $new];
            }
            if ($changes) {
                $model->writeLog('updated', $changes);
            }
        });
        static::deleted(fn ($model) => $model->writeLog('deleted', null));
    }

    protected function writeLog(string $action, ?array $changes): void
    {
        if (! static::$logging) {
            return;
        }
        $memberId = $this->memberIdForLog();
        MemberLog::create([
            'member_id' => $action === 'deleted' && $this instanceof \App\Models\Member ? null : $memberId,
            'user_id' => auth()->id(),
            'action' => $action,
            'subject' => $this->logSubject() . ($action === 'deleted' && $this instanceof \App\Models\Member ? ' (' . $this->full_name . ')' : ''),
            'changes' => $changes ? $this->sanitizeChanges($changes) : null,
            'created_at' => now(),
        ]);
    }

    /** Valeurs lisibles et courtes dans le journal. */
    private function sanitizeChanges(array $changes): array
    {
        return collect($changes)->map(fn ($pair) => array_map(function ($v) {
            if (is_array($v)) {
                return json_encode($v, JSON_UNESCAPED_UNICODE);
            }
            if ($v instanceof \DateTimeInterface) {
                return $v->format('Y-m-d');
            }

            return is_string($v) ? mb_substr($v, 0, 200) : $v;
        }, $pair))->all();
    }
}
