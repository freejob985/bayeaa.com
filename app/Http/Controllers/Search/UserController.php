<?php


namespace App\Http\Controllers\Search;

use App\Models\User;
use App\Models\CategoryField;
use App\Events\PostWasVisited;
use App\Helpers\ArrayHelper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Post\Traits\CatBreadcrumbTrait;
use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use App\Http\Requests\SendMessageRequest;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Message;
use App\Models\Package;
use App\Http\Controllers\FrontController;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Notifications\SellerContacted;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\Localization\Country as CountryLocalization;
use Session;


class UserController extends BaseController
{
	public $isUserSearch = true;
	public $sUser;
	
	/**
	 * @param $countryCode
	 * @param null $userId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	 
	public function getPostFieldsValues($catId, $postId)
	{
		// Get the Post's Custom Fields by its Parent Category
		$customFields = CategoryField::getFields($catId, $postId);
		
		// Get the Post's Custom Fields that have a value
		$postValue = [];
		if ($customFields->count() > 0) {
			foreach ($customFields as $key => $field) {
				if (!empty($field->default)) {
					$postValue[$key] = $field;
				}
			}
		}
		
		return collect($postValue);
	}	 
	 	private function reorderCatBreadcrumbItemsPositions($array = [])
	{
		if (!is_array($array)) {
			return [];
		}
		
		$countItems = count($array);
		if ($countItems > 0) {
			$tmp = $array;
			$j = $countParents = $countItems - 1;
			for ($i = 0; $i <= $countParents; $i++) {
				if (isset($array[$i]) && $tmp[$j]) {
					$array[$i]['position'] = $tmp[$j]['position'];
					
					// Transform the item from array to collection
					$array[$i] = collect($array[$i]);
				}
				$j--;
			}
			unset($tmp);
			$array = array_reverse($array);
		}
		
		return $array;
	}
	 	private function getUnorderedCatBreadcrumb($cat, &$position = 0, &$tab = [])
	{
		if (empty($cat) || !($cat instanceof Category)) {
			return $tab;
		}
		
		if (empty($tab)) {
			$tab[] = [
				'name'     => $cat->name,
				'url'      => UrlGen::category($cat),
				'position' => $position,
			];
		}
		
		if (isset($cat->parent) && !empty($cat->parent)) {
			$tab[] = [
				'name'     => $cat->parent->name,
				'url'      => UrlGen::category($cat->parent),
				'position' => $position + 1,
			];
			
			if (isset($cat->parent->parent) && !empty($cat->parent->parent)) {
				$position = $position + 1;
				
				return $this->getUnorderedCatBreadcrumb($cat->parent, $position, $tab);
			}
		}
		
		return $tab;
	}
	 	private function getCatBreadcrumb($cat, $position = 0)
	{
		$array = $this->getUnorderedCatBreadcrumb($cat, $position);
		$array = $this->reorderCatBreadcrumbItemsPositions($array);
		
		return $array;
	}
	public function index($countryCode, $userId = null)
	{
//========================================

	$data = [];
		
		// Get and Check the Controller's Method Parameters
		$parameters = request()->route()->parameters();
		
		// Show 404 error if the Post's ID is not numeric
		if (!isset($parameters['id']) || empty($parameters['id']) || !is_numeric($parameters['id'])) {
			abort(404);
		}
		
		// Set the Parameters
		$postId = $parameters['id'];
		if (isset($parameters['slug'])) {
			$slug = $parameters['slug'];
		}
		
		// GET POST'S DETAILS
		if (auth()->check()) {
			// Get post's details even if it's not activated and reviewed
			$cacheId = 'post.withoutGlobalScopes.with.city.pictures.' . $postId . '.' . config('app.locale');
			$post    = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postId) {
				return Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
					->withCountryFix()
					->unarchived()
					->where('id', $postId)
					->with([
						'category'      => function ($builder) { $builder->with(['parent']); },
						'postType',
						'city',
						'pictures',
						'latestPayment' => function ($builder) { $builder->with(['package']); },
					])
					->first();
			});
			
			// If the logged user is not an admin user...
			if (!auth()->user()->can(Permission::getStaffPermissions())) {
				// Then don't get post that are not from the user
				if (!empty($post) && $post->user_id != auth()->user()->id) {
					$cacheId = 'post.with.city.pictures.' . $postId . '.' . config('app.locale');
					$post    = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postId) {
						return Post::withCountryFix()
							->unarchived()
							->where('id', $postId)
							->with([
								'category'      => function ($builder) { $builder->with(['parent']); },
								'postType',
								'city',
								'pictures',
								'latestPayment' => function ($builder) { $builder->with(['package']); },
							])
							->first();
					});
				}
			}
		} else {
			$cacheId = 'post.with.city.pictures.' . $postId . '.' . config('app.locale');
			$post    = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postId) {
				return Post::withCountryFix()
					->unarchived()
					->where('id', $postId)
					->with([
						'category'      => function ($builder) { $builder->with(['parent']); },
						'postType',
						'city',
						'pictures',
						'latestPayment' => function ($builder) { $builder->with(['package']); },
					])
					->first();
			});
		}
		// Preview Post after activation
		if (request()->filled('preview') && request()->get('preview') == 1) {
			// Get post's details even if it's not activated and reviewed
			$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->withCountryFix()
				->where('id', $postId)
				->with([
					'category'      => function ($builder) { $builder->with(['parent']); },
					'postType',
					'city',
					'pictures',
					'latestPayment' => function ($builder) { $builder->with(['package']); },
				])
				->first();
		}
		
		// Post not found
		if (empty($post) || empty($post->category) || empty($post->postType) || empty($post->city)) {
			abort(404, t('Post not found'));
		}
		
		// Share post's details
		view()->share('post', $post);
		
		// Get possible post's Author (User)
		$user = null;
		if (isset($post->user_id) && !empty($post->user_id)) {
			$user = User::find($post->user_id);
		}
		view()->share('user', $user);
		
		// Get user picture
		$userPhoto = (!empty($post->email)) ? Gravatar::fallback(url('images/user.jpg'))->get($post->email) : null;
		if (isset($user) && !empty($user) && isset($user->photo) && !empty($user->photo)) {
			$userPhoto = imgUrl($user->photo, 'user');
		}
		view()->share('userPhoto', $userPhoto);
		
		// Get Post's user decision about comments activation
		$commentsAreDisabledByUser = false;
		if (isset($user) && !empty($user)) {
			if ($user->disable_comments == 1) {
				$commentsAreDisabledByUser = true;
			}
		}
		view()->share('commentsAreDisabledByUser', $commentsAreDisabledByUser);
		
		// Category Breadcrumb
		$catBreadcrumb = $this->getCatBreadcrumb($post->category, 1);
		view()->share('catBreadcrumb', $catBreadcrumb);
		
		// Get Custom Fields
		$customFields = $this->getPostFieldsValues($post->category->tid, $post->id);
		view()->share('customFields', $customFields);
		
		// Increment Post visits counter
		Event::dispatch(new PostWasVisited($post));
		
		// GET SIMILAR POSTS
		if (config('settings.single.similar_posts') == '1') {
			$cacheId = 'posts.similar.category.' . $post->category->tid . '.post.' . $post->id;
			$posts   = Cache::remember($cacheId, $this->cacheExpiration, function () use ($post) {
				return $post->getSimilarByCategory();
			});
			
			// Featured Area Data
			$featured         = [
				'title' => t('Similar Ads'),
				'link'  => qsUrl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c' => $post->category->tid])),
				'posts' => $posts,
			];
			$data['featured'] = (count($posts) > 0) ? ArrayHelper::toObject($featured) : null;
		} else if (config('settings.single.similar_posts') == '2') {
			$distance = 50; // km OR miles
			
			$cacheId = 'posts.similar.city.' . $post->city->id . '.post.' . $post->id;
			$posts   = Cache::remember($cacheId, $this->cacheExpiration, function () use ($post, $distance) {
				return $post->getSimilarByLocation($distance);
			});
			
			// Featured Area Data
			$featured         = [
				'title' => t('more_ads_at_x_distance_around_city', [
					'distance' => $distance,
					'unit'     => getDistanceUnit(config('country.code')),
					'city'     => $post->city->name,
				]),
				'link'  => qsUrl(trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except(['l', 'location']), ['l' => $post->city->id])),
				'posts' => $posts,
			];
			$data['featured'] = (count($posts) > 0) ? ArrayHelper::toObject($featured) : null;
		}
		
		// SEO
		$title       = $post->title . ', ' . $post->city->name;
		$description = Str::limit(str_strip(strip_tags($post->description)), 200);
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		if (!empty($post->tags)) {
			MetaTag::set('keywords', str_replace(',', ', ', $post->tags));
		}
		
		// Open Graph
		$this->og->title($title)
			->description($description)
			->type('article');
		if (!$post->pictures->isEmpty()) {
			if ($this->og->has('image')) {
				$this->og->forget('image')->forget('image:width')->forget('image:height');
			}
			foreach ($post->pictures as $picture) {
				$this->og->image(imgUrl($picture->filename, 'big'), [
					'width'  => 600,
					'height' => 600,
				]);
			}
		}
		view()->share('og', $this->og);
		
		/*
		// Expiration Info
		$today = Date::now(config('timezone.id'));
		if ($today->gt($post->created_at->addMonths($this->expireTime))) {
			flash(t("this_ad_is_expired"))->error();
		}
		*/
		
		// Reviews Plugin Data
		if (config('plugins.reviews.installed')) {
			try {
				$rvPost = \extras\plugins\reviews\app\Models\Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->find($post->id);
				view()->share('rvPost', $rvPost);
			} catch (\Exception $e) {
			}
		}
	  Session::push('post', $data);
	
//=============================================
		// Check multi-countries site parameters
		if (!config('settings.seo.multi_countries_urls')) {
			$userId = $countryCode;
		}
		
		view()->share('isUserSearch', $this->isUserSearch);
		
		// Get User
		$this->sUser = User::findOrFail($userId);
		
		// Redirect to User's profile If username exists
		if (!empty($this->sUser->username)) {
			$url = UrlGen::user($this->sUser, null, $countryCode);
			headerLocation($url);
		}
		
		return $this->searchByUserId($this->sUser->id);
	}
	
	/**
	 * @param $countryCode
	 * @param null $username
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function profile($countryCode, $username = null)
	{
		// Check multi-countries site parameters
		if (!config('settings.seo.multi_countries_urls')) {
			$username = $countryCode;
		}
		
		view()->share('isUserSearch', $this->isUserSearch);
		
		// Get User
		$this->sUser = User::where('username', $username)->firstOrFail();
		
		return $this->searchByUserId($this->sUser->id, $this->sUser->username);
	}
	
	/**
	 * @param $userId
	 * @param null $username
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	private function searchByUserId($userId, $username = null)
	{
		// Search
		$search = new $this->searchClass();
		$data = $search->setUser($userId)->fetch();
		
		// Get Titles
		$bcTab = $this->getBreadcrumb();
		$htmlTitle = $this->getHtmlTitle();
		view()->share('bcTab', $bcTab);
		view()->share('htmlTitle', $htmlTitle);
		
		// Meta Tags
		$title = $this->getTitle();
		MetaTag::set('title', $title);
		MetaTag::set('description', $title);
		
		// Translation vars
		view()->share('uriPathUserId', $userId);
		view()->share('uriPathUsername', $username);
		
		return appView('search.db', $data);
	}
}
