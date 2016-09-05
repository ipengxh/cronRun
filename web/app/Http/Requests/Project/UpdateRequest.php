<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Project,
App\Models\ProjectPermission;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = Project::findOrFail($this->id);
        return ProjectPermission::whereProjectId($project->id)
        ->whereUserId(\Auth::user()->id)
        ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:projects,name,'.$this->id
        ];
    }
}
