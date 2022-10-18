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

function querySingle( store, property, value, cacheKey, recache ) {
	var dfd = $.Deferred();
	if ( !value || typeof value !== 'string' || value.length < 2 ) {
		return dfd.resolve( {} ).promise();
	}

	if ( !recache && cache.has( cacheKey ) ) {
		dfd.resolve( cache.get( cacheKey ) );
		return dfd.promise();
	}
	mws.commonwebapis[store].query( {
		filter: JSON.stringify( [
			{
				type: 'string',
				value: value,
				operator: 'eq',
				property: property
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
			return querySingle(
				'user', 'user_name', username, 'user-data-' + username, recache
			);
		}
	},
	group: {
		query: function( params ) {
			return queryStore( 'group-store', params, 'group-{group_name}' );
		},
		getByGroupName: function( groupname, recache ) {
			return querySingle(
				'group', 'group_name', groupname, 'group-' + groupname, recache
			);
		}
	}
}
