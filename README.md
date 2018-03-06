# Comments Bundle

[![Build Status](https://travis-ci.org/MovingImage24/MICommentsBundle.svg?branch=master)](https://travis-ci.org/MovingImage24/MICommentsBundle)

Symfony bundle that provides commenting functionality (posting, reading and administering comments).

Features include:

* API endpoint for fetching existing comments (supports pagination)
* API endpoint for posting comments
* Admin area for administering comments (listing, publishing and rejecting)

## Installation

### Add composer dependency:

```
composer require movingimage/mi-comments-bundle
```

### Include MICommentsBundle and dependent Bundles in your AppKernel.php:

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

### Include bundle routes to routing.yml:

```
mi_comments:
    resource: "@MICommentsBundle/Resources/config/routing.yml"
    prefix:   /
```

You may choose a different prefix if required.
 
### Configure the bundle in config.yml

```
mi_comments:
   auto_publish: true //optional, by default is false
```

### Configure doctrine migrations bundle in config.yml (optional)

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

### Configure Sonata Admin Bundle (optional)

- Add SonataBundle and dependent bundles to AppKernel.php:

```
$bundles = [
    //...
    new Symfony\Bundle\SecurityBundle\SecurityBundle(),
    new Sonata\CoreBundle\SonataCoreBundle(),
    new Sonata\BlockBundle\SonataBlockBundle(),
    new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
    new Sonata\AdminBundle\SonataAdminBundle(),
];
```

- Enable translator in config.yml

```
translator: { fallbacks: ['%locale%'] }
```
    
- Configure sonata bundle in config.yml

```
sonata_block:
    blocks:
        sonata.admin.block.admin_list:
            contexts: [admin]
        sonata.admin.block.search_result:
            contexts: [admin]

sonata_admin: ~
```
    
- Configure routes in routing.yml

```
mi_comments_admin:
    resource: "@MICommentsBundle/Resources/config/admin_routing.yml"
    prefix:   /admin
```

You may choose a different prefix if required.

- Install assets

```
./bin/console assets:install
```

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