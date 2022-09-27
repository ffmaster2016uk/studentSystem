<?php

    namespace Tests\Feature;

    use App\Models\Student;
    use Illuminate\Foundation\Testing\DatabaseTransactions;
    use Tests\TestCase;

    class StudentsTest extends TestCase
    {
        use DatabaseTransactions;

        public function testViewStudents()
        {
            $students = Student::factory(5)->create();
            $result = $this->get(route('students-view'));
            $result->assertStatus(200);
            foreach($students as $student) {
                $result->assertSee($student->IdentificationNo);
                $this->get(route('students-view', $student->id))->assertStatus(200)->assertSee($student->IdentificationNo);
            }
        }

        public function testStoreStudent()
        {
            $data = Student::factory()->raw();
            $this->put(route('students-store'), $data)->assertStatus(200)->assertSee($data['IdentificationNo']);
        }

        public function testStoreStudentValidationFail()
        {
            $data = Student::factory()->raw();
            unset($data['Name']);
            $this->put(route('students-store'), $data)->assertStatus(400)->assertSee('Failed to pass validation');
        }

        public function testStoreStudentExceptionHandling()
        {
            $data = Student::factory()->raw();
            $data['randomField'] = 1;
            $this->put(route('students-store'), $data)->assertStatus(400);
        }

        public function testUpdateStudent()
        {
            $student = Student::factory()->create();
            $newData = Student::factory()->raw();
            $newData['Id'] = $student->id;
            $this->patch(route('students-update'), $newData)->assertStatus(200)->assertSee($newData['IdentificationNo']);
        }

        public function testUpdateStudentValidationFail()
        {
            $student = Student::factory()->create();
            $newData = Student::factory()->raw();
            $newData['Id'] = $student->id;
            unset($newData['Name']);
            $this->patch(route('students-update'), $newData)->assertStatus(400)->assertSee('Failed to pass validation');
        }

        public function testUpdateStudentExceptionHandling()
        {
            $student = Student::factory()->create();
            $newData = Student::factory()->raw();
            $newData['Id'] = $student->id;
            $newData['randomField'] = 1;
            $this->patch(route('students-update'), $newData)->assertStatus(400);
        }

        public function testStudentSearch()
        {
            $students = Student::factory(5)->create();
            $lastStudent = $students->last();
            $data = [
                'searchField' => 'IdentificationNo',
                'searchValue' => $lastStudent->IdentificationNo
            ];
            $this->post(route('students-search'), $data)->assertStatus(200)->assertSee($lastStudent->Name);
        }

        public function testStudentSearchValidationFail()
        {
            $data = [
                'searchField' => 'IdentificationNo',
            ];
            $this->post(route('students-search'), $data)->assertStatus(400)->assertSee('Failed to pass validation');
        }
    }
