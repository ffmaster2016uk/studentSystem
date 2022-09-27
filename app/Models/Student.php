<?php

    namespace App\Models;

    use App\Interfaces\LoggableInterface;
    use App\Traits\AuditableTrait;
    use App\Traits\LoggableTrait;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Student extends Model implements LoggableInterface
    {
        use SoftDeletes;
        use AuditableTrait;
        use HasFactory;
        use LoggableTrait;

        protected $table = 'students';
        protected $primaryKey = 'Id';

        protected $guarded = [];

        public function processOriginalData($originalData)
        {

            $keysToRemove = [
                'id',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
                'deleted_at',
                'deleted_by',
            ];

            foreach($keysToRemove as $key) {
                unset($originalData[$key]);
            }

            return $originalData;
        }
    }
