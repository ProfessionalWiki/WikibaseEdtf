#! /bin/bash

set -e

MW_BRANCH=$1
EXTENSION_NAME=$2

wget "https://github.com/wikimedia/mediawiki/archive/refs/heads/$MW_BRANCH.tar.gz" -nv

tar -zxf $MW_BRANCH.tar.gz
mv mediawiki-$MW_BRANCH mediawiki

cd mediawiki

composer install
php maintenance/install.php --dbtype sqlite --dbuser root --dbname mw --dbpath $(pwd) --pass AdminPassword WikiName AdminUser

echo 'error_reporting(E_ALL| E_STRICT);' >> LocalSettings.php
echo 'ini_set("display_errors", 1);' >> LocalSettings.php
echo '$wgShowExceptionDetails = true;' >> LocalSettings.php
echo '$wgShowDBErrorBacktrace = true;' >> LocalSettings.php
echo '$wgDevelopmentWarnings = true;' >> LocalSettings.php

echo '$wgEnableWikibaseRepo = true;' >> LocalSettings.php
echo '$wgEnableWikibaseClient = true;' >> LocalSettings.php
echo "wfLoadExtension( 'WikibaseRepository', __DIR__ . '/extensions/Wikibase/extension-repo.json' );" >> LocalSettings.php
echo 'require_once __DIR__ . "/extensions/Wikibase/repo/ExampleSettings.php";' >> LocalSettings.php
echo "wfLoadExtension( 'WikibaseClient', __DIR__ . '/extensions/Wikibase/extension-client.json' );" >> LocalSettings.php
echo 'require_once __DIR__ . "/extensions/Wikibase/client/ExampleSettings.php";' >> LocalSettings.php

echo 'wfLoadExtension( "'$EXTENSION_NAME'" );' >> LocalSettings.php

cat <<EOT >> composer.local.json
{
  "require": {
    "wikibase/wikibase": "dev-$MW_BRANCH"
  },
	"extra": {
		"merge-plugin": {
			"merge-dev": true,
			"include": [
				"extensions/$EXTENSION_NAME/composer.json"
			]
		}
	}
}
EOT

cd extensions
git clone https://gerrit.wikimedia.org/r/mediawiki/extensions/Wikibase --branch=$MW_BRANCH -j8

cd Wikibase
git submodule set-url view/lib/wikibase-serialization https://github.com/wmde/WikibaseSerializationJavaScript.git
git submodule set-url view/lib/wikibase-data-values https://github.com/wmde/DataValuesJavaScript.git
git submodule set-url view/lib/wikibase-data-model https://github.com/wmde/WikibaseDataModelJavaScript.git
git submodule sync && git submodule init && git submodule update --recursive
