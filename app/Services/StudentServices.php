<?php

    namespace App\Services;

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
                'IdentificationNo' => 'required|legal_characters',
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
            $result = $this->getDefaultResultStructure();
            $student = App::make(Student::class);
            $result = $this->saveStudentData($student, $data, $result);

            return $result;
        }

        public function updateStudent($data)
        {
            $result = $this->getDefaultResultStructure();
            if(is_array($data) && array_key_exists(self::ID_KEY, $data) && is_numeric($data[self::ID_KEY])) {
                $student = Student::find($data[self::ID_KEY]);
                if(!$student) {
                    $result['status'] = 'error';
                    $result['errors'] = [
                        'Student not found'
                    ];
                }
                $result = $this->saveStudentData($student, $data, $result);
            } else {
                $result['status'] = 'error';
                $result['errors'] = [
                    'Id not provided'
                ];
            }

            return $result;
        }

        private function getDefaultResultStructure()
        {
            return [
                'status' => '',
                'errors' => null,
                'data' => null,
            ];
        }

        private function saveStudentData($student, $data, $result)
        {
            $student->fill($data);
            try {
                $student->save();
                $result['status'] = 'success';
                $result['data'] = $student;
            } catch (\Exception $e) {
                $result['status'] = 'error';
                $result['errors'] = [
                    $e->getMessage()
                ];
            }

            return $result;
        }

        public function searchStudent($data) : array
        {
            $result = $this->getDefaultResultStructure();
            if(
                is_array($data) && array_key_exists(self::SEARCH_FIELD_KEY, $data) && !empty($data[self::SEARCH_FIELD_KEY]) &&
                array_key_exists(self::SEARCH_VALUE_KEY, $data) && !empty($data[self::SEARCH_VALUE_KEY])
            ) {
                $students = Student::query()->where($data[self::SEARCH_FIELD_KEY], 'LIKE', '%'.$data[self::SEARCH_VALUE_KEY].'%')->get();
                $result['status'] = 'success';
                $result['data'] = $students;
            } else {
                $result['status'] = 'error';
                $result['errors'] = [
                    'Please provide a search field and a search value'
                ];
            }

            return $result;
        }
    }
