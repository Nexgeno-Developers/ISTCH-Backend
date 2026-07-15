<?php

namespace App\Http\Controllers;

use App\Helpers\AdminMailHelper;
use App\Mail\FormSubmissionMail;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function submit(Request $request)
    {
        $formName = $request->input('form_name');

        $validationRules = $this->getValidationRules($formName);
        $validatedData = $request->validate($validationRules);
        $formData = collect($validatedData)->except(['form_name', 'name', 'email', 'phone'])->toArray();

        $companyId = $request->input('company_id') ?? 1;

        $form = Form::create([
            'form_name' => $formName,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'form_data' => $formData,
            'ip' => request()->ip(),
            'company_id' => $companyId,
        ]);

        AdminMailHelper::send(new FormSubmissionMail($formName, $validatedData), $companyId);

        return redirect()->back()->with('success', 'Enquiry submitted successfully');
    }

    private function getValidationRules($formName)
    {
        switch ($formName) {
            case 'contact':
                return [
                    'form_name' => 'required|max:20',
                    'name' => 'required|string|max:50',
                    'company' => 'required|string|max:70',
                    'phone' => 'nullable|digits_between:10,15|max:50',
                    'email' => 'required|email|max:50',
                    'subject' => 'nullable|string|max:100',
                    'message' => 'nullable|string|max:150',
                ];

            case 'enrolments':
                return [
                    'form_name' => 'required|max:20',
                    'name' => 'required|string|max:50',
                    'phone' => 'digits_between:10,15',
                    'email' => 'required|email|max:50',
                    'course' => 'nullable|string|max:150',
                    'course_category' => 'nullable|string|max:150',
                ];

            default:
                return [
                    'form_name' => 'required|max:20',
                ];
        }
    }
}
