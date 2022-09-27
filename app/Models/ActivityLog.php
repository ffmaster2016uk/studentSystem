<?php

    namespace App\Models;

    use App\Traits\AuditableTrait;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class ActivityLog extends Model
    {
        use SoftDeletes;
        use AuditableTrait;

        protected $table = 'activity_logs';

        protected $fillable = [
            'type',
            'subject',
            'data_submitted',
            'data_changed',
            'previous_data',
            'response'
        ];

        protected $dates = [
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        public function loggable()
        {
            return $this->morphTo();
        }
    }
