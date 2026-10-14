<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class GradeSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_TEACHER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'score'                          => 'required|numeric|min:0|max:10',
            'teacher_feedback'               => 'nullable|string|max:2000',
            'audio_feedback'                 => 'nullable|file|mimes:webm,mp3,wav,ogg,m4a,weba,aac|max:20480',
            'delete_audio'                   => 'nullable|boolean',
            'teacher_attachments'            => 'nullable|array|max:5',
            'teacher_attachments.*'          => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip,rar,webm,mp3,wav,ogg,m4a,weba|max:20480',
            'existing_teacher_attachments'   => 'nullable|array',
            'existing_teacher_attachments.*' => 'nullable|string',
        ];
    }

    /**
     * Get paths of existing teacher attachments that should be retained.
     * Validate path phải nằm trong thư mục assignments/ và không chứa path traversal.
     *
     * @return string[]
     */
    public function getRetainedTeacherAttachmentPaths(): array
    {
        $paths = $this->input('existing_teacher_attachments', []);
        if (!is_array($paths)) {
            return [];
        }

        return array_values(array_filter($paths, function ($p) {
            if (!is_string($p) || empty(trim($p))) {
                return false;
            }
            // ✅ Bảo vệ path traversal: chỉ cho phép path trong assignments/
            if (!str_starts_with($p, 'assignments/')) {
                return false;
            }
            // ✅ Không cho phép '..' để tránh directory traversal
            if (str_contains($p, '..')) {
                return false;
            }
            // ✅ Không cho phép ký tự null byte
            if (str_contains($p, "\0")) {
                return false;
            }
            return true;
        }));
    }

    /**
     * Get valid uploaded teacher attachments.
     *
     * @return UploadedFile[]
     */
    public function getValidTeacherAttachments(): array
    {
        $files = [];
        if ($this->hasFile('teacher_attachments')) {
            $raw = $this->file('teacher_attachments');
            if (is_array($raw)) {
                foreach ($raw as $f) {
                    if ($f instanceof UploadedFile && $f->isValid()) {
                        $files[] = $f;
                    }
                }
            } elseif ($raw instanceof UploadedFile && $raw->isValid()) {
                $files[] = $raw;
            }
        }
        return $files;
    }
}
