<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

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
		$this->timeRdfBuilder->addValue(
			$writer,
			$propertyValueNamespace,
			$propertyValueLName,
			$dataType,
			$snakNamespace,
			$this->edtfSnakToTimeSnak( $snak )
		);
	}

	private function edtfSnakToTimeSnak( PropertyValueSnak $edtfSnak ): PropertyValueSnak {
		return new PropertyValueSnak(
			$edtfSnak->getPropertyId(),
			$this->timeValueBuilder->edtfToTimeValue( $edtfSnak->getDataValue()->getValue() )
		);
	}

}
