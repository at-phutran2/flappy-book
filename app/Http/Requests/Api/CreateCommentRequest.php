<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Model\Post;
use App\Model\Book;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'commentable_id'   => 'required|integer',
            'commentable_type' => 'required|in:'.Post::COMMENTABLE_TYPE.','
                                                .Book::COMMENTABLE_TYPE,
            'parent_id'        => 'integer|exists:comments,id',
            'comment'          => 'required',
        ];

        return $rules;
    }
}
