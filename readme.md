# FightBackCybercrime

FightBackCybercrime is a collection of simple scripts to fight back cybercrime.
At the moment only the generators for fake email, fake password and fake user credentials exist to generate e.g. fake database dumps or other fake data to take the piss out of ciminals on the internet who looking for stolen data. 

## How to use the generators
The generator for fake data is located in the **FakeDataGenerator** folder. 

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



## How to use the PhishingSiteFlooder
The flooder for phishing sites is located in **PhishingSiteFlooder** folder.

### Options
* delimiter - The delimiter between email and password in the used file with user credentials (default: :)
* use - The name of the file with user credentials (default: dump.txt)
* use_proxy - If you want to use the list of proxies (located in PhishingSiteFlooder/config/proxy_list.json) set this option to **1** otherwise set it to **0** (default: 0)
* target - The URL which is set in the action-attribute of the HTML-Form of the phishing site (default: '')
* browser - Which user agent should be used (chrome, IE, Firefox or custom) on each request (default: chrome)
* timeout - Timeout of each request in seconds (default: 10)
* http_post - Set this option to **1** when each request will be a post-request or to **0** when it should be a get-request (default: 1)

### Set Form-Fields 
You can set the fields of the data which will be send in the post_data.json file which is located in **PhishingSiteFlooder/config**

Default:
```
{
	"email": "{email}",
    "password": "{password}",
    "autologin": 1
}
```
It has the format:
```
{
	"FORM_INPUT_NAME": "VALUE"
}
```


Placeholder: 
* {email} - will be replaced with the email of the next user credentials which will be send
* {password} - will be replaced with the password of the next user credentials which will be send