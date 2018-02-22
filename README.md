# Comments Bundle

Symfony bundle that provides commenting functionality (posting, reading and administering comments).

Features include:

* API endpoint for fetching existing comments (supports pagination)
* API endpoint for posting comments

## Installation

- Add composer dependency:

```
composer require movingimage/mi-comments-bundle
```

- Include MICommentsBundle and dependent Bundles in your AppKernel.php:

```
$bundles = [
    //...
    new FOS\RestBundle\FOSRestBundle(),
    new JMS\SerializerBundle\JMSSerializerBundle(),
    new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
    new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(), //optional
    new MovingImage\Bundle\MICommentsBundle\MICommentsBundle(),
];
```

- Include bundle routes to routing.yml:

```
mi_comments:
    resource: "@MICommentsBundle/Resources/config/routing.yml"
    prefix:   /
```

- Configure the bundle in config.yml

```
mi_comments:
   auto_publish: true
```

- Configure doctrine migrations bundle in config.yml (optional)

```
doctrine_migrations:
    dir_name: "%kernel.root_dir%/../vendor/movingimage/mi-comments-bundle/Migrations"
    namespace: MovingImage\Bundle\MICommentsBundle\Migrations
    table_name: mi_comments_bundle_migration_versions
    name: MICommentsBundle Migrations
    organize_migrations: false
    custom_template: ~
```

If you are already using doctrine migrations bundle in your project, keep your current
configuration, but copy the migration files from Migrations directory to your existing
migrations directory. Alternatively create the required table manually. In this
case you don't need to use the doctrine migrations bundle.

## Configuration parameters

Parameter       | Type      | Default   | Required  | Description
----------------|-----------|-----------|-----------|------------
auto_publish    | boolean   | false     | no        | If true, comments are automatically published

## Usage

### POST a comment

```
curl -X POST \
  http://<base_url>/comments \
  -H 'Content-Type: application/json' \
  -d '{
	"user": {
	    "email": "pavle.predic@movingimage.com",
	    "name": "Pavle Predic"
	},
	"entity": {
	    "id": "DF3osABraqfGPaSGtty8vt",
	    "title": "Recruiting Teaser Karo & Matthias"
	},
	"comment": "What an awesome video! Great job!"
}'
```

### GET published comments

```
curl -X GET <base_url>/comments?entityId=DF3osABraqfGPaSGtty8vt&limit=10&offset=20
```

This will return a list of published comments for the specified video ID (if provided),
ordered by creation date (newest first). If no limit and offset are provided,
the defaults will be used (limit: 10, offset: 0).

## License

This bundle is under the BSD 3-clause license. Please check the LICENSE file for the complete license.