# Setup Mail on Ubuntu using Postfix ready for PHP and GMail

Recently, I switching hosting to dedicated, and needed to update my server to allow it to send and receive email to all of my accounts, which are managed by Google Apps account. After a bit of research, I finally found out what I needed to do to allow this.

A few things I wanted:

* All emails which were forwarded to any email address on my domain should go to 1 main account
* I should be able to send emails using PHP
* All received emails should be managed by Google

#### Install a Mail Transfer Agent (MTA)

This allows us to send email from our account

```bash
sudo apt-get install postfix mailutils libsasl2-2 ca-certificates libsasl2-modules
```

You'll be taken through some setup.


* Set your host name to be your fully qualified domain name. (Mine is anujnair.com)
* When asked to input destinations, add your domain name to the front of the line. The final line looks like the following: mail.anujnair.com, localhost.localdomain, localhost
* Your relay host should be: `[smtp.gmail.com]:587`.
* IP address / networks can stay as is
* Recipient delimiter stays as a "+" without quotes
* Interfaces / Protocols stays as all

That's the basic config done. Now we have to add the extra GMail config:

```bash
vim /etc/postfix/main.cf

relayhost = [smtp.gmail.com]:587

smtp_sasl_auth_enable = yes
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_sasl_security_options = noanonymous
smtp_tls_policy_maps = hash:/etc/postfix/tls_policy

smtp_tls_CAfile = /etc/postfix/cacert.pem
smtp_use_tls = yes

smtp_generic_maps=hash:/etc/postfix/generic
```

If you already have a key for one of them, just update it to point to the correct place.


* Line 1 says we should send all email through GMail, with a specific port.
* Line 3 and 4 says, GMail requires us to authenticate, so lets enable it and point to the list of authenticated users (We'll be creating this in a sec)
* Line 5 says don't allow anonymous users (i.e. we need to log in)
* Line 6 is requested by GMail and says let's encrypt everything which goes through GMail
* Line 8 and 9 are our encryption settings
* Line 11 allows us to have all email forwarded to specific accounts to be redirected to the account we want. (See point 1 of what I wanted)

#### Authentication via GMail, Create Policy, Map users

First let's create a user:

```bash
sudo useradd -m -s /bin/bash admin
sudo passwd admin
```

So we can have a mail account for "admin@domain.com" - this should be the same account you log into GMail with.

Now let's authenticate the user so that postfix can send email via GMail using that account

```bash
vim /etc/postfix/sasl_passwd
```

Add:

```bash
[smtp.gmail.com]:587    admin@domain.com:PASSWORD
```

Obviously, replace admin@domain.com and PASSWORD with your own details. Save and Exit

Now we want to set the correct permissions for this file, and turn it into a DB file so that postfix can read it:

```bash
sudo chmod 400 /etc/postfix/sasl_passwd
sudo postmap /etc/postfix/sasl_passwd
```

You can remove the sasl_passwd file if you prefer after running postmap on it

Create a "generic" file, add a basic mapping, and the postmap into a file postfix can read. This is so internal mail can be redirected to the correct location:

```bash
vim /etc/postfix/generic

admin@localhost         admin@domain.com
root@domain             admin@domain.com

sudo postmap /etc/postfix/generic
```

Create your cert file:

```bash
cat /etc/ssl/certs/Thawte_Premium_Server_CA.pem | sudo tee -a /etc/postfix/cacert.pem
```

Finally, reload postfix config for changes to take effect:

```bash
sudo /etc/init.d/postfix restart
```

You can test to see if you're able to send mail by using the following command:

```bash
echo "Test to see if mail is working" | mail -s "Test email" you@example.com
```

Where you@example.com is the receiver of the email.

Log into your Google Apps account, go to Sent Mail and see if you can see the mail there.
If so, success!

#### Enable PHP to send email using Postfix

```bash
vim /etc/php5/apache2/php.ini
```

Find "send_mailpath", uncomment it and add the following command:

```bash
sendmail_path = "/usr/sbin/sendmail -t -i"
```

Save, and restart Apache.

```bash
/etc/init.d/apache2 restart
```

Add an alias to your root account, for your email account:

```bash
vim /etc/aliases
root    admin@domain.com
```

Test a send mail with PHP. You should receive it!

#### Receive emails to a Google Apps Account

Sign up for a [Google Apps account](http://admin.google.com) and verify you're the owner of your domain via a HTML file or a Meta tag.

Enable GMail for your users, and configure some other settings in the Gmail Advanced settings section - this is where you can apply a "catch all" to your domain, so that if someone emails an address which doesn't exist, it get's forwarded to another email instead.

Log into your DNS manager for your domain and update your MX records to point to Google's:
[Google MX Records](https://support.google.com/a/answer/174125?hl=en)

Send an email to the account to see if it is working. Unfortunately, the first one took an hour for me to receive, so it was difficult to see if I had configured it properly straight away!

Let me know how you get on

Thanks!
