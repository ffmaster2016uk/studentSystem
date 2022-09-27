<?php

    namespace App\Traits;

    use App\Models\ActivityLog;
    use App\Helpers\Helpers;

    trait LoggableTrait
    {
        public function logs()
        {
            return $this->morphMany(ActivityLog::class, 'loggable');
        }

        public function buildChangedDataStructure($originalData, $submittedData)
        {
            $result = [];
            $originalData = $this->processOriginalData($originalData);
            foreach($originalData as $field => $value) {
                if(array_key_exists($field, $submittedData)) {
                    if(is_array($value) && is_array($submittedData[$field])) {
                        if(!(count($value) == 0 && count($submittedData[$field]) == 1 && $submittedData[$field][0] == null)) {
                            $diff = Helpers::arrayRecursiveDiff($submittedData[$field], $value);
                            if(count($diff) > 0) {
                                $result[] = [
                                    'fieldName' => $field,
                                    'oldData' => $value,
                                    'newData' => $submittedData[$field],
                                    'diff' => $diff
                                ];
                            }
                        }
                    } else {
                        if($value != $submittedData[$field]) {
                            $result[] = [
                                'fieldName' => $field,
                                'oldData' => $value,
                                'newData' => $submittedData[$field]
                            ];
                        }
                    }

                } else if(!array_key_exists($field, $submittedData) && $originalData[$field] != null) {
                    $result[] = [
                        'fieldName' => $field,
                        'oldData' => $value,
                        'newData' => null
                    ];
                }
            }

            return json_encode($result);
        }
    }
