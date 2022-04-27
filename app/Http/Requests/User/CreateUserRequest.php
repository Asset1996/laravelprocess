<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\checkPinUnique;

class CreateUserRequest extends FormRequest
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
     * Кастомные сообщения об ошибке.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'iin.required' => 'Вы не заполнили ИИН.',
            'name.required' => 'Вы не заполнили Имя.',
            'surname.required' => 'Вы не заполнили Фамилия.',
            'email.required' => 'Вы не заполнили адрес электронной почты.',
            'roles_id.required' => 'Вы не заполнили Роль.',
            'pin_code.required' => 'Вы не заполнили Пин код.',
            'password.required' => 'Вы не заполнили Пароль.',

            'iin.min' => 'Поле ИИН должен состоять как минимум из 12 цифр.',
            'email.min' => 'Поле email должен состоять как минимум из 6 символов.',
            'pin_code.min' => 'Поле пин код должен состоять как минимум из 6 символов.',
            'password.min' => 'Поле пароль должен состоять как минимум из 6 символов.',

            'pin_code.confirmed' => 'Пин коды не совпадают.',
            'password.confirmed' => 'Пароли не совпадают.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'iin' => 'required|unique:user|min:12|max:12|',
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'lastname' => 'max:50',
            'roles_id' => 'integer|required|max:4',
            'positions_id' => 'integer',
            'email' => 'required|unique:user|email|min:6|max:255',
            'pin_code' => ['required', 'confirmed', 'min:6', 'max:6', new checkPinUnique],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }
}
