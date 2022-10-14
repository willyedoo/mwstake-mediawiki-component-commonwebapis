var cache = {
	data: {},
	set: function( key, data ) {
		cache.data[key] = data;
	},
	get: function( key, defaultValue ) {
		return cache.data[key] || defaultValue;
	},
	has: function( key ) {
		return cache.data[key] !== undefined;
	}
}
function queryStore( store, params, cacheKey ) {
	var dfd = $.Deferred();
	$.ajax( {
		method: 'GET',
		url: mw.util.wikiScript( 'rest' ) + '/mws/v1/' + store,
		data: params
	} ).done( function( response ) {
		if ( response && response.results ) {
			for ( var i = 0; i < response.results.length; i++ ) {
				var result = response.results[i];
				// Replace named placeholders in curly braces with actual values
				var key = cacheKey.replace( /\{([^}]+)\}/g, function( match, p1 ) {
					return result[p1];
				} );
				// if cache key contains a placeholder that is not in the result, skip
				if ( key.indexOf( '{' ) !== -1 ) {
					continue;
				}
				cache.set( key, result );
			}



			dfd.resolve( response.results );
			return;
		}
		dfd.resolve( [] );
	} ).fail( function( err ) {
		dfd.resolve( err );
	} );
	return dfd.promise();
}

mws = window.mws || {};
mws.commonwebapis = {
	user: {
		query: function( params ) {
			return queryStore( 'user-query-store', params, 'user-data-{user_name}' );
		},
		getByUsername: function( username, recache ) {
			var dfd = $.Deferred();
			if ( !username || typeof username !== 'string' || username.length < 2) {
				return dfd.resolve( {} ).promise();
			}

			// First letter of username to upper
			username = username.charAt( 0 ).toUpperCase() + username.slice( 1 );
			if ( !recache && cache.has( 'user-data-' + username ) ) {
				dfd.resolve( cache.get( 'user-data-' + username ) );
				return dfd.promise();
			}
			mws.commonwebapis.user.query( {
				filter: JSON.stringify( [
					{
						type: 'string',
						value: username,
						operator: 'eq',
						property: 'user_name'
					}
				] ),
				limit: 1
			} ).done( function( response ) {
				if ( response.length > 0 ) {
					dfd.resolve( response[0] );
					return;
				}
				dfd.resolve( {} );
			} ).fail( function( err ) {
				dfd.resolve( err );
			} );

			return dfd.promise();
		}
	}
}
