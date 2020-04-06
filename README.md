# Blog
create DB && update schema

    bin/console do:da:cr
    bin/console do:sc:up --force
 
 
load fixtures

    bin/console hautelook:fixtures:load
    
load fixtures (test db)

    bin/console t:f:l -e test -f
    
Generate the SSH keys (LexikJWTAuthenticationBundle)

    https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#prerequisites
    chmod -R 644 config/jwt/*

Links

    http://blog.zraiev.top/api-doc - API doc
    http://blog.zraiev.top/admin - admin
    
Admin user

    admin@example.com
    test

To send notifications run command
    
    bin/console s:s:n
    
Installing and Configuring Elasticsearch (step 1)

    https://www.digitalocean.com/community/tutorials/how-to-install-elasticsearch-logstash-and-kibana-elastic-stack-on-ubuntu-18-04
    
Update search indexes

     bin/console fos:elastica:populate

Run tests

    bin/phpunit
