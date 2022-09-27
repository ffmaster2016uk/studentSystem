<?php

    namespace App\Traits;

    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    trait AuditableTrait {

        private $auditableTraitAttributes = ["created_by", "updated_by", "deleted_by"];

        protected $auditableOverridden = false;


        /**
         * Initialise storing of created_by, updated_by and deleted_by on creating, saving and deleting model events
         * @return void
         */
        public static function bootAuditableTrait() {


            static::creating(function($model) {
                if (Auth::user()) {
                    $model->created_by = Auth::user()->id;
                }
            });

            static::saving(function($model) {
                if (Auth::user()) {
                    $model->updated_by = Auth::user()->id;
                }
            });

            static::deleting(function($model) {
                if (Auth::user()) {
                    $model->deleted_by = Auth::user()->id;
                    $model->save();
                }
            });

            static::restoring(function($model) {
                $model->deleted_by = null;
            });
        }

        /**
         * Accessor for the creator of this object
         * @return User
         */
        public function creator() {
            return $this->belongsTo(User::class, "created_by");
        }

        /**
         * Accessor for the last editor of this object
         * @return User
         */
        public function updater() {
            return $this->belongsTo(User::class, "updated_by");
        }

        /**
         * Accessor for the deleter of this object
         * @return User
         */
        public function deleter() {
            return $this->belongsTo(User::class, "deleted_by");
        }

        public function overrideAuditable() {
            $this->auditableOverridden = true;
        }
    }
