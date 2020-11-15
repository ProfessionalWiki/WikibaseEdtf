module.exports = ( function( $, vv ) {
	'use strict';

	var PARENT = vv.experts.StringValue;

	vv.experts.EDTF = vv.expert( 'EDTF', PARENT, {
		/**
		 * @inheritdoc
		 * @protected
		 */
		_init: function() {
			PARENT.prototype._init.call( this );
		}
	} );

	return vv.experts.EDTF;

}( jQuery, jQuery.valueview ) );
