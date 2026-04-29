<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\FormSubmissionMail;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormSubmissionController extends Controller
{
    /**
     * Submit a form via API.
     *
     * Expected:
     * - `form_name`
     * - `name`, `email`, `phone` (depending on form_name rules)
     * - Any other validated scalar fields as per `getValidationRules()`
     * - Uploaded files as top-level multipart form-data fields (e.g. `image`, `resume`, `pdf`, etc.)
     *
     * Uploaded file paths will be stored inside `forms.form_data` under the same field name.
     */
    public function submit(Request $request)
    {
        $formName = $request->input('form_name');
        if (!$formName) {
            return response()->json([
                'error' => [
                    'message' => 'form_name is required',
                    'code' => 'FORM_NAME_REQUIRED',
                ],
            ], 422);
        }

        $validationRules = $this->getValidationRules($formName);
        $validatedData = $request->validate($validationRules);



        $companyId = $request->input('company_id') ?? 1;

        // Keep parity with your web controller: store only validated scalar fields
        // (excluding name/email/phone/form_name/company_id).
        $formData = collect($validatedData)
            ->except(['form_name', 'name', 'email', 'phone'])
            ->toArray();

        // Add uploaded files (multipart) into form_data under their input field name.
        // We store a storage-relative public path (prefixed with `storage/`)
        // so the backend can render it using `my_asset()`.
        $files = $request->allFiles();
        foreach ($files as $field => $fileValue) {
            $stored = $this->storeFileValue($fileValue, $formName, (string) $companyId);
            if ($stored === null) {
                continue;
            }

            $formData[$field] = $stored;
        }

        $name = $request->input('name');

        if (empty($name)) {
            $first = $request->input('first_name') ?? '';
            $last  = $request->input('last_name') ?? '';
            $name = trim($first . ' ' . $last);
        }        

        $form = Form::create([
            'form_name' => $formName,
            'name' => $name,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'form_data' => $formData,
            'ip' => $request->ip(),
            'company_id' => $companyId,
        ]);

        //Keep the same behavior as the web flow (send email to your configured recipient).
        $recipientEmail = [config('custom.from_email')];
        try {
            Mail::to($recipientEmail)->send(new FormSubmissionMail($formName, array_merge($validatedData, $formData)));
        } catch (\Throwable $e) {
            // Avoid failing the submission if email fails.
            logger('Form submission mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'data' => [
                'id' => $form->id,
                'form_name' => $form->form_name,
                'created_at' => $form->created_at,
                'form_data' => $form->form_data,
            ],
        ], 201);
    }

    /**
     * Store either:
     * - a single UploadedFile
     * - an array of UploadedFile
     *
     * Returns:
     * - string path for a single file
     * - string[] for multiple files
     * - null if the provided value isn't a valid UploadedFile (ignored)
     */
    private function storeFileValue(mixed $fileValue, string $formName, string $companyId): array|string|null
    {
        if ($fileValue instanceof UploadedFile) {
            return $this->storeOneFile($fileValue, $formName, $companyId);
        }

        if (is_array($fileValue)) {
            $paths = [];
            foreach ($fileValue as $maybeFile) {
                if ($maybeFile instanceof UploadedFile) {
                    $paths[] = $this->storeOneFile($maybeFile, $formName, $companyId);
                }
            }

            if (count($paths) === 0) {
                return null;
            }

            return count($paths) === 1 ? $paths[0] : $paths;
        }

        return null;
    }

    private function storeOneFile(UploadedFile $file, string $formName, string $companyId): string
    {
        // Match the intent of ProtectForms middleware.
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
        ];

        $mimeType = (string) $file->getMimeType();
        if (!in_array($mimeType, $allowedMimes, true)) {
            abort(422, 'Disallowed file type');
        }

        $maxSizeBytes = 10 * 1024 * 1024; // 10MB (keeps things reasonable for forms)
        if ($file->getSize() > $maxSizeBytes) {
            abort(422, 'File too large');
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $date = date('Y/m');

        // Store on the `public` disk and return the storage-relative public path.
        $path = $file->storeAs(
            'uploads/forms/' . $formName . '/' . $companyId . '/' . $date,
            Str::random(20) . '.' . $extension,
            'public'
        );

        // The stored value is designed to be compatible with `my_asset($value)`.
        return 'storage/' . $path;
    }

    private function getValidationRules(string $formName): array
    {
        switch ($formName) {
            case 'subscription':
                return [
                    'form_name' => 'required|max:20',
                    'email' => 'required|email|max:50',
                ];

            case 'get_in_touch':
                return [
                    'form_name' => 'required|max:20',
                    'name' => 'required|string|max:50',
                    'email' => 'required|email|max:50',
                ];

            case 'message':
                return [
                    'form_name' => 'required|max:20',
                    'name' => 'nullable|string|max:50',
                    'first_name' => 'required|string|max:50',
                    'last_name' => 'required|string|max:50',
                    'phone' => 'nullable|digits_between:10,15|max:50',
                    'email' => 'required|email|max:50',
                    'message' => 'nullable|string|max:200',
                ]; 
                
            case 'contact':
                return [
                    'form_name' => 'required|max:20',
                    'name' => 'nullable|string|max:50',
                    'first_name' => 'required|string|max:50',
                    'last_name' => 'required|string|max:50',
                    'email' => 'required|email|max:50',
                    'phone' => 'nullable|digits_between:10,15|max:50',
                    'company_name' => 'required|string|max:50',
                    'company_url' => 'nullable|string|max:255',
                    'job_function'=> 'required|string|max:50',
                    'job_title'   => 'required|string|max:50',
                    'country'     => 'required|string|max:50',
                    'interests' => 'required|string|max:200',
                    'interested_products' => 'nullable|string|max:200|required_if:interests,Products & Services',
                    'interested_marketing_support_service' => 'nullable|string|max:200|required_if:interests,Products & Services',
                    'interested_technical_support_service' => 'nullable|string|max:200|required_if:interests,Products & Services',
                    'message' => 'nullable|string|max:200',
                ];                  

            default:
                return [
                    'form_name' => 'required|max:20',
                ];
        }
    }
}