<?php

    namespace App\Http\Controllers;

    use App\Services\StudentServices;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class StudentController extends Controller
    {
        protected $service;

        public function __construct(StudentServices $service)
        {
            $this->service = $service;
        }

        public function view($id = null)
        {
            $data = $this->service->getStudents($id);
            return response()->json($this->service->setSuccessResult($data));

        }

        public function store(Request $request)
        {
            $data = $request->all();
            $validator = Validator::make($data, $this->service->getStoreValidationRules());
            if ($validator->fails()) {
                return response()->json($this->service->setErrorResult($validator->errors(), 'Failed to pass validation'), 400);
            }

            $result = $this->service->storeStudent($data);
            $httpStatusCode = $result['status'] == 'success' ? 200 : 400;
            return response()->json($result, $httpStatusCode);
        }

        public function update(Request $request)
        {
            $data = $request->all();
            $validator = Validator::make($data, $this->service->getUpdateValidationRules());
            if ($validator->fails()) {
                return response()->json($this->service->setErrorResult($validator->errors(), 'Failed to pass validation'), 400);
            }

            $result = $this->service->updateStudent($data);
            $httpStatusCode = $result['status'] == 'success' ? 200 : 400;
            return response()->json($result, $httpStatusCode);
        }

        public function search(Request $request)
        {
            $data = $request->all();
            $validator = Validator::make($data, $this->service->getSearchValidationRules());
            if ($validator->fails()) {
                return response()->json($this->service->setErrorResult($validator->errors(), 'Failed to pass validation'), 400);
            }

            $result = $this->service->searchStudent($data);
            $httpStatusCode = $result['status'] == 'success' ? 200 : 400;
            return response()->json($result, $httpStatusCode);
        }
    }
