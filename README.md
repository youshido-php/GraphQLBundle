# Symfony GraphQl Bundle

### This is a bundle based on the pure [PHP GraphQL Server](http://github.com/youshido/graphql/) implementation

This bundle provides you with:

 * Full compatibility with the [RFC Specification for GraphQL](https://facebook.github.io/graphql/)
 * Agile object oriented structure to architect your GraphQL Schema
 * Intuitive Type system that allows you to build your project much faster and stay consistent
 * Build-in validation for the GraphQL Schema you develop
 * Well documented classes with a lot of examples
 * Automatically created endpoint /graphql to handle requests

## Table of Contents

 * [Installation](#installation)
 * [Symfony features included](#symfony-features-included)
    * [AbstractContainerAwareField class](#class-abstractcontainerawarefield)
    * [Service method as callable](#service-method-as-callable)
    * [Resolve field security](#resolve-field-security)
 * [Documentation](#documentation)


## Installation

We assume you have `composer`, if you're not – install it from the [official website](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).  
If you need any help installing Symfony framework – here's the link [http://symfony.com/doc/current/book/installation.html](http://symfony.com/doc/current/book/installation.html).
> Shortcut to install Symfony: `composer create-project symfony/framework-standard-edition my_project_name`

Once you have your composer up and running – you're ready to install the GraphQL Bundle.   
Go to your project folder and run:
```sh
composer require youshido/graphql-bundle
```

Than enable bundle in your `app/AppKernel.php`
```php
new Youshido\GraphQLBundle\GraphQLBundle(),
```

Add the routing reference to the `app/config/routing.yml`:
```yaml
graphql:
    resource: "@GraphQLBundle/Controller/"
```

Let's check if you've done everything right so far – try to access url `localhost:8000/graphql`.  
You should get a JSON response with the following error:
```js
{"errors":[{"message":"You have to set GraphQL Schema to process"}]}
```

That's because there was no GraphQL Schema specified for the processor yet. You need to create a GraphQL Schema class and set it inside your `app/config/config.yml` file.

> There is a way where you can use inline approach and do not create a Schema class, in order to do that you have to define your own GraphQL controller and use a `->setSchema` method of the processor to set the Schema.  

The fastest way to create a Schema class is to use a generator shipped with this bundle:
```sh
php bin/console graphql:schema:generate AppBundle
```
Here *AppBundle* is a name of the bundle where the class will be generated in.  
You will be requested for a confirmation to create a class and then presented with instructions to update your config file.

```yaml
# Update your app/config/config.yml with the parameter:
graph_ql:
  schema_class: AppBundle\GraphQL\Schema
```

After you've added parameters to the config file, try to access the following link in the browser – `http://localhost:8000/graphql?query={hello}`

> Alternatively, you can execute the same request using CURL client in your console  
> `curl http://localhost:8000/graphql --data "query={ hello }"`

Successful response from a test Schema will be displayed:
```js
{"data":{"hello":"world!"}}
```

That means you have GraphQL Bundle for the Symfony Framework configured and now can architect your GraphQL Schema:

## Symfony features included:
### Class AbstractContainerAwareField:
AbstractContainerAwareField class used for auto passing container to field, add ability to use container in resolve function:
```php
class RootDirField extends AbstractContainerAwareField
{

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return new StringType();
    }

    /**
     * @inheritdoc
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $this->container->getParameter('kernel.root_dir');
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'rootDir';
    }
```

### Service method as callable:
Ability to pass service method as resolve callable:
```php
$config->addField(new Field([
    'name'    => 'cacheDir',
    'type'    => new StringType(),
    'resolve' => ['@resolve_service', 'getCacheDir']
]))
```

### Restrict Access to the whole endpoint or to a specific root fields
You can specify `anonymous` parameter under `graph_ql` section:
```yml
graph_ql:
    anonymous: true #default
```
Allow full anonymous access

```yml
graph_ql:
    anonymous: false
```
Disable access for unauthenticated users

```yml
graph_ql:
    anonymous: ["info", "login"]
```
Allowing access to those `info` and `login` fields under the rootQuery or rootMutation


### Resolve field security:
First of all, you need to enable it in your config.yml file:
```yaml
graph_ql:
    security_enable: true
```
Then create standard security voter for that ([official documentation](http://symfony.com/doc/current/security/voters.html)), as in example below:
```php
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQLBundle\Security\Manager\SecurityManagerInterface;

class GraphQLVoter extends Voter
{

    /**
     * @inheritdoc
     */
    protected function supports($attribute, $subject)
    {
        return $attribute == SecurityManagerInterface::RESOLVE_ATTRIBUTE && $subject instanceof ResolveInfo;
    }

    /**
     * @inheritdoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // your own validation logic here

        /** @var $subject ResolveInfo */
        if ($subject->getField()->getName() == 'hello') { //example
            return false;
        }

        return true;
    }
}
```
Now GraphQL executor will check access for every resolved field before resolve it.


## GraphiQL extension:
To run [graphiql extension](https://github.com/graphql/graphiql) just try to access to `http://your_domain/explorer`

## Documentation
All detailed documentation is available on the main GraphQL repository – http://github.com/youshido/graphql/.
