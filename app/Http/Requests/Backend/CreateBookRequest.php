<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Book;

class CreateBookRequest extends FormRequest
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
        return [
            'title'       => 'required',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'unit'        => 'in:' . Book::TYPE_VND . ','
                                   . Book::TYPE_DOLAR . ','
                                   . Book::TYPE_EURO . ','
                                   . Book::TYPE_YEN . ',',
            'from_person' => 'required|max:10|exists:users,employ_code',
            'description' => 'required',
            'year'        => 'required|integer|date_format:"Y"|max:' . date('Y'),
            'author'      => 'required|max:100',
            'picture'     => 'image|mimes:png,jpg,jpeg|dimensions:min_width=100,min_height=200',
            'language_id' => 'required|exists:languages,id',
            'page_number' => 'numeric|max:2000',
        ];
    }
}
