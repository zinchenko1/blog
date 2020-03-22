# Blog
create DB && update schema

    bin/console do:da:cr
    bin/console do:sc:up --force
 
 
load fixtures

    bin/console hautelook:fixtures:load
    
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
