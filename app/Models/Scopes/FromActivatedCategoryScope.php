<?php


namespace App\Models\Scopes;

use App\Models\Category;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FromActivatedCategoryScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
	 * @param Builder $builder
	 * @param Model $model
	 * @return $this|Builder
	 */
    public function apply(Builder $builder, Model $model)
    {
        if (request()->segment(1) == admin_uri()) {
            return $builder;
        }

        // Get all active categories
        $categories = Category::all();
        if (!empty($categories)) {
            $categories = collect($categories)->keyBy('id')->keys()->toArray();
            return $builder->whereIn('category_id', $categories);
        }

        return $builder;
    }
}
