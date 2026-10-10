<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class SubmitAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_STUDENT;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,zip,rar,webm,mp3,wav,ogg,m4a,weba|max:20480',
            'audio_file'    => 'nullable|file|mimes:webm,mp3,wav,ogg,m4a,weba|max:20480',
        ];
    }

    /**
     * Custom validation after rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $files = $this->getValidFiles();
            if (empty($files)) {
                $validator->errors()->add('attachments', __('Vui lòng ghi âm giọng nói hoặc chọn ít nhất một tệp tin bài làm.'));
            }
        });
    }

    /**
     * Get all valid files from attachments and audio_file inputs.
     *
     * @return UploadedFile[]
     */
    public function getValidFiles(): array
    {
        $files = [];

        if ($this->hasFile('attachments')) {
            $rawAttachments = $this->file('attachments');
            if (is_array($rawAttachments)) {
                foreach ($rawAttachments as $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $files[] = $file;
                    }
                }
            } elseif ($rawAttachments instanceof UploadedFile && $rawAttachments->isValid()) {
                $files[] = $rawAttachments;
            }
        }

        if ($this->hasFile('audio_file')) {
            $audio = $this->file('audio_file');
            if ($audio instanceof UploadedFile && $audio->isValid()) {
                $files[] = $audio;
            }
        }

        return $files;
    }
}
