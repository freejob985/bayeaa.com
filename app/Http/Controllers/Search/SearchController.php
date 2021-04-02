<?php


namespace App\Http\Controllers\Search;

use Torann\LaravelMetaTags\Facades\MetaTag;

class SearchController extends BaseController
{
	public $isIndexSearch = true;
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
	    
		view()->share('isIndexSearch', $this->isIndexSearch);
		
		// Search
		$search = new $this->searchClass($this->preSearch);
		$data = $search->fetch();
		
		// Export Search Result
		view()->share('count', $data['count']);
		view()->share('paginator', $data['paginator']);
		
		// Get Titles
		$title = $this->getTitle();
		$this->getBreadcrumb();
		$this->getHtmlTitle();
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $title);
		
		return appView('search.results');
	}
}
