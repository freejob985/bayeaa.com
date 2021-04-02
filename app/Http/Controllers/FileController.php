<?php


namespace App\Http\Controllers;


use App\Helpers\Files\Response\FileContentResponseCreator;
use App\Helpers\Files\Storage\StorageDisk;

class FileController extends Controller
{
	protected $disk;
	
	/**
	 * FileController constructor.
	 */
	public function __construct()
	{
		$this->disk = StorageDisk::getDisk();
	}
	
	/**
	 * @param FileContentResponseCreator $response
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse|void
	 */
	public function show(FileContentResponseCreator $response)
	{
		$filePath = request()->get('path');
		$filePath = preg_replace('|\?.*|ui', '', $filePath);
		
		$out = $response::create($this->disk, $filePath);
		
		ob_end_clean(); // HERE IS THE MAGIC
		
		return $out;
	}
}
