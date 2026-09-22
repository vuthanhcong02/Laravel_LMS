<?php

namespace App\Http\Requests\Api;

use App\Services\TtsService;
use Illuminate\Foundation\Http\FormRequest;

class SynthesizeTtsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'text'  => ['required', 'string', 'max:300'],
            'voice' => ['nullable', 'string', 'max:50'],
            'rate'  => ['nullable', 'string', 'max:10'],
        ];
    }

    /**
     * Get the validated text string.
     */
    public function getText(): string
    {
        return (string) $this->validated('text');
    }

    /**
     * Get the speech voice with fallback to default.
     */
    public function getVoice(): string
    {
        return $this->validated('voice') ?? TtsService::DEFAULT_VOICE;
    }

    /**
     * Get the speech rate with fallback to default.
     */
    public function getRate(): string
    {
        return $this->validated('rate') ?? '-5%';
    }
}
