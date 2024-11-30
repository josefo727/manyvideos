<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['video'] = 'required|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:204800';
        } else if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['video'] = 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:204800';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'video.mimetypes' => 'The file must be a valid video with avi, mpeg, quicktime or mp4 extension.',
            'video.max' => 'The video should be no larger than 200MB.',
        ];
    }
}
