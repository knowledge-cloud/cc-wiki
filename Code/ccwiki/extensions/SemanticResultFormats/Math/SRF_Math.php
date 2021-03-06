<?php

/**
 * Various mathematical functions - sum, product, average, min and max.
 *
 * @file
 * @ingroup SemanticResultFormats
 * 
 * @licence GNU GPL v3+
 * 
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Yaron Koren
 * @author Nathan Yergler
 */
class SRFMath extends SMWResultPrinter {

	/**
	 * (non-PHPdoc)
	 * @see SMWResultPrinter::getName()
	 */
	public function getName() {
		return wfMsg( 'srf_printername_' . $this->mFormat );
	}

	/**
	 * (non-PHPdoc)
	 * @see SMWResultPrinter::getResult()
	 */
	public function getResult( SMWQueryResult $results, array $params, $outputmode ) {
		$this->readParameters( $params, $outputmode );
		global $wgLang;
		return $wgLang->formatNum( $this->getResultText( $results, SMW_OUTPUT_HTML ) );
	}

	/**
	 * (non-PHPdoc)
	 * @see SMWResultPrinter::getResultText()
	 */
	protected function getResultText( SMWQueryResult $res, $outputmode ) {
		$numbers = $this->getNumbers( $res );
		
		if ( count( $numbers ) == 0 ) {
			return '';
		}
		
		switch ( $this->mFormat ) {
			case 'max':
				return max( $numbers );
				break;
			case 'min':
				return min( $numbers );
				break;
			case 'sum':
				return array_sum( $numbers );
				break;
			case 'product':
				return array_product( $numbers );
				break;
			case 'average':
				return array_sum( $numbers ) / count( $numbers );
				break;
			case 'median':
				sort( $numbers, SORT_NUMERIC );
				$position = ( count( $numbers ) + 1 ) / 2 - 1;
				return ( $numbers[ceil( $position )] + $numbers[floor( $position )] ) / 2; 
				break;
		}
	}
	
	/**
	 * Gets a list of all numbers.
	 * 
	 * @since 1.6
	 * 
	 * @param SMWQueryResult $res
	 * 
	 * @return array
	 */
	protected function getNumbers( SMWQueryResult $res ) {
		$numbers = array();

		while ( $row = $res->getNext() ) {
			foreach( $row as /* SMWResultArray */ $resultArray ) {
				while ( ( $dataValue = efSRFGetNextDV( $resultArray ) ) !== false ) {
					self::addNumbersForDataValue( $dataValue, $numbers );
				}				
			}
		}

		return $numbers;
	}
	
	/**
	 * Gets a list of all numbers contained in a datavalue.
	 * 
	 * @since 1.6
	 * 
	 * @param SMWDataValue $dataValue
	 * @param array $numbers
	 */
	public static function addNumbersForDataValue( SMWDataValue $dataValue, array &$numbers ) {
		// Make use of instanceof instead of getTypeId so that deriving classes will get handled as well.
		if ( $dataValue instanceof SMWNumberValue ) {
			// getDataItem was introduced in SMW 1.6, getValueKey was deprecated in the same version.
			if ( method_exists( $dataValue, 'getDataItem' ) ) {
				self::addNumbersForDataItem( $dataValue->getDataItem(), $numbers );
			} else {
				$numbers[] = $dataValue->getValueKey();
			}
		// Support for records (SMWRecordValue) using code added in SMW 1.6.
		} elseif ( $dataValue instanceof SMWRecordValue && method_exists( $dataValue, 'getDataItem' ) ) {
			self::addNumbersForDataItem( $dataValue->getDataItem(), $numbers );
		// Support for SMWNAryValue, which was removed in SMW 1.6.
		} elseif ( $dataValue instanceof SMWNAryValue ) {
			foreach ( $dataValue->getDVs() as $dataValue ) {
				self::addNumbersForDataValue( $dataValue, $numbers );
			}
		}
	}
	
	/**
	 * Gets a list of all numbers contained in a dataitem.
	 * 
	 * @since 1.6
	 * 
	 * @param SMWDataItem $dataItem
	 * @param array $numbers
	 */
	public static function addNumbersForDataItem( SMWDataItem $dataItem, array &$numbers ) {
		switch ( $dataItem->getDIType() ) {
			case SMWDataItem::TYPE_NUMBER:
				$numbers[] = $dataItem->getNumber();
				break;
			case SMWDataItem::TYPE_CONTAINER:
				foreach ( $dataItem->getDataItems() as $di ) {
					self::addNumbersForDataItem( $di, $numbers );
				}
				break;
			default:
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see SMWResultPrinter::getParameters()
	 */
	public function getParameters() {
		return array(
			array( 'name' => 'limit', 'type' => 'int', 'description' => wfMsg( 'srf_paramdesc_limit' ) ),
		);
	}

}
