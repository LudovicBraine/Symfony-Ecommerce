# Symfony-Ecommerce

# Start symfony server : 
- symfony serve

# Clear cache :
- symfony console clear:cache

# Create security user class:
- symfony console make:user

# Create security user entity:
- symfony console make:entity 

# Create new datebase with config from .env file: 
- symfony console doctrine:database:create

# Create migration
- symfony console make:migration

# Apply the migration
- symfony console doctrine:migrations:migrate

# Generate form
- symfony console make:form

# Change the default language for error message 
- Go in the file translation.yaml and change the variable "default_locale: en"

# Create a new authenticator 
- symfony console make:auth

An authenticator is a class with all the methods used by symfony to authenticate a user

# Show all the route available in your application 
- symfony console debug:router


TO DO : 

Redirection lors de l'inscription + message de validation 