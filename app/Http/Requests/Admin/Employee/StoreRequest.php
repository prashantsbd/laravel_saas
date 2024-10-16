<?php

namespace App\Http\Requests\Admin\Employee;

use App\Http\Requests\CoreRequest;
use App\Traits\CustomFieldsRequestTrait;

class StoreRequest extends CoreRequest
{
    use CustomFieldsRequestTrait;

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
        \Illuminate\Support\Facades\Validator::extend('check_superadmin', function ($attribute, $value, $parameters, $validator) {
            return !\App\Models\User::withoutGlobalScopes([\App\Scopes\ActiveScope::class, \App\Scopes\CompanyScope::class])
                ->where('email', $value)
                ->where('is_superadmin', 1)
                ->exists();
        });

        $setting = company();
        $rules = [
            'employee_id' => 'required|unique:employee_details,employee_id,null,id,company_id,' . company()->id.'|max:100',
            'name' => 'required|max:50',
            'email' => 'required|email:rfc,strict|unique:users,email,null,id,company_id,' . company()->id.'|max:100|check_superadmin',
            'slack_username' => 'nullable|unique:employee_details,slack_username,null,id,company_id,' . company()->id.'|max:30',
            'hourly_rate' => 'nullable|numeric',
            'daily_hrs_cap' => 'nullable|numeric|min:0|max:8',
            'weekly_hrs_cap' => 'nullable|numeric|min:0|max:50',
            'joining_date' => 'required',
            'last_date' => 'nullable|date_format:"' . $setting->date_format . '"|after_or_equal:joining_date',
            'date_of_birth' => 'nullable|date_format:"' . $setting->date_format . '"|before_or_equal:'.now($setting->timezone)->toDateString(),
            'department' => 'required',
            'designation' => 'required',
            'company_address' => 'required',
            'probation_end_date' => 'nullable|date_format:"' . $setting->date_format . '"|after_or_equal:joining_date',
            'notice_period_start_date' => 'nullable|required_with:notice_period_end_date|date_format:"' . $setting->date_format . '"',
            'notice_period_end_date' => 'nullable|required_with:notice_period_start_date|date_format:"' . $setting->date_format . '"|after_or_equal:notice_period_start_date',
            'internship_end_date' => 'nullable|date_format:"' . $setting->date_format . '"|after_or_equal:joining_date',
            'contract_end_date' => 'nullable|date_format:"' . $setting->date_format . '"|after_or_equal:joining_date',
        ];

        if (request()->telegram_user_id) {
            $rules['telegram_user_id'] = 'nullable|unique:users,telegram_user_id,null,id,company_id,' . company()->id;
        }

        if((request()->daily_hrs_cap <= 8) && (request()->weekly_hrs_cap <= 50)){
            $rules['daily_hrs_cap'] = 'nullable|numeric|min:0|max:"'. request()->weekly_hrs_cap .'"'; 
            $rules['weekly_hrs_cap'] = 'nullable|numeric|min:"'. request()->daily_hrs_cap .'"|max:50'; 
        }

        $rules = $this->customFieldRules($rules);

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        $attributes = $this->customFieldsAttributes($attributes);

        return $attributes;
    }

    public function messages()
    {
        return [
            'email.check_superadmin' => __('superadmin.emailAlreadyExist'),
        ];
    }

}
