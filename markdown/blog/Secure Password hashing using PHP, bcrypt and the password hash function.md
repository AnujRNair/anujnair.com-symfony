# Secure Password hashing using PHP, bcrypt and the password hash function

PHP 5.5 gives us a secure way to hash and store passwords in our database, using a simple built in function called `password_hash`. We can then easily verify that password using it's sibling function `password_verify`.

Using the function is very simple, and incredibly secure. `password_hash` actually bakes in a lot of good practices when storing passwords, so we don't have to worry about doing it manually.

#### Good Practice: Cost Factor
The function allows a **cost factor** to increase the complexity of the hashing algorithm. As computers become more powerful over time, it will be important to make our hashing functions more complex so that faster computers cannot break the hash easily.

#### Good Practice: Salt a Password
The function bakes in a **salt**, further protecting us against *rainbow table* attacks. A rainbow table is a precomputed table of hashes of common passwords - if a malicious user obtains a copy of your database, they can compare all of your database hashes to the precompiled list of passwords in the rainbow table until they find a match. 

Adding a salt to a password means that the list of precompiled passwords is useless, as each password is hashed uniquely. 

Furthermore, if the salt is different per user (and per password), then that means two users with the same password will have different hashes stored in the database when their hash is stored.

#### How to use password_hash

It's very simple to use:
```php
$options = array('cost' => 10);
$hashed_password = password_hash("ThisIsMyPassword", PASSWORD_DEFAULT, $options);

//Outputs: \$2y\$10\$hZjugx0VE8uZryPWr9mMj.XEyD7qkfS7uxImRRxKERqGkfocg3.SS
```

This function has generated us a unique salt, and has set the optional parameter "cost" to be 10. It's all been hashed using our chosen algorithm and stored as a 60 character string. Everything we need to verify the password is stored in those 60 characters.

So then to decode:
```php
$verified = password_verify("ThisIsMyPassword", '\$2y\$10\$hZjugx0VE8uZryPWr9mMj.XEyD7qkfS7uxImRRxKERqGkfocg3.SS');

//Outputs: True
```

#### The 60 Character String
* **$2y$10$**hZjugx0VE8uZryPWr9mMj.XEyD7qkfS7uxImRRxKERqGkfocg3.SS - This denotes a crypt hash
* $2y$**10**$hZjugx0VE8uZryPWr9mMj.XEyD7qkfS7uxImRRxKERqGkfocg3.SS - This is the cost parameter we selected
* $2y$10$**hZjugx0VE8uZryPWr9mMj.**XEyD7qkfS7uxImRRxKERqGkfocg3.SS - The next 22 characters is the salt
* $2y$10$hZjugx0VE8uZryPWr9mMj.**XEyD7qkfS7uxImRRxKERqGkfocg3.SS** - Finally we have the hash of the password itself

More info can be found on the [PHP password_hash page](http://uk1.php.net/password_hash)
