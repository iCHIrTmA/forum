<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleRequestsException;
use App\Reply;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }

    public function failedAuthorization()
    {
        throw new ThrottleRequestsException('You are replying too frequently');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', new Spamfree],
        ];
    }
}
