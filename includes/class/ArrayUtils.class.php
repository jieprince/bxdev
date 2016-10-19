<?php
/**
* 将一个二维数组按照多个列进行排序，类似 SQL 语句中的 ORDER BY
*
* 用法：
* @code php
* $rows = Helper_Array::sortByMultiCols($rows, array(
* 'parent' => SORT_ASC,
* 'name' => SORT_DESC,
* ));
* @endcode
*/
class ArrayUtils {
	/**
	 * @param array $array 要排序的数组
	 * 
	 * @param string $keyname
	 *        	排序的键
	 * @param int $dir
	 *        	排序方向
	 *        	
	 * @return array 排序后的数组
	 *        
	 */
	static function sortByCol($array, $keyname, $dir = SORT_ASC) {
		return self::sortByMultiCols ( $array, array (
				$keyname => $dir 
		) );
	}
	
	/**
	 *
	 * @param array $rowset
	 *        	要排序的数组
	 * @param array $args
	 *        	排序的键
	 *        	
	 * @return array 排序后的数组
	 *        
	 */
	private static function sortByMultiCols($rowset, $args) {
		$sortArray = array ();
		$sortRule = '';
		foreach ( $args as $sortField => $sortDir ) {
			foreach ( $rowset as $offset => $row ) {
				$sortArray [$sortField] [$offset] = $row [$sortField];
			}
			$sortRule .= '$sortArray[\'' . $sortField . '\'], ' . $sortDir . ', ';
		}
		if (empty ( $sortArray ) || empty ( $sortRule )) {
			return $rowset;
		}
		eval ( 'array_multisort(' . $sortRule . '$rowset);' );
		return $rowset;
	}
}

?>