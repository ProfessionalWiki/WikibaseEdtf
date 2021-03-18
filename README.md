# Wikibase EDTF

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/ProfessionalWiki/WikibaseEdtf/CI/master)](https://github.com/ProfessionalWiki/WikibaseEdtf/actions?query=workflow%3ACI)
[![Latest Stable Version](https://poser.pugx.org/professional-wiki/wikibase-edtf/version.png)](https://packagist.org/packages/professional-wiki/wikibase-edtf)
[![Download count](https://poser.pugx.org/professional-wiki/wikibase-edtf/d/total.png)](https://packagist.org/packages/professional-wiki/wikibase-edtf)

[MediaWiki] extension that adds support for the [Extended Date/Time Format (EDTF) Specification][EDTF] to [Wikibase] via a new data type.

Wikibase EDTF has been made possible with the financial support of the Luxembourg Ministry of Culture.
It is developed by [Professional.Wiki].

## Platform requirements

* [PHP] 7.4 or later, including PHP 8.0
* [MediaWiki] 1.35
* [Wikibase Repository] REL1_35

See the [release notes](#release-notes) for more information on the different versions of this extension.

## Installation

First install MediaWiki and Wikibase Repository.

The recommended way to install Wikibase EDTF is using [Composer] with
[MediaWiki's built-in support for Composer][Composer install].

On the commandline, go to your wikis root directory. Then run these two commands:

```shell script
COMPOSER=composer.local.json composer require --no-update professional-wiki/wikibase-edtf:*
composer update professional-wiki/wikibase-edtf --no-dev -o
```

**Enabling the extension**

Then enable the extension by adding the following to the bottom of your wikis `LocalSettings.php` file:

```php
wfLoadExtension( 'WikibaseEdtf' );
```

You can verify the extension was enabled successfully by opening your wikis Special:Version page in your browser.

## Running the tests

* PHP tests: `php tests/phpunit/phpunit.php -c extensions/WikibaseEdtf/`

## Release notes

### Version 1.0.0 - 2021-03-19

* Initial release for MediaWiki/Wikibase 1.35
* EDTF datatype with
  	* Support for EDTF levels 0, 1 and 2
	* Input validation
	* Display of humanized and internationalized version in the reading UI
	* RDF export (using standard Wikibase dates) for most values

[Professional.Wiki]: https://professional.wiki
[EDTF]: https://www.loc.gov/standards/datetime/
[Wikibase]: https://wikibase.consulting/what-is-wikibase/
[MediaWiki]: https://www.mediawiki.org
[PHP]: https://www.php.net
[Wikibase Repository]: https://www.mediawiki.org/wiki/Extension:Wikibase_Repository
[Composer]: https://getcomposer.org
[Composer install]: https://professional.wiki/en/articles/installing-mediawiki-extensions-with-composer
