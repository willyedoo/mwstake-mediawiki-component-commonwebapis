## MediaWiki Stakeholders Group - Components
# Common Web APIs for MediaWiki

Provides various web APIs (Action API and REST).

**This code is meant to be executed within the MediaWiki application context. No standalone usage is intended.**

## Prerequisites

## Use in a MediaWiki extension

In your extensions `extension.json` file, register all required API endpoints. E.g.:

```json
{
	...
	"attributes": {
		"MWStake": {
			"CommonWebAPIs": [ "aysnc-menu", "async-container" ]
		}
	},
	...
}
```

## Configuration

## Debugging
