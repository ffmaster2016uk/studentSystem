<?php

    namespace App\Database;

    use Illuminate\Database\Schema\Blueprint;

    trait AuditableTrait {
        /**
         * Adds timestamps, soft deletes and cols for created_by, updated_by, deleted_by
         *
         * @param Blueprint $table
         *
         */
        public function auditable(Blueprint $table)
        {
            $table->timestamps();
            $table->softDeletes();
            $this->createdBy($table);
            $this->updatedBy($table);
            $element = $this->deletedBy($table);
            return $element;
        }

        /**
         * Adds unsigned integer created_by not null with index
         */
        public function createdBy(Blueprint $table)
        {
            $column = $table->unsignedInteger("created_by")->nullable();
            $table->index("created_by");
            return $column;
        }

        /**
         * Adds unsigned integer updated_by not null with index
         */
        public function updatedBy(Blueprint $table)
        {
            $column = $table->unsignedInteger("updated_by")->nullable();
            $table->index("updated_by");
            return $column;
        }

        /**
         * Adds unsigned integer deleted_by not null with index
         */
        public function deletedBy(Blueprint $table)
        {
            $column = $table->unsignedInteger("deleted_by")->nullable();
            $table->index("deleted_by");
            return $column;
        }
    }
