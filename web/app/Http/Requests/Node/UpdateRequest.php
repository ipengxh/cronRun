<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Node,
App\Models\NodePermission;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $node = Node::findOrFail($this->id);
        return NodePermission::whereNodeId($node->id)
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
            'name' => 'required|unique:nodes,name,'.$this->id
        ];
    }
}
