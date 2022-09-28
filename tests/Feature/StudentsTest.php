<?php

    namespace Tests\Feature;

    use App\Models\Student;
    use App\Models\User;
    use Illuminate\Foundation\Testing\DatabaseTransactions;
    use Laravel\Sanctum\Sanctum;
    use Tests\TestCase;

    class StudentsTest extends TestCase
    {
        use DatabaseTransactions;

        public function testViewStudents()
        {
            $this->actAsUser();
            $students = Student::factory(5)->create();
            $result = $this->get(route('students-view'));
            $result->assertStatus(200);
            foreach($students as $student) {
                $result->assertSee($student->IdentificationNo);
                $this->get(route('students-view', $student->Id))->assertStatus(200)->assertSee($student->IdentificationNo);
            }
        }

        public function testStoreStudent()
        {
            $this->actAsUser();
            $data = Student::factory()->raw();
            $this->put(route('students-store'), $data)->assertStatus(200)->assertSee($data['IdentificationNo']);
            $this->assertDatabaseHas('activity_logs', [
                'loggable_type' => Student::class,
                'type' => 'created',
            ]);
        }

        public function testStoreStudentValidationFail()
        {
            $this->actAsUser();
            $data = Student::factory()->raw();
            unset($data['Name']);
            $this->put(route('students-store'), $data)->assertStatus(400)->assertSee('Failed to pass validation');
        }

        public function testStoreStudentExceptionHandling()
        {
            $this->actAsUser();
            $data = Student::factory()->raw();
            $data['randomField'] = 1;
            $this->put(route('students-store'), $data)->assertStatus(400);
        }

        public function testUpdateStudent()
        {
            $this->actAsUser();
            $student = Student::factory()->create();
            $newData = Student::factory()->raw();
            $newData['Id'] = $student->Id;
            $this->patch(route('students-update'), $newData)->assertStatus(200)->assertSee($newData['IdentificationNo']);
            $this->assertDatabaseHas('activity_logs', [
                'loggable_id' => $student->Id,
                'loggable_type' => Student::class,
                'type' => 'updated',
            ]);
        }

        public function testUpdateStudentValidationFail()
        {
            $this->actAsUser();
            $student = Student::factory()->create();
            $newData = Student::factory()->raw();
            $newData['Id'] = $student->Id;
            unset($newData['Name']);
            $this->patch(route('students-update'), $newData)->assertStatus(400)->assertSee('Failed to pass validation');
        }

        public function testUpdateStudentExceptionHandling()
        {
            $this->actAsUser();
            $student = Student::factory()->create();
            $newData = Student::factory()->raw();
            $newData['Id'] = $student->Id;
            $newData['randomField'] = 1;
            $this->patch(route('students-update'), $newData)->assertStatus(400);
        }

        public function testStudentSearch()
        {
            $this->actAsUser();
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
            $this->actAsUser();
            $data = [
                'searchField' => 'IdentificationNo',
            ];
            $this->post(route('students-search'), $data)->assertStatus(400)->assertSee('Failed to pass validation');
        }

        private function actAsUser()
        {
            Sanctum::actingAs(
                User::factory()->create()
            );
        }
    }
