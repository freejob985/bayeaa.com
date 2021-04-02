<?php


namespace App\Http\Controllers;

use Torann\LaravelMetaTags\Facades\MetaTag;

class CountriesController extends FrontController
{
	/**
	 * CountriesController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'countries'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'countries')));
		MetaTag::set('keywords', getMetaTag('keywords', 'countries'));
		
		return appView('countries', $data);
	}
}
