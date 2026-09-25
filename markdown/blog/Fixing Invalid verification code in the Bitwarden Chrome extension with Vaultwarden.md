Recently, I ran into a frustrating issue whilst logging into my self-hosted Vaultwarden instance through the Bitwarden Chrome extension. After entering my password and the code from my authenticator app, I kept receiving the following error:

```text
Invalid verification code
```

I could log into the web vault and desktop app, and entering a fresh code in the extension didn't help.

Other's online had noted that uninstalling and reinstalling the extension had solved the issue for them. This wasn't working for me.

It turned out that Vaultwarden couldn't send a login notification email, and my configuration made that a requirement for logging in from a new device. Here's how I tracked it down and fixed it.

## My setup

I was running:

- Latest Vaultwarden in Docker
- A reverse proxy in front of it
- Bitwarden Chrome extension `2026.8.0` (although the version didn't end up mattering)
- Two-factor authentication using an authenticator app

## Finding the actual error

I checked that the extension was pointing at the correct self-hosted address, and that the server and browser clocks were in sync. I also investigated my reverse proxy config, but the login continued to fail.

A clue came from inspecting the extension's login request in Chrome DevTools.

To check this yourself:

1. Open the extension's login popup, right-click inside it, and choose `Inspect`.
2. In DevTools, open the `Network` tab.
3. Submit a fresh authenticator code in the extension.
4. Select the request to `/identity/connect/token` and inspect the `Response` tab.

My request returned:

```http
POST /identity/connect/token
400 Bad Request
```

The relevant part of the response was:

```json
{
  "message": "Could not send login notification email. Please contact your administrator."
}
```

Looking at the bundled source for the `2026.8.0` extension, the two-factor submission handler catches errors and displays the `invalidVerificationCode` message. In this case, that hid the email error returned by Vaultwarden.

## Why an email failure blocked login

In the Vaultwarden admin page, under **Settings → Advanced settings**, I had **Require new device emails** enabled. The corresponding configuration option is `REQUIRE_DEVICE_EMAIL`.

With mail enabled, Vaultwarden attempts to send a notification when a new device logs in. If that attempt fails and this option is enabled, Vaultwarden rejects the login. This behaviour is documented in the [Vaultwarden configuration template](https://github.com/dani-garcia/vaultwarden/blob/1.37.3/.env.template#L374-L376).

The [login code in Vaultwarden 1.37.3](https://github.com/dani-garcia/vaultwarden/blob/1.37.3/src/api/identity.rs#L465-L500) also shows that this email check happens **after the password and two-factor checks**. My authenticator code had passed; the notification email was the step failing afterwards.

This also explained why different clients could behave differently. An already registered client can avoid the new-device email check, whilst a newly installed extension triggers it. Being able to log into an existing client doesn't necessarily mean that outgoing email is working.

## The solution

I opened **Settings → SMTP Email Settings** in the Vaultwarden admin page and filled in the relevant sections.

For Gmail, the settings are:

| Setting | Value |
| --- | --- |
| Host | `smtp.gmail.com` |
| Secure SMTP | `force_tls` |
| Port | `465` |
| From address | Your sending mailbox address |
| Username | Your full Google / Google Workspace email address |
| Password | A Google app password |

The password should be an **app password**, generated through your Google account. Google provides [instructions for creating one](https://support.google.com/accounts/answer/185833), and the [Vaultwarden SMTP guide](https://github.com/dani-garcia/vaultwarden/wiki/SMTP-configuration#googlegmail) has the corresponding Gmail configuration.

After entering the missing SMTP credentials and saving the settings, I retried the extension login with a fresh authenticator code. It worked!

The admin page also has a **Send test email** option. Once you've saved your SMTP settings, use this to check email delivery before retrying the extension.

## Allowing login when email fails

There is another option: uncheck **Require new device emails** under **Advanced settings** and save.

This allows a login to continue if the notification email fails. It leaves two-factor authentication enabled, but means a new-device login can succeed without its notification email being sent.

My fix was to fill in the missing SMTP credentials. If you see the same verification-code error, check the failed request's response first: in this case, it pointed straight to the mail configuration.
