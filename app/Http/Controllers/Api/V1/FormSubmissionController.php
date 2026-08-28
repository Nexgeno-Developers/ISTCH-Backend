<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\AdminMailHelper;
use App\Http\Controllers\Controller;
use App\Mail\FormSubmissionMail;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FormSubmissionController extends Controller
{
    public function submit(Request $request)
    {
        $formName = $request->input('form_name');
        if (! $formName) {
            return response()->json([
                'error' => [
                    'message' => 'form_name is required',
                    'code' => 'FORM_NAME_REQUIRED',
                ],
            ], 422);
        }

        $validationRules = $this->getValidationRules($formName);
        $validatedData = $request->validate($validationRules);

        $companyId = $validatedData['company_id'] ?? config('custom.company_id') ?? 1;

        $formData = collect($validatedData)
            ->except(['form_name', 'name', 'email', 'phone', 'company_id', 'recaptcha_token', 'recaptcha_action'])
            ->toArray();

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
            $full = $request->input('full_name');
            if (! empty($full)) {
                $name = $full;
            } else {
                $first = $request->input('first_name') ?? '';
                $last = $request->input('last_name') ?? '';
                $name = trim("{$first} {$last}");
            }
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

        AdminMailHelper::send(new FormSubmissionMail($formName, [
            'name' => $form->name,
            'email' => $form->email,
            'phone' => $form->phone,
            ...$form->form_data,
        ]), $companyId, $formName);

        return response()->json([
            'data' => [
                'id' => $form->id,
                'form_name' => $form->form_name,
                'status' => 'submitted',
                'created_at' => optional($form->created_at)->toIso8601String(),
            ],
        ], 201);
    }

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
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
        ];

        $mimeType = (string) $file->getMimeType();
        if (! in_array($mimeType, $allowedMimes, true)) {
            abort(422, 'Disallowed file type');
        }

        $maxSizeBytes = 10 * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            abort(422, 'File too large');
        }

        $extension = $this->extensionFromMime($mimeType);
        $date = date('Y/m');

        $path = $file->storeAs(
            'uploads/forms/'.$formName.'/'.$companyId.'/'.$date,
            Str::random(20).'.'.$extension,
            'public'
        );

        return 'storage/'.$path;
    }

    private function extensionFromMime(string $mimeType): string
    {
        return match (strtolower($mimeType)) {
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            default => 'bin',
        };
    }

    private function getValidationRules(string $formName): array
    {
        switch ($formName) {
            case 'volunteers_application':
                return [
                    'form_name' => 'required|max:50',
                    'company_id' => 'nullable|integer|exists:companies,id',
                    'name' => 'required_without:full_name|nullable|string|max:50',
                    'full_name' => 'required_without:name|nullable|string|max:50',
                    'email' => 'required|email|max:50',
                    'phone' => 'nullable|string|max:20',
                    'age' => 'required|integer|min:1|max:120',
                    'country' => 'required|string|max:50',
                    'previous_experience' => 'nullable|string|max:500',
                    'availability' => 'required|string|max:100',
                    'recaptcha_token' => 'required|string',
                    'recaptcha_action' => 'required|string|max:100',
                ];

            case 'contact':
                return [
                    'form_name' => 'required|max:50',
                    'company_id' => 'nullable|integer|exists:companies,id',
                    'name' => 'required_without:full_name|nullable|string|max:50',
                    'full_name' => 'required_without:name|nullable|string|max:50',
                    'email' => 'required|email|max:50',
                    'phone' => 'nullable|string|max:20',
                    'country' => 'required|string|max:100',
                    'nature_of_inquiry' => 'required|string|max:100',
                    'message' => 'required|string|max:1000',
                    'recaptcha_token' => 'required|string',
                    'recaptcha_action' => 'required|string|max:100',
                ];

            default:
                throw ValidationException::withMessages([
                    'form_name' => ['Unsupported form_name.'],
                ]);
        }
    }
}

