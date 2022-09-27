<?php

    namespace App\Models;

    use App\Traits\AuditableTrait;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Student extends Model
    {
        use SoftDeletes;
        use AuditableTrait;
        use HasFactory;

        protected $table = 'students';

        protected $guarded = [];
    }
