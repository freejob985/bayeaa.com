<?php


namespace App\Http\Controllers\Install\Traits\Install\Db;

use App\Helpers\DBTool;

trait SqlFilesTrait
{
	/**
	 * Import Database's Schema (Migration)
	 *
	 * @param \PDO $pdo
	 * @param $tablesPrefix
	 * @return bool
	 */
	protected function importSchemaSql(\PDO $pdo, $tablesPrefix)
	{
		// Default Schema SQL file
		$filename = 'database/schema.sql';
		$filePath = storage_path($filename);
		
		// Import the SQL file
		$res = DBTool::importSqlFile($pdo, $filePath, $tablesPrefix);
		if ($res === false) {
			dd('ERROR');
		}
		
		return $res;
	}
	
	/**
	 * Import Database's Required Data (Seeding)
	 *
	 * @param \PDO $pdo
	 * @param $tablesPrefix
	 * @return bool
	 */
	protected function importDataSql(\PDO $pdo, $tablesPrefix)
	{
		// Default Required Data SQL file
		$filename = 'database/data.sql';
		$filePath = storage_path($filename);
		
		// Import the SQL file
		$res = DBTool::importSqlFile($pdo, $filePath, $tablesPrefix);
		if ($res === false) {
			dd('ERROR');
		}
		
		return $res;
	}
}
