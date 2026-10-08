<?php

namespace App\Http\Requests\Teacher;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LessonRequest extends FormRequest
{
    /**
     * Determine if the teacher is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === User::ROLE_TEACHER;
    }

    /**
     * Validation rules for lesson creation/update.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'record_url'       => ['nullable', 'url', 'max:500'],
            'video_url'        => ['nullable', 'url', 'max:500'],
            'pdf_file'         => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'note_file'        => ['nullable', 'file', 'mimes:pdf,doc,docx,txt,zip,rar,ppt,pptx,xlsx,xls', 'max:20480'],
            'note_content'     => ['nullable', 'string', 'max:50000'],
            'remove_pdf'       => ['nullable', 'boolean'],
            'remove_note_file' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'     => __('Vui lòng nhập tiêu đề bài học.'),
            'title.max'          => __('Tiêu đề bài học không được vượt quá 255 ký tự.'),
            'description.max'    => __('Mô tả bài học không được vượt quá 2000 ký tự.'),
            'record_url.url'     => __('Đường dẫn link Record bài giảng không đúng định dạng URL.'),
            'record_url.max'     => __('Đường dẫn link Record không được vượt quá 500 ký tự.'),
            'video_url.url'      => __('Đường dẫn video bài giảng không đúng định dạng URL.'),
            'video_url.max'      => __('Đường dẫn video không được vượt quá 500 ký tự.'),
            'pdf_file.file'      => __('File tài liệu phải là một tệp hợp lệ.'),
            'pdf_file.mimes'     => __('File tài liệu bài giảng bắt buộc phải có định dạng PDF (.pdf).'),
            'pdf_file.max'       => __('Dung lượng file PDF không được vượt quá 20MB.'),
            'note_file.file'     => __('File ghi chú đính kèm phải là một tệp hợp lệ.'),
            'note_file.mimes'    => __('File ghi chú đính kèm chỉ chấp nhận các định dạng: pdf, doc, docx, txt, zip, rar, ppt, pptx, xlsx, xls.'),
            'note_file.max'      => __('Dung lượng file ghi chú không được vượt quá 20MB.'),
            'note_content.max'   => __('Nội dung ghi chú không được vượt quá 50.000 ký tự.'),
        ];
    }
}
