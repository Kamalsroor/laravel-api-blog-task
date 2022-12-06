<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'body' =>  ['required', 'string', 'max:1500'],
            'pinned' => ['required' , 'boolean'],
            'tags' => ['required', 'array' , 'exists:tags,id'],
        ];
        switch($this->method())
        {
            case 'POST':
            {
                $rules['cover'] = ['required' ,'file' ,'mimes:png,jpg'];
                break;
            }
            case 'PUT':{
                $rules['cover'] = ['nullable' ,'file' ,'mimes:png,jpg'];
                break;
            }
            default:break;
        }

        return  $rules;
    }
}
