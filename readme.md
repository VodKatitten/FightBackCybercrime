# FightBackCybercrime

FightBackCybercrime is a collection of simple scripts to fight back cybercrime.
At the moment only the generators for fake email, fake password and fake user credentials exist to generate e.g. fake database dumps or other fake data to take the piss out of ciminals on the internet who looking for stolen data. 

## How to use the generators
The generator for fake data is located in the **fakedatagenerator** folder. 

You can choose between
* EmailGenerator 
* PasswordGenerator
* UserCredentialsGenerator

For example:
```
	php generatedump.php --generator=UserCredentialsGenerator --amount=1000 
```

### Generator Options

**General Options**
* generator - The Name of the generator you want to use (default: EmailGenerator)
* amount - The amount of fake data you want to gernerate (default: 1)
* unique - Set this option to **0** to allow duplicate data and to **1** if you want only unique data (default: 1)
* output - The output file (default: dump.txt)

**PasswordGenerator Specific Options**
* min_password_length - The min length of each password (default: 6)
* hashed_password - Set this option to **1** to get passwords hashed and not in plain text (default: 0)
* hash_function - The hash-function which will be used when hashed_password is set to **1**. Can be **md5** or **sha1** (deafault: md5)

**UserCredentialsGenerator Specific Options**
* pattern - The pattern of each user credentials. Use {email} and {password} as placeholder. (default: {email}:{password})
* min_password_length - The min length of each password (default: 6)
* hashed_password - Set this option to **1** to get passwords hashed and not in plain text (default: 0)
* hash_function - The hash-function which will be used when hashed_password is set to **1**. Can be **md5** or **sha1** (deafault: md5)



