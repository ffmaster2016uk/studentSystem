<?php

    namespace App\Services;

    use App\Events\RecordCreated;
    use App\Events\RecordUpdated;
    use App\Models\Student;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\App;

    class StudentServices
    {
        const ID_KEY = 'Id';
        const SEARCH_FIELD_KEY = 'searchField';
        const SEARCH_VALUE_KEY = 'searchValue';

        public function getStoreValidationRules() : array
        {
            return [
                'Name' => 'required|legal_characters',
                'Surname' => 'required|legal_characters',
                'IdentificationNo' => 'required|legal_characters|unique:students,IdentificationNo',
                'Country' => 'nullable|legal_characters',
                'DateOfBirth' => 'nullable|date_format:Y-m-d',
                'RegisteredOn' => 'nullable|date_format:Y-m-d',
            ];
        }

        public function getUpdateValidationRules() : array
        {
            $storeRules = $this->getStoreValidationRules();
            $storeRules[self::ID_KEY] = 'required|integer';
            return $storeRules;
        }

        public function getSearchValidationRules() : array
        {
            return [
                self::SEARCH_FIELD_KEY => 'required|legal_characters',
                self::SEARCH_VALUE_KEY => 'required|legal_characters',
            ];
        }

        public function getStudents($id) : Collection
        {
            $result = [];
            if(is_numeric($id)) {
                $result[] = Student::find($id);
                return collect($result);
            }

            return Student::all();
        }

        public function storeStudent($data) : array
        {
            $student = App::make(Student::class);
            $result = $this->saveStudentData($student, $data);
            if($result['status'] == 'success') {
                RecordCreated::dispatch($student, $data);
            }

            return $result;
        }

        public function updateStudent($data)
        {
            if(is_array($data) && array_key_exists(self::ID_KEY, $data) && is_numeric($data[self::ID_KEY])) {
                $student = Student::find($data[self::ID_KEY]);
                if(!$student) {
                    return $this->setErrorResult(['Student not found']);
                }
                $originalData = $student->toArray();
                $result = $this->saveStudentData($student, $data);
                if($result['status'] == 'success') {
                    RecordUpdated::dispatch($student, $originalData, $data);
                }

            } else {
                $result = $this->setErrorResult(['Id not provided']);
            }

            return $result;
        }

        private function saveStudentData($student, $data)
        {
            $student->fill($data);
            try {
                $student->save();
                $result = $this->setSuccessResult($student);
            } catch (\Exception $e) {
                $result = $this->setErrorResult([$e->getMessage()]);
            }

            return $result;
        }

        public function setErrorResult($errors, $message = null, $data = null)
        {
            return [
                'status' => 'error',
                'errors' => $errors,
                'message' => $message,
                'data' => $data,
            ];
        }

        public function setSuccessResult($data)
        {
            return [
                'status' => 'success',
                'data' => $data,
            ];
        }

        public function searchStudent($data) : array
        {
            if(
                is_array($data) && array_key_exists(self::SEARCH_FIELD_KEY, $data) && !empty($data[self::SEARCH_FIELD_KEY]) &&
                array_key_exists(self::SEARCH_VALUE_KEY, $data) && !empty($data[self::SEARCH_VALUE_KEY])
            ) {
                $students = Student::query()->where($data[self::SEARCH_FIELD_KEY], 'LIKE', '%'.$data[self::SEARCH_VALUE_KEY].'%')->get();
                $result = $this->setSuccessResult($students);
            } else {
                $result = $this->setErrorResult(['Please provide a search field and a search value']);
            }

            return $result;
        }
    }
