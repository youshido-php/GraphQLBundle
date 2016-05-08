# Symfony GraphQl Bundle

### This is a bundle based on the pure [GraphQL PHP Server implementation](http://github.com/youshido/graphql/)

This bundle provides you with:

 * Full compatibility with the [RFC Specification for GraphQL](https://facebook.github.io/graphql/)
 * Agile object oriented structure to architect your GraphQL Schema
 * Intuitive Type system that allows you to build your project much faster and stay consistent
 * Build-in validation for the GraphQL Schema you develop
 * Well documented classes with a lot of examples 
 * Automatically created endpoint /graphql to handle requests
 
## Installing GraphQL Bundle

We assume you have `composer`, but if you're not – install it from the [official website](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).
After you've done with that, simply run
```
$> composer require youshido/graphql-bundle
```

Than add bundle to your `app/AppKernel.php`
```
...
new Youshido\GraphQLBundle\GraphQLBundle(),
...
```

And finally add the routing reference to the `app/config/routing.yml`:
```
graphql:
    resource: "@GraphQLBundle/Controller/"
```

## Documentation

Detailed documentation is available on the main GraphQL repository – http://github.com/youshido/graphql/.
