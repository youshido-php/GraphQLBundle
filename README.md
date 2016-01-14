# Symfony2 GraphQl Bundle

### True object oriented GraphQL PHP Server realization

This is not a PHP port of the JavaScript GraphQL. 
This is a Object oriented realization of the GraphQL server conforming the [RFC Specification for GraphQL](https://facebook.github.io/graphql/).
 
## Installing graphql-bundle

We assume you have `composer`, if not – [go get install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).
After that, simply run
```
$> composer require youshido/graphql-bundle='dev-master'
```
Than add bundle to your `app/AppKernel.php`
```
...
new Youshido\GraphQLBundle\GraphQLBundle(),
...
```

And finally adding routing reference to the `app/config/routing.yml`:
```
graphql:
    resource: "@GraphQLBundle/Controller/"
```

## Examples

Right now you can learn some examples in our test directory but we're going to put out the whole step by step guide for you soon.