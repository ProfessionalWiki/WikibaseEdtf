<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\TimeValue;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\Repo\Rdf\Values\TimeRdfBuilder;
use Wikibase\Repo\Rdf\ValueSnakRdfBuilder;
use Wikimedia\Purtle\RdfWriter;

class RdfBuilder implements ValueSnakRdfBuilder {

	private TimeRdfBuilder $timeRdfBuilder;
	private TimeValueBuilder $timeValueBuilder;

	public function __construct( TimeRdfBuilder $timeRdfBuilder, TimeValueBuilder $timeValueBuilder ) {
		$this->timeRdfBuilder = $timeRdfBuilder;
		$this->timeValueBuilder = $timeValueBuilder;
	}

	/**
	 * @param RdfWriter $writer
	 * @param string $propertyValueNamespace Property value relation namespace
	 * @param string $propertyValueLName Property value relation name
	 * @param string $dataType Property data type
	 * @param PropertyValueSnak $snak
	 */
	public function addValue( RdfWriter $writer, $propertyValueNamespace, $propertyValueLName, $dataType, $snakNamespace, PropertyValueSnak $snak ) {
		foreach ( $this->edtfSnakToTimeSnaks( $snak ) as $timeValueSnak ) {
			$this->timeRdfBuilder->addValue(
				$writer,
				$propertyValueNamespace,
				$propertyValueLName,
				$dataType,
				$snakNamespace,
				$timeValueSnak
			);
		}
	}

	/**
	 * @return PropertyValueSnak[]
	 */
	private function edtfSnakToTimeSnaks( PropertyValueSnak $edtfSnak ): array {
		return array_map(
			fn ( TimeValue $time ) => new PropertyValueSnak(
				$edtfSnak->getPropertyId(),
				$time
			),
			$this->timeValueBuilder->edtfToTimeValues( $edtfSnak->getDataValue()->getValue() )
		);
	}

}
