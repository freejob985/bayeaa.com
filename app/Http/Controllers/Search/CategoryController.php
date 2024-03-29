<?php


namespace App\Http\Controllers\Search;

use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Session;
class CategoryController extends BaseController
{
	public $isCatSearch = true;
	
	/**
	 * Category URL
	 * Pattern: (countryCode/)category/category-slug
	 * Pattern: (countryCode/)category/parent-category-slug/category-slug
	 *
	 * @param $countryCode
	 * @param $catSlug
	 * @param null $subCatSlug
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function index($countryCode, $catSlug, $subCatSlug = null)
	{
	   // dd(Session::get('post'));
		// Check if Category is found
		if (empty($this->cat)) {
			abort(404, t('category_not_found'));
		}
		
		// Search
		$search = new $this->searchClass($this->preSearch);
		$data = $search->fetch();
		
		// Get Titles
		$bcTab = $this->getBreadcrumb();
		$htmlTitle = $this->getHtmlTitle();
		view()->share('bcTab', $bcTab);
		view()->share('htmlTitle', $htmlTitle);
		
		// SEO
		$title = $this->getTitle();
		if (isset($this->cat->description) && !empty($this->cat->description)) {
			$description = Str::limit($this->cat->description, 200);
		} else {
			$description = Str::limit(t('free_ads_category_in_location', [
					'category' => $this->cat->name,
					'location' => config('country.name'),
				]) . '. ' . t('looking_for_product_or_service') . ' - ' . config('country.name'), 200);
		}
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		$data['post']=Session::get('post');
		// Open Graph
		$this->og->title($title)->description($description)->type('website');

		view()->share('og', $this->og);
	
		return appView('search.results', $data);
	}
}
