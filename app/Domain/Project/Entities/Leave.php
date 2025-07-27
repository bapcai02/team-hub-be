<?php

namespace App\Domain\Project\Entities;

/**
 * Class Leave
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Leave
{
    public int $id;
    public int $project_id;
    public int $user_id;
    public string $start_date;
    public string $end_date;
    public string $reason;
    public string $status;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id,
        int $project_id,
        int $user_id,
        string $start_date,
        string $end_date,
        string $reason,
        string $status,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->user_id = $user_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->reason = $reason;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
} 