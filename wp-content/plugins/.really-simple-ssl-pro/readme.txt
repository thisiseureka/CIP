=== Really Simple Security ===
Contributors: RogierLankhorst, markwolters, hesseldejong, vicocotea, marcelsanting, janwoostendorp, wimbraam
Donate link: https://www.paypal.me/reallysimplessl
Tags: security, ssl, 2fa, vulnerabilities, two factor
Requires at least: 5.9
License: GPL2
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 9.3.4
License URI: http://www.gnu.org/licenses/gpl-2.0.html
The easiest way to improve security! Leverage your SSL certificate and protect your website visitors.

== Description ==
Really Simple Security will automatically configure your website to use SSL to its fullest potential. Use extra security features to protect your website, and use our server health check to keep up-to-date.

== Features ==
* Easy SSL Migration: Takes your website to HTTPS in just one-click.
* Let's Encrypt: Install an SSL Certificate if your hosting provider supports manual installation.
* Server Health Check: Your server configuration is every bit as important for your website security.
* WordPress Hardening: Tweak your configuration and keep WordPress fortified and safe by tackling its weaknesses.
* Vulnerability Detection: Get notified when plugins, themes or core contain vulnerabilities and need appropriate action.

== Improve Security with Really Simple Security Pro ==
* The Mixed Content Scan & Fixer. Detect files that are requested over HTTP and fix it. Both Front- and Back-end.

=== Security Headers ===
These features mitigate the risk of clickjacking, cross-site-forgery attacks, stealing login credentials and malware among others.

* Independent of your Server Configuration, works on Apache, LiteSpeed, NGINX etc.
* Protect your website visitors with X-XSS Protection, X-Content-Type-Options, X-Frame-Options and Referrer Policy.
* Enable HTTP Strict Transport Security and configure your site for the HSTS Preload list.

=== Advanced Security ===
Isolate your website from unnecessary file loading and exchanges with third-parties. Fully control your website and minimize risk of manipulation.

* Designed for WordPress.
* Control third-parties with the Content Security Policy - including Learning Mode.
* Control browser features with the Permissions Policy e.g. geolocation, camera's and microphones.
* Isolate information exchange between other websites. Fully control in- and outbound of data.

=== Advanced Hardening ===
* Choose a custom login URL
* Rename and randomize your database prefix.
* Change the debug.log file location to a non-public folder
* Disable application passwords.
* Control admin creation
* Disable HTTP methods, reducing HTTP requests.

=== Vulnerability Measures ===
When a vulnerability is detected you will get notified accordingly. With Vulnerability Measures you can configure simple, but effective, measures to make sure a missed notification is not the end of the world.

* Force update: An update process will be tried multiple times, until it can be assumed development of a theme or plugin is abandoned. You will be notified during these steps.
* Quarantine: This extends the update process, which will run. When a plugin or theme can't be updated to solve a vulnerability, it will be disabled and deactivated. Use with caution!

== How does Really Simple Security's HTTPS migration work? ==
* The plugin will check for an existing SSL certificate. If you don't have one, you can generate one in the plugin. Depending on your hosting provider, the plugin can also install it for you or assist with instructions.
* If needed,  It will handle known issues WordPress has with SSL. An example might be that your website uses a loadbalancer, proxy or headers are not passed to detect a certificate.
* All incoming requests are redirected to HTTPS with a default 301 WordPress redirect. You can also choose a .htaccess redirect.
* The Site URL and Home URL are changed to HTTPS.
* Your insecure content is fixed by replacing all HTTP:// URLs with HTTPS://, except external hyperlinks, dynamically.
* Cookies with PHP are set securely by setting them with the HTTPOnly flag.

== Useful Links ==
* [Documentation](https://really-simple-ssl.com/knowledge-base-overview/)
* [SSL Definitions](https://really-simple-ssl.com/definitions/)
* [Translate Really Simple Security](https://translate.wordpress.org/projects/wp-plugins/really-simple-ssl)
* [Issues & pull requests](https://github.com/Really-Simple-Plugins/really-simple-ssl/issues)
* [Feature requests](https://github.com/Really-Simple-Plugins/really-simple-ssl)

== Love Really Simple Security? ==
Hopefully, this plugin saves you some time. If you want to support the continuing development of this plugin, please consider buying [Really Simple Security Pro](https://www.really-simple-ssl.com/pro/), which includes some excellent security features and premium support.

== About Really Simple Plugins ==
Other plugins developed by Really Simple Plugins: [Complianz](https://wordpress.org/plugins/complianz-gdpr/).

For generating SSL certificates, Really Simple Security uses the [le acme2 PHP](https://github.com/fbett/le-acme2-php/) Let's Encrypt client library, thanks to 'fbett' for providing it. Vulnerability Detection uses WP Vulnerability, an open-source initiative by Javier Casares. Want to join as a collaborator? We're on [GitHub](https://github.com/really-simple-plugins/really-simple-ssl) as well!

== Installation ==
To install this plugin:

1. Make a backup! See [our recommendations](https://really-simple-ssl.com/knowledge-base/backing-up-your-site/).
2. Install your SSL certificate or generate one with Really Simple Security.
3. Download the plugin.
4. Upload the plugin to the /wp-content/plugins/ directory.
5. You may need to log in again, so keep your credentials ready.
6. Go to "Plugins" in your WordPress admin, then click "Activate".
7. You will now see a notice asking you to enable SSL. Click it and log in again, if needed.

== Frequently Asked Questions ==
= Knowledge Base =
For more detailed explanations and documentation on redirect loops, Let's Encrypt, mixed content, errors, and so on, please search the [documentation](https://www.really-simple-ssl.com/knowledge-base/)

= Vulnerability Detection =
Vulnerability Detection is currently in Beta. It's stable, but needs scale to improve. If you can provide us with feedback, please do so!

= Mixed Content issues =
Most mixed content issues are caused by URLs in CSS or JS files. For detailed instructions on how to find mixed content read this [article](https://really-simple-ssl.com/knowledge-base/how-to-track-down-mixed-content-or-insecure-content/).

= Generating a Let's Encrypt SSL Certificate =
We added the possibility to generate a Free SSL Certificate with Let's Encrypt in our Really Simple Security Wizard. We have an updated list available for all possible integrations [here](https://really-simple-ssl.com/install-ssl-certificate/). Please leave feedback about another integration, incorrect information, or you need help.

= Redirect loop issues =
If you are experiencing redirect loops on your site, try these [instructions](https://really-simple-ssl.com/knowledge-base/my-website-is-in-a-redirect-loop/).

= Is the plugin multisite compatible? =
Yes. There is a dedicated network settings page where you can control settings for your entire network, at once.

= Uninstalling Really Simple Security =
The plugin checks your certificate before enabling, but if, for example, you migrated the site to a non-SSL environment, you might get locked out of the back-end.

If you can't deactivate, do not just remove the plugin folder to uninstall! Follow these [instructions](https://really-simple-ssl.com/knowledge-base/uninstall-websitebackend-not-accessible/) instead.

== Changelog ==
= 9.3.4 =
* improvement: Added a better handling for Ip subnets cidr in Firewall.
* Fix: Fixed an issue where TOTP codes were not properly validated.
* Fix: Prevent Limit Login Attempts from triggering during WordPress Cron to improve compatibility with other plugins.
* Fix: Fixed an issue where Eventlog was registering a temp block even when the IP was not blocked or whitelisted.
* Fix: Fixed an issue where Limit Login Attempts, temporary blocks were not correctly cleared.

= 9.3.3 =
* Removed: File Change Detection
* Improvement: Added multiple WP-CLI commands to better align with recent plugin features
* Improvement: Added multiple improvements to File Permissions Check
* Improvement: Added support for custom/multiple roles in Two Factor Authentication
* Fix: Fixed a rare case where the autoloader could cause a Fatal Error due to loading twice

= 9.3.2.1 =
* Fix: Properly handle unknown plugins in upgrade requests, preventing unintended behavior.

= 9.3.2 =
* Improvement: Added filters to customize Let's Encrypt Wizard behaviour
* Fix: Removed default checkbox behaviour from configuration settings.
* Fix: Fixed an issue where a loop occurred when password protected page was created and custom-login enabled.
* Fix: handle multiple tooltip reasons for disabled select fields
* Fix: Preventing double lines to occur in the advanced-headers.php file

= 9.3.1 =
* Fix: In some occasions an error could occur while logging out when the custom login-url was enabled.

= 9.3.0 =
* New: Create manual CSP entries.
* Performance: generating backup codes only when totp is being configured.
* Improvement: Not able to use email needed functions when email is not yet verified.
* Improvement: Added better text to verify the action of resending 2fa email.
* Improvement: remove email token when login succeeds.
* Improvement: Updated LLA database structure for MySQL 5.7 compatibility.
* Fix: Improved user role handling to prevent undefined array key in 2FA.
* Fix: All instruction links are now correct.
* Fix: Undefined array key "m" when showing vulnerability details.
* Fix: Prevent errors when downgrading to free.
* Fix: Compatibility between 2FA and JetPack “Log in using WordPress.com account” setting

= 9.2.0 =
* Improvement: limited the number of header test requests for CSP learning mode
* Improvement: If 404 errors are detected on the site’s homepage, we prevent the 404 blocker from being activated to prevent unwanted lockouts.
* Improvement: Two-Factor Authentication now works with custom login screens.
* Improvement: Updated endpoint version.
* Improvement: disabling file permissions check will now correctly reset the file permissions list.
* Fix: In some situations, header settings were lost after temporarily deactivating the plugin.
* Fix: In some cases the review notice was not properly dismissible.
* Fix: Added nonce check to certificate re-check button.
* New: File change detection. This feature will keep track of suspicious file changes outside of regular updates.

= 9.1.4 =
* Improvement: do not track 404's for logged in users
* Improvement: implemented the rsssl_wpconfig_path filter in all wp-config functions
* Improvement: When an ip is blocked within the admin panel the username now won’t be blocked after a login
* Improvement: Faster onboarding completion after clicking Finish button
* Improvement: CSS. Shields in user interface on datatables are no longer cut off
* Fix: 404 blocker will now write the rules to the advanced-headers.php file immediately
* Fix: fixed the dropdown height for the custom login URL 404 page picker
* Fix: LLA Username block did not expire
* Fix: stop showing notice when firewall is enabled.
* Fix: Reset 2FA attempt counter & notice after successful TOTP login
* Fix: 2fa sanitising produced a failed login.
* Fix: Create initial database entries for 'Enforce frequent password change' in batches, to prevent memory issues on sites with many users

= 9.1.3 =
* Improvement: Width Vulnerabilities -> configuration
* Improvement: 2Fa lockout notice
* Improvement: catch use of short init in advanced-headers file
* Improvement: string improvements and translator comments
* Improvement: Bitnami support for rsssl_find_wordpress_base_path()
* Improvement: integrate Site health notifications with Solid Security
* Improvement: Enhanced random password generation in Rename Admin User feature
* Improvement: Always return string in wpconfig_path() function
* Improvement: Removes configuration options for a user in edit user.
* Fix: Remove duplicate site URL.
* Fix: ensure rsssl_sanitize_uri_value() function always returns a string, to prevent errors.
* Fix: multisite users who have enabled roles couldn’t use the 2fa if an other role than theirs has been forced.
* Fix: The ‘Skip Onboarding’ button presented an undefined page after selecting the email method as an option.

= 9.1.2 =
* Improvement: improved error handling

= 9.1.1.1 =
* November 5th, 2024
* Fix: fixed a bug where the 2FA grace period was kept active after a reset

= 9.1.1 =
* October 30th, 2024
* Improvement: setting a rsssl-safe-mode.lock file now also enables safe mode and deactivates the Firewall, 2FA and LLA for debugging purposes.
* Improvement: update to system status
* Improvement: textual changes
* Improvement: Updated instructions URLs
* Improvement: Changed site health notices from critical to recommended
* Improvement: dropped obsolete react library
* Improvement: add a site URL to email warnings regarding suspicious admin account creation
* Improvement: Additional feedback if 2FA grace period has expired
* Improvement: Users for which 2FA is enforced will now receive an email reminder three days before the grace period expires.
* Fix: email login button not working
* Fix: onboarding and login button did not save the correct configuration.

= 9.1.0 =
* October 22nd, 2024
* New: User Agent Blocking.
* Improvement: add captcha URL's to default Content Security Policy rules.
* Improvement: Auto-removal of Free plugin and translation files when Pro is activated.
* Improvement: Resize logo in 2FA e-mails.
* Improvement: Allow scanning for security headers via http://scan.really-simple-ssl.com  with one click
* Improvement: Exclude MPDF library in Complianz from Permission Detection.
* Improvement: Remove unnecessary rsssl_update_option calls.
* Fix: prevent potential errors with login feedback..
* Fix: Catch type error when $transients is not an array.
* Fix: Custom Login URL compatibility with password protected pages.
* Fix: Prevent potential errors when adding IP addresses to the Firewall allowlist.

= 9.0.2.=
* Fix: issue with deactivating 2fa

= 9.0.1 =
* September 16th, 2024
* Fix: Prevent duplicate DB queries on Firewall settings page
* Fix: Instructions URL in the Firewall settings.
* Fix: Block a custom login URL bypass on Multisite
* Fix: Catch situation where an entry in the MaxMind DB returns null
* Fix: Fixed incorrect instructions URL
* Fix: Dismiss notice when dashboard is viewed
* Improvement: As the X-Frame-Options is deprecated and replaced by frame ancestors, we drop the header as recommendation.

= 9.0.0 =
* September 6th, 2024
* Security: Login url bypass on multisite
* Security: 2fa bypass fix, props Julio Potier @SecuPress
* Fix: Let's Encrypt returning an old certificate on auto-renewed certificates
* Fix: Multiselect in Limit login attempts
* Improvement: Better UX sync between CSP and frame ancestors
* Improvement: save and continue in vulnerabilities overview not working correctly

= 8.3.0.1 =
* Fix: Issues with the decryption model

= 8.3.0 =
* August 12th, 2024
* Feature: Password security scan. This feature scans your users for weak passwords, and allows you to enforce non-compromised passwords.
* Fix: Fixed some strings that were not translatable. This has been resolved.
* Fix: Premium support link did not work. Now links to the correct page.
* Improvement: Disable the cron schedules on deactivation.
* Fix: Links in emails were sometimes not correct. This has been fixed.
* Fix: Fatal error on permission detection. This has been resolved.
* Improvement: Custom header for the license checks for better compatibility with some hosting environments.
* Improvement: Added option to disable X-powered-by header.
* Improvement: New improved encryption method for some settings.

= 8.2.6.1 =
* Multisite fix.

= 8.2.6 =
* July 30th, 2024
* Fix: lower number of rewrites of the advanced-headers.php and advanced-headers-test.php file when in the Really Simple Security dashboard

= 8.2.5 =
* July 25th, 2024
* New: 404 blocker and firewall, auto blocking IP's that generate 404 errors
* New: File Change detection, get notified when files are changed
* Fix: possibility to bypass hide login url with certain internal URLs.
* Fix: setting for hide remember me missing
* Fix: code for two modules loaded even if not enabled.
* Improvement: added filter to write to other file than the wp-config.php
* Improvement: added option to disable Limit Login Attempts with a constant, if the admin is locked out.
* Improvement: added a constant which prevents inserting the .htaccess auto prepend rule
* Improvement: added 'Really Simple Security' to admin notices so users can see where the notice comes from, props @a4jpcom

= 8.2.4 =
* June 20th, 2024
* Fix: documentation links to website broken
* Improvement: some text changes in helptexts
* Improvement: x-frame options synced with frame-ancestors
* Improvement: new structure to upgrade database tables
* Improvement: change login url compatibility with other plugin

= 8.2.3 =
* June 6th, 2024
* Improvement: dropdown in onboarding not entirely visible
* Improvement: Styling of locked XML RPC overview
* Fix: Reset user for two step email authentication from the settings page
* Fix: Not loading cookie expiration change
* Fix: Visual Composer compatibility icw Enforce Strong Password
* Fix: Multiple CloudFlare detected notices in onboarding
* Fix: Checkbox position in onboarding

= 8.2.2 =
* May 16th, 2024
* Fix: WP Rocket compatibility causing an issue when advanced-headers.php does not exist

= 8.2.1 =
* May 16th, 2024
* Fix: upgrade advanced-headers.php file to allow early inclusion of the file. The ABSPATH defined check causes in issue for early inclusion, so must be removed.

= 8.2.0 =
* May 7th, 2024
* New: detection of non-recommended permissions on files
* New: Configure region restrictions for your site
* Improvement: Textual change on premium overlay
* Improvement: Upgraded minimum required PHP version to 7.4
* Improvement: compatibility with Bitnami
* Improvement: compatibility of Limit Login Attempts with Woocommerce
* Improvement: remove duplicate X-Really-Simple-SSL-Test from advanced-headers-test.php
* Improvement: clear notice about .htaccess writable if do_not_edit_htaccess is enabled
* Fix: upgrade from <6.0 version to >8.0 causing a fatal error
* Fix: URL to details of detected vulnerabilities was incorrect

= 8.1.0 =
* Improvement: some string corrections
* Fix: show 'self' as default in Frame Ancestors
* Improvement: catch not existing rsssl_version_compare
* Improvement: check for openSSL module existence
* Improvement: set default empty array for options, for legacy upgrades
* Improvement: disable custom login URL when plain permalinks are enabled
* New: Limit Login Attempts Captcha integration
* Improvement: drop renamed folder notice, not needed anymore
* Improvement: enable advanced headers in onboarding
* Improvement: is_object check in updater

= 8.0.1 =
* Fix: enable 2FA during onboarding when not selected by user
* Improvement: better CSP defaults
* Fix: on upgrade to pro, free settings were cleared if "clear settings on deactivation" was enabled
* Fix: catch several array key not existing errors

= 8.0.0 =
* New: hide remember me checkbox
* New: extend blocking of malicious admin creation to multisite
* Improvement: drop prefetch-src from Content Security Policy
* Improvement: disable two-fa when login protection is disabled

= 7.2.8 =
* Fix: clear cron schedules on deactivation
* Improvement: translations update
* Notice: inform users about upcoming merge of free and pro plugin, not action needed, everything will be handled automatically

= 7.2.7 =
* Improvement: added integration with FlyingPress and Fastest Cache
* Improvement: fix exiting a filter, causing a compatibility issue with BuddyPress

= 7.2.6 =
* Improvement: text changes
* Improvement: css on login error message
* Improvement: header detection improved by always checking the last url in the redirect chain
* New: Added option to limit login cookie expiration time
* Fix: custom 404 pages i.c.w. custom login url

= 7.2.5 =
* Fix: IP detection header order
* Fix: table creation on activation of LLA module

= 7.2.4 =
* Fix: PHP warning in Password Security module
* Fix: change login url feature not working with password protected pages
* Improvement: move database table creation to Limit Login Attempts module
* Improvement: prevent php error caused by debug.log file hardening feature

= 7.2.3 =
* Fix: CSP data not showing in datatable

= 7.2.2 =
* Improvement: improved check for PharData class

= 7.2.1 =
* Fix: Config for CSP preventing Learning mode from completing
* Fix: datatable styling
* Fix: using deactivate_https with wp-cli did not remove htaccess rules 
* Improvement: add query parameter to enforce email verification &rsssl_force_verification
* Improvement: css for check certificate manually button

= 7.2.0 =
* Fix: changed link to article
* Fix: remove flags .js file which was added twice, props @adamainsworth
* Fix: typo in missing advanced-headers.php notice
* Improvement: catch php warning when script src is empty when using hide wp version, props @chris-yau
* Improvement: new save & continue feedback
* Improvement: datatable styling
* Improvement: new react based modal
* Improvement: menu re-structured
* Improvement: re-check vulnerability status after core update
* Improvement: link in the email security notification to the vulnerability page instead of to a general explanation

= 7.1.3 =
* October 11th 2023
* Fix: React ErrorBoundary preventing Let's Encrypt generation to complete.

= 7.1.2 =
* October 6th 2023
* Fix: hook change in integrations loader causing modules not to load. props @rami5342

= 7.1.1 =
* October 5th 2023
* Fix: incorrect function usage, props @heutger

= 7.1.0 =
* October 4th 2023
* Improvement: detection if advanced-headers.php file is running

= 7.0.9 =
* September 5th 2023
* Improvement: typo update word
* Improvement: translatability in several strings.

= 7.0.8 =
* August 8th 2023
* Improvement: WordPress tested up to 6.3
* Improvement: improve file existence check json
* Fix: handling of legacy options in php 8.1
* Fix: count remaining tasks

= 7.0.7 =
* July 25th 2023
* Improvement: modal icon placement in wizard on smaller screens
* Improvement: expire cached detected headers five minutes after saving the settings
* Fix: handling of legacy options in php 8.1
* Fix: prevent issues with CloudFlare when submitting support form from within the plugin
* Fix: translations singular/plural for japanese translations @maboroshin

= 7.0.6 =
* July 4th 2023
* Improvement: support custom wp-content directory in advanced-headers.php
* Improvement: prevent usage of subdirectories in custom login url
* Fix: translations not loading for chunked react components
* Improvement: add option to manually re-check vulnerabilities '&rsssl_check_vulnerabilities', props @fawp

= 7.0.5 =
* Fix: some users with a non www site reporting issues on the login page over http://www, due to the changes in the wp redirect. Reverting to the old method. props @pedalnorth, @mossifer.

= 7.0.4 =
* June 14th 2023
* Improvement: notice informing about the new free vulnerability detection feature
* Improvement: improved the php redirect method
* Improvement: make the wp-config.php not writable notice dismissable
* Fix: feedback on hardening features enable action not showing as enabled, props @rtpHarry

= 7.0.3 =
* Fix: fix false positives on some plugins
* Improvement: vulnerability notifications in site health, if notifications are enabled.

= 7.0.2 =
* Improvement: improve matching precision on plugins with vulnerabilities.

= 7.0.1 =
* Fix: When the Rest API is not available, the ajax fallback should kick in, which didn't work correctly in 7.0. props @justaniceguy

= 7.0.0 =
* New: Vulnerability Detection is in Beta - [Read more](https://really-simple-ssl.com/vulnerability-detection/) or [Get Started](https://really-simple-ssl.com/instructions/about-vulnerabilities/)
* Improvement: move onboarding rest api to do_action rest_route
* Improvement: catch several edge situations in SSL Labs api
* Improvement: SSL Labs block responsiveness
* Improvement: more robust handling of wp-config.php detection

= 6.3.0 =
* Improvement: added support for the new Let's Encrypt staging environment

= 6.2.5 =
* Improvement: add warning alert option
* Fix: capability mismatch in multisite. props @verkkovaraani

= 6.2.4 =
* Improvement: optionally enable notification emails in onboarding wizard
* Improvement: onboarding styling
* Fix: catch non array value from notices array, props @kenrichman
* Fix: typo in documenation link, props @bookman53

= 6.2.3 =
* Improvement: Changed Back-end react to functional components
* Improvement: multisite notice should link to network admin page
* Improvement: detect existing CAA records to check Let's Encrypt compatibility
* Improvement: tested up to wp 6.2
* Improvement: UX improvement learning mode

= 6.2.2 =
* Fix: capability mismatch for a non administrator in multisite admin, props @jg-visual

= 6.2.1 =
* Fix: race condition when activating SSL through wp-cli, because of upgrade script
* Fix: missing disabled state in textarea and checkboxes
* Fix: some strings not translatable
* Fix: Let's Encrypt renewal with add on
* Improvement: permissions check re-structuring
* Improvement: notice on subsite within multisite environment about wildcard updated

= 6.2.0 =
* New: optional email notifications on advanced settings
* Improvement: added tooltips
* Improvement: added warnings for .htaccess redirect
* Improvement: don't send user email change on renaming admin user, as the email doesn't actually change
* Improvement: Use BASEPATH only for wp-load.php, so symlinked folders will load based on ABSPATH
* Improvement: Improved support for environments where Rest API is blocked

= 6.1.1 =
* Fix: WP CLI not completing SSL when because site_has_ssl option is not set if website has not been visited before, props @oolongm
* Improvement: prevent 'undefined' status showing up in api calls on settings page
* Improvement: show notice if users are using an <2.0 Let's Encrypt shell add-on which is not compatible with 6.0

= 6.1.0 =
* Improvement: some UX changes
* Improvement: Limit number of notices in the dashboard
* Improvement: load rest api request url over https if website is loaded over https
* Fix: empty menu item visible in Let's Encrypt menu

= 6.0.14 =
* Fix: settings page when using plain permalinks, props @mvsitecreator, props @doug2son

= 6.0.13 =
* Improvement: improve method of dropping empty menu items in settings dashboard
* Improvement: dynamic links in auto installer
* Improvement: Let's Encrypt Auto installer not working correctly, props @mirkolofio
* Improvement: change rest_api method to core wp apiFetch()
* Improvement: scroll highlighted setting into view after clicking "fix" on a task
* Improvement: run http method test in batches, and set a default, to prevent possibility of curl timeouts on systems with CURL issues
* Improvement: clean up code-execution.php file after test, props @spinhead
* Improvement: give notification if 'DISABLE_FILE_EDITING' is set to false in the wp-config.php props @joeri1977
* Improvement: drop some unnecessary translations
* Improvement: set better default, and change transients to option for more persistent behavior in wp version test, props @photomaldives
* Fix: CSS for blue labels in progress dashboard below 1080px
* Fix: WPCLI SSL activation not working due to capability checks, props @oolongm
* Fix: catch invalid account error in Let's Encrypt generation, props @bugsjr
* Fix: do not block user enumeration for gutenberg

= 6.0.12 =
* Fix: on multisite, the test for users with admin username did not use the correct prefix, $wpdb->base_prefix, props @jg-visual
* Improvement: allow submenu in back-end react application
* Improvement: Skip value update when no change has been made
* Improvement: no redirect on dismiss of admin notice, props @gangesh, @rtpHarry, @dumel
* Improvement: remove obsolete warning
* Improvement: qtranslate support on settings page

= 6.0.11 =
* Fix: on some environments, the HTTP_X_WP_NONCE is not available in the code, changed logged in check to accomodate such environments
* Fix: dismiss on admin notices not immediately dismissing, requiring dismiss through dashboard, props @dumel

= 6.0.10 =
* Fix: Apache 2.4 support for the block code execution in the uploads directory hardening feature, props @overlake
* Fix: When used with Varnish cache, Rest API get requests were cached, causing the settings page not to update.
* Fix: Ensure manage_security capability for users upgraded from versions before introduction of this capability
* Fix: allow for custom rest api prefixes, props @coderevolution
* Fix: bug in Let's Encrypt generation with DNS verification: saving of 'disable_ocsp' setting, create_bundle_or_renew action with quotes
* Fix: change REST API response method to prevent script errors on environments with PHP warnings and errors, causing blank settings page
* Improvement: Simplify user enumeration test
* Improvement: catch unexpected response in SSL Labs object
* Improvement: z-index on on boarding modal on smaller screen sizes, props @rtpHarry
* Improvement: hide username field if no admin username is present, props @rtpHarry

= 6.0.9 =
* Fix: incorrectly disabled email field in Let's Encrypt wizard, props @cburgess
* Improvement: on rename admin user, catch existing username, and strange characters
* Improvement: catch openBaseDir restriction in cpanel detection function, props @alofnur
* Improvement: remove 6.0 update notices on subsites in a multisite network, props @wpcoderca, (@collizo4sky

= 6.0.8 =
* Improvement: Lets Encrypt wizard CSS styling
* Improvement: re-add link to article about Let's Encrypt so users can easily find the URL
* Improvement: let user choose a new username when selecting "rename admin user"

= 6.0.7 =
* Fix: restrict conditions in which htaccess rewrite runs, preventing conflicts with other rewriting plugins

= 6.0.6 =
* Fix: drop upgrade of .htaccess file in upgrade script

= 6.0.5 =
* Fix: race condition in .htaccess update script, where multiple updates simultaneously caused issues with the .htaccess file

= 6.0.4 =
* Fix: using the .htaccess redirect in combination with the block code execution in uploads causes an issue in the .htaccess redirect
* Fix: deactivating Really Simple Security does not completely remove the wp-config.php fixes, causing errors, props @minalukic812

= 6.0.3 =
* Fix: Rest Optimizer causing other plugins to deactivate when recommended plugins were activated, props @sardelich

= 6.0.2 =
* Fix: do not show WP_DEBUG_DISPLAY notice if WP_DEBUG is false, props @janv01
* Fix: empty cron schedule, props @gilvansilvabr
* Improvement: several typo's and string improvements
* Fix: auto installer used function not defined yet
* Fix: rest api optimizer causing an error in some cases @giorgos93

= 6.0.1 =
* Fix translations not loading for scripts

= 6.0.0 =
* Tested up to WordPress 6.1.0
* Improvement: User Interface
* New: Server Health Check - powered by SSLLabs
* New: WordPress Hardening Features

= 5.3.0 =
* Improvement: added PHP 8.1 compatibility
* Improvement: updated .htaccess redirect comment
* New: add installation helper
* Improvement: is_writable check in Let's Encrypt
* Improvement: Catch not set subject alternative and common names in cert

= 5.2.3 =
* Bumped tested up to 5.9

= 5.2.2 =
* Improvement: change text about Google Analytics for a more broader application
* Improvement: prevent duplicate notice
* Improvement: better feedback on failed SSL detection
* Improvement: .htaccess redirect detection with preg_match

= 5.2.1 =
* Improvement: changed text on security headers feedback
* Improvement: some resources were not loaded minified on the back-end
* Improvement: dropped one line from tips&tricks to ensure it all fits when translated
* Improvement: improve feedback on the Let's Encrypt terms & conditions checkbox being required
* Improvement: improve feedback on chosen hosting company, if SSL is already available, or not available at all.
* Improvement: updated wp-config needs fixes notice
* Improvement: RTL css update

= 5.2.0 =
* Improvement: updated tips & tricks with Let's Encrypt and Cross-Origin resource policy articles
* Improvement: updated setting slider styling
* Improvement: updated WP Config not writable notice and article
* Improvement: recommended headers check now uses cURL for header detection

= 5.1.3 =
* Improvement: auto rename force-deactivate.php back to .txt after running
* Improvement: auto flush caches of popular caching plugins
* Improvement: "dismiss all notices" option on multisite network settings menu
* Improvement: add option to disable OCSP stapling in the Let's Encrypt certificate generation, instead of doing this automatically only
* Improvement: added high contrast option to settings for better WCAG compatibility
* Improvement: link in "install manually" on Let's Encrypt certificate renewal should point to certificate download instead of hosting installation url.
* Improvement: recommend headers check now uses cURL for header detection

= 5.1.2 =
* Improvement: remove one recommendation from the activate ssl notice, to keep it clean
* Improvement: continue instead of stop when no auto installation possible
* Improvement: add reset option to Let's Encrypt generation wizard, to allow fully resetting Lets Encrypt
* Improvement: saved settings feedback

= 5.1.1 =
* Improvement: color of progress bar
* Improvement: make notice about not protected directories dismissible, in case the Let's Encrypt certificate generation process is not completed.
* Improvement: catch not existing fsock open function, props @sitesandsearch
* Improvement: slide out animation on task dismissal

= 5.1.0 =
* Improvement: clear keys directory only clearing files
* Improvement: added WP Version and PHP version to system status export
* Improvement: check for duplicate SSL plugins
* Improvement: Catch file writing error in Let's Encrypt setup where the custom_error_handler wasn't able to catch the error successfully
* Improvement: new hosting providers added Let's Encrypt

= 5.0.10 =
* Fix: Let's Encrypt SSL certificate download only possible through copy option, and not through downloading the file

= 5.0.9 =
* Improvement: make sure plus one notices also get re-counted outside the settings page after cache clears
* Fix: On Multisite a Let's Encrypt specific filter was loaded unnecessarily
* Improvement: also skip challenge directory check in the ACME library, when the user has selected the skip directory check option

= 5.0.8 =
* Improvement: move localhost test before subfolder test as the localhost warning won't show otherwise on most localhost setups
* Fix: when using the shell add-on, the action for a failed cpanel installation should be "skip" instead of "stop"
* Fix: drop obsolete arguments in the cron_renew_installation function, props @chulainna

= 5.0.7 =
* Fix: check for file existence in has_well_known_needle function, props @libertylink
* Fix: fixed a timeout on SSL settings page on OVH due to failed port check
* Improvement: allow SSL generation when a valid certificate has been found

= 5.0.6 =
* Fix: rsssl_server class not loaded on cron

= 5.0.5 =
* Fix: cron job for Let's Encrypt generation not loading correct classes

= 5.0.4 =
* Fix: php notices when in SSL certificate generation mode, due to wrong class usage
* Improvement: Refresh option in case the certificate was just installed.
* Improvement: catch invalid order during SSL certificate generation

= 5.0.3 =
* Improvement: Install SSL notice dismissible, which allows for SSL already installed situations and not detected.
* Fix: WordPress basepath detection in force deactivate function and in system status
* Fix: not dismissible urgent notices were still dismissible
* Improvement: add-on compatibility check
* Improvement: due to bug in Plesk, a "no Order for ID" error could be returned. A second attempt will now automatically be made on the Let's Encrypt SSL certificate generation
* Improvement: allow overriding of SSL detection of SSL was not detected as valid

= 5.0.2 =
* Improvement: remove some files to prevent false positive warnings from windows defender
* Improvement: move variable in cpanel integration to prevent php warnings.

= 5.0.1 =
* Fix: obsolete variable in function causing php errors on some configurations.

= 5.0.0 =
* New: Let's Encrypt SSL certificate generation

= 4.0.15 =
* Fix: non hierarchical structured form elements in the template could cause settings not to get saved in some configurations.

= 4.0.14 =
* Improvement: when WordPress incorrectly reports that SSL is not possible, correct the resulting site health notice.
* Improvement: don't show the secure cookies notice on subsites of a multisite installation. Show on the network dashboard instead.

= 4.0.13 =
* Fixed notice about wp config.php not writable notice even when httpOnly cookie settings already written.

= 4.0.12 =
* Added secure cookies
* Improved Right-To-Left text support

= 4.0.11 =
* Fixed a bug where users with an older Pro version could get a fatal error call to private function

= 4.0.10 =
* Improvement: enable WordPess redirect, disable .htaccess redirect for WP Engine users.
* Improvement: adjust for dropped .htaccess support in WP Engine

= 4.0.9 =
* Improvement: some small CSS improvements in the dashboard
* Fix: Switched wp_insert_site hook to wp_initialize_site props @masumm17
* Fix: multisite: after switching from networkwide to per site, or vice versa, the completed notice didn't go away.

= 4.0.8 =
* Fix: fixed a bug in the get_certinfo() function where an URL with a double prefix could be checked
* Improvement: Content Security Policy compatibility

= 4.0.7 =
* Fix: catch not set certificate info in case of empty array when no certificate is available
* Fix: minor CSS fixes

= 4.0.6 =
* Improvement: Improved responsive css for tabbed menu
* Improvement: PHP 8 compatibility
* Improvement: Added links to help article for not writable notices
* Improvement: notice when plugin folder had been renamed
* Improvement: increase php minimum required to 5.6

= 4.0.5 =
* Backward compatibility for <4.0 premium versions

= 4.0.4 =
* Added Really Simple Plugins logo
* Fix: enable link in task for multisite redirected to subsite
* Fix: exclude plus one count from admin notices

= 4.0.3 =
* Fix: sitehealth dismiss not working correctly, props @doffine

= 4.0.2 =
* Fix: not translatable string, props @kebbet
* Improvement: clear admin notices cache when SSL activated or reloaded over https
* Fix: removed javascript regex not supported by Safari, causing the dismiss not to work on the progress block
* Improvement: option to dismiss site health notices in the settings

= 4.0.1 =
* Fix: fixed a bug where switching between the WP/.htaccess redirect caused a percentage switch
* No SSL detected notice is cached after enabling SSL. props @memery2020
* Fix: deactivating before SSL was activated on a site which was already SSL would revert to http.

= 4.0.0 =
* New user interface
* Fix: transient stored with 'WEEK_IN_SECONDS' as string instead of constant
* Improvement: notices dashboard, with dismissable notices
* Improvement: improved naming of settings, and instructions
* Improvement: articles in tips & tricks section

= 3.3.4 =
* Fix: prefix review notice dismiss to prevent conflicts with other plugins

= 3.3.3 =
* Dismiss review notice now uses get variable to dismiss it

= 3.3.2 =
* Added a notice when using Divi theme with a link to knowledge base instructions
* Fixed a CSS issue where the active tab in setting didn't have an active color
* Added an additional option to dismiss the review notice
* Removed review notice capability check
* Fixed a bug on multisite where a plusone was shown when it should only shown on non-multisite
* Added prefix to uses_elementor() function and added checks if function_exists

= 3.3.1 =
* Fixed a typo in the backup link
* Added instructions on how to add a free SSL certificate

= 3.3 =
* Updated SSL activated notice
* Updated readme

= 3.2.9 =
* Fixed a bug where the redirect to settings page would abort SSL activation, not writing the wp-config fix on new installs
* Fixed typo in force-deactivate notice

= 3.2.8 =
* Added redirect to settings page after activating SSL
* Improved dashboard SSL certificate check by using the is_valid check from rsssl_certificate instead of relying on site_has_ssl
* Updated activation notice
* Updated settings page sidebar styling and links

= 3.2.7 =
* Updated switch_to_blog function in to a backwards compatible version for older WP installations
* Updated review notice
* Improved .htaccess not writeable notice for Bitnami installations to show htaccess.conf location
* Updated green lock to secure lock text
* Removed border for dashboard sidebar button
* Activate some security headers by default when pro is enabled

= 3.2.6 =
* Optimized plusone count function
* Disabled Javascript redirect by default
* Fixed a bug in the setting highlight function where an undefined setting name could cause a warning

= 3.2.5 =
* Fixed typo in trace_log() function call

= 3.2.4 =
* Improved and added dashboard notices
* Improved debug logging
* Added option to dismiss all Really Simple Security notices
* Fixed a bug where other plugins buttons had their style reset

= 3.2.3 =
* Added right-to-left text support
* Show a plusone behind the notice that generated it
* Added a dismiss text link to dismissible notices
* Added highlighting to .htaccess redirect option after clicking on dashboard link
* Added option to dismiss all notices
* Added site health notice

= 3.2.2 =
* Fix: some single sites setup were having issues with multisite files being included.

= 3.2.1 =
* Fix: error in regex, cause a fatal error in cases where a plus one already was showing in the settings menu

= 3.2 =
* Added update counter to Settings/SSL menu item if recommended settings aren't enabled yet
* Added WP-CLI support
* Tweak: made some dashboard items dismissible
* Tweak: added link on multisite networkwide activation notice to switch function hook to fix conversions hanging on 0%
* Tweak: required WordPress version now 4.6 because of get_networks() version

= 3.1.5 =
* Fix: fixed a bug where having an open_basedir defined showed PHP warnings when using htaccess.conf

= 3.1.4 =
* Tweak: added support for Bitnami/AWS htaccess.conf file
* Tweak: multisite blog count now only counts public sites
* Tweak: changed rewrite rules flush time to 1-5 minutes
* Tweak: improved multisite site count

= 3.1.3 =
* Tweak: no longer shows notices on Gutenberg edit screens
* Tweak: updated Google Analytics with link to SSL settings page
* Fix: multisite blog count now only counts public sites

= 3.1.2 =
* Tweak: added cool checkboxes
* Tweak: .well-known/acme-challenge/ is excluded from .htaccess https:// redirect
* Tweak: implemented transients for functions that use curl/wp_remote_get()
* Tweak: improved mixed content fixer detection notifications
* Tweak: removed review notice for multisite

= 3.1.1 =
* Fix: Multisite network wide activation/deactivation cron not saving settings because user capability not set this early in the process.

= 3.1 =
* Fix: fixed a bug in certificate detection
* Tweak: added HTTP_X_PROTO as supported header
* Tweak: split HTTP_X_FORWARDED_SSL into a variation which can be either '1' or 'on'
* Tweak: improved certificate detection by stripping domains of subfolders.
* Tweak: Multisite bulk SSL activation now chunked in 200 site blocks, to prevent time out issues on large multisite networks.
* Tweak: a 'leave review' notice for new free users

= 3.0.5 =
* Fix: untranslatable string made translatable.

= 3.0.4 =
* Fix: removed anonymous function to maintain PHP 5.2 compatibility.

= 3.0.3 =
* Tweak: mixed content fixer will no longer fire on XML content
* Tweak: network menu on subsites now always shows to Super Admins
* Tweak: flush rewrite rules upon activation is delayed by one minute to reduce server load

= 3.0.2 =
* Fix: fixed an image containing uppercase characters, which can lead to the image not showing on some servers.
* Fix: fixed an issue where the 'data-rsssl=1' marker wasn't inserted when the <body> tag was empty.

= 3.0.1 =
* Tweak: Add privacy notice
* Tweak: Set javascript redirect to false by default
* Fix: Hide SSL notice on multisite for all subsites, and show only for "activate_plugins" cap users

= 3.0 =
* Added a built-in certificate check in the class-certificate.php file that checks if the domain is present in the common names and/or the alternative names section.
* The .htaccess redirect now uses $1 instead of {REQUEST_URI}.
* Added an option to deactivate the plugin while keeping SSL in the SSL settings.
* Added a filter for the Javascript redirect.
* Added a sidebar with recommended plugins.

= 2.5.26 =
* Fix: multisite menu not showing when main site is not SSL.
* Fix: the admin_url and site_url filter get an empty blog_id when checking the URL for the current blog.
* Tweak: added comment to encourage backing up to activation notice.
* Tested the plugin with Gutenberg.

= 2.5.25 =
* Fix: "switch mixed content fixer hook" option not visible on the multisites settings page
* Tweak: several typo's and uppercasing

= 2.5.24 =
* Fix: On multisite, admin_url forced current blog URL's over http even when the current blog was loaded over https. This will now only force http for other blog_urls than the current one, when they are on http and not https.

= 2.5.23 =
* Tested up to WP 4.9
* Added secure cookie notice

= 2.5.22 =
* Changed mixed content fixer hook back from wp_print_footer_scripts to shutdown

= 2.5.21 =
* Fixed double slash in paths to files
* Fixed typo in activation notice.
* Tweak: added option to not flush the rewrite rules
* Fix: prevent forcing admin_url to http when FORCE_SSL_ADMIN is defined

= 2.5.20 =
* Tweak: constant RSSSL_DISMISS_ACTIVATE_SSL_NOTICE to allow users to hide notices.
* Tweak: setting to switch the mixed content fixer hook from template_redirect to init.
* Fix: nag in multisite didn't dismiss properly

= 2.5.19 =
* Multisite fix: due to a merge admin_url and site_url filters were dropped, re-added them
* Added constant RSSSL_CONTENT_FIXER_ON_INIT so users can keep on using the init hook for the mixed content fixer.

= 2.5.18 =
* Tweak: Removed JetPack fix, as it is now incorporated in JetPack.
* Tweak: Moved mixed content fixer hook to template_redirect
* Fix: Changed flush rewrite rules hook from admin_init to shutdown, on activation of SSL.
* Multisite fix: Changed function which checks if admin_url and site_url should return http or https to check for https in home_url.
* Tweak: Explicitly excluded json and xmlrpc requests from the mixed content fixer

= 2.5.17 =
* Tweak: Added a function where the home_url and site_url on multisite check if it should be http or https when SSL is enabled on a per site basis.
* Tweak: Added a notice that there will be no network menu when Really Simple Security is activated per site.
* Tweak: Added hook for new multisite site so a new site will be activated as SSL when network wide is activated.
* Tweak: limited the JetPack listen on port 80 tweak to reverse proxy servers.
* Tweak: created a dedicated rest api redirect constant in case users want to prevent the rest api from redirecting to https.
* Fix: dismissal of SSL activated notice on multisite did not work properly

= 2.5.16 =
* Reverted wp_safe_redirect to wp_redirect, as wp_safe_redirect causes a redirect to wp-login.php even when the primary url is domain.com and request url www.domain.com

= 2.5.15 =
* No functional changes, version change because WordPress was not processing the version update

= 2.5.14 =
* Fix: fixed issue in the mixed content fixer where on optimized html the match would match across elements.
* replaced wp_redirect with wp_safe_redirect
* Added force SSL on wp_rest_api

= 2.5.13 =
* Tweak: configuration more function

= 2.5.12 =
* Added multisite settings page
* Added filter for .htaccess code output
* Increased user capability to "activate_plugins"
* Added SSL_FORWARDED_PROTO = 1 in addition to SSL_FORWARDED_PROTO = on as supported SSL recognition variable.

= 2.5.11 =
* Removed curl in favor of wp_remote_get

= 2.5.10 =
* Fastest cache compatibility fix

= 2.5.9 =
* Multisite tweaks

= 2.5.8 =
* Removed automatic insertion of .htaccess redirects. The .htaccess redirects work fine for most people, but can cause issues in some edge cases.
* Added option to explicitly insert .htaccess redirect
* Added safe mode constant RSSSL_SAFE_MODE to enable activating in a minimized way
* Fix: RLRSSSL_DO_NOT_EDIT_HTACCESS constant did not override setting correctly when setting was used before.
* Dropped cache flushing on activation, as this does not always work as expected

= 2.5.7 =
* Tweak: changes testurl to the function test_url()

= 2.5.6 =
* version nr fix

= 2.5.5 =
* Reverted some changes to 2.4.3, as it was causing issues for some users.

= 2.5.4 =
fix: Adjusted selection order of .htaccess rules, preventing redirect loops

= 2.5.3 =
* Changed .htaccess redirects to use only one condition

= 2.5.2 =
* removed file_get_contents function from class_url.php, as in some cases this causes issues.

= 2.5.1 =
* Added help tooltips
* Fix: typos in explanations
* Added detected server to debug Log
* Added test folder for CloudFlare
* Added htaccess redirect to use all available server vars for checking SSL.

= 2.5.0 =
* Tweak: Improved support for cloudflare
* Tweak: Added support for Cloudfront, thanks to Sharif Alexandre
* Fix: Prevent writing of empty .htaccess redirect
* Tweak: Added option for 301 internal wp redirect
* Tweak: Improved NGINX support
* Tweak: Added support for when only the $_ENV[HTTPS] variable is present
* Fix: Mixed content fixing of escaped URLS

= 2.4.3 =
* Removed banner in admin

= 2.4.2 =
* Tweak: Added reload over https link for when SSL was not detected
* Fixed: After reloading page when the .htaccess message shows, .htaccess is now rewritten.
* Tweak: Removed Yoast notices
* Tested for WP 4.7
* Fixed: bug where network options were not removed properly on deactivation
* Tweak: Changed mixed content marker to variation without quotes, to prevent issues with scripting etc.

= 2.4.1 =
* Tweak: improved HSTS check

= 2.4.0 =
* Fixed: added a version check on wp_get_sites / get_sites to get rid of deprecated function notice, and keep backward compatibility.
* Fixed: A bug in multisite where plugin_url returned a malformed url in case of main site containing a trailing slash, and subsite not. Thanks to @gahapati for reporting this bug.
* Tweak: Added button to settings page to enable SSL, for cases where another plugin is blocking admin notices.
* Tweak: Rebuilt the mixed content fixer, for better compatibility
* Tweak: Improved the mixed content marker on the front-end, so it's less noticeable, and won't get removed by minification code.

= 2.3.14 =
* Fixed: Clearing of WP Rocket cache after SSL activation causing an error
* Fixed: Clearing of W3TC after SSL activation did not function properly

= 2.3.13 =
* Re-inserted Jetpack fix.

= 2.3.12 =
* Requires at least changed back to 4.2, as the function that this was meant for didn’t make it in current release yet.

= 2.3.11 =
* Improved request method in url class
* Added check if .htaccess actually exists in htaccess_contains_redirect_rules()
* Made activation message more clear.

= 2.3.10 =
* Tested for 4.6
* Tweak: changed check for htaccess redirect from checking the RSSSL comments to checking the redirect rule itself
* Fix: htaccess not writable message not shown anymore when SSL not yet enabled
* Tweak: extended mixed content fixer to cover actions in forms, as those should also be http in case of external urls.
* Tweak: added safe domain list for domains that get found but are no threat.
* Tweak: added filter for get_admin_url in multisite situations, where WP always returns an https url, although the site might not be on SSL
* Tweak: htaccess files and wpconfig are rewritten when the settings page is loaded

= 2.3.9 =
* Fix: removed internal WordPress redirect as it causes issues for some users.
* Tweak: improved url request method

= 2.3.8 =
* Tweak: Fallback redirect changed into internal wp redirect, which is faster
* Tweak: When no .htaccess rules are detected, redirect option is enabled automatically
* Tweak: Url request falls back to file_get_contents when curl does not give a result

= 2.3.7 =
* Updated screenshots

= 2.3.6 =
* Fixed: missing priority in template_include hook caused not activating mixed content fixer in some themes

= 2.3.5 =
* Fixed: javascript redirect insertion

= 2.3.4 =
* Tweak: load css stylesheet only on options page and before enabling ssl
* Tweak: mixed content fixer triggered by is_ssl(), which prevents fixing content on http.
* Start detection and configuration only for users with "manage_options" capability

= 2.3.3 =
* Fixed bug in force-deactivate script

= 2.3.2 =
* Changed SSL detection so test page is only needed when not currently on SSL.
* Some minor bug fixes.

= 2.3.1 =
* Removed "activate ssl" option when no ssl is detected.
* Optimized emptying of cache
* Fixed some bugs in deactivation and activation of multisite

= 2.3.0 =
* Gave more control over activation process by explicitly asking to enable SSL.
* Added a notice if .htaccess is not writable

= 2.2.20 =
Fixed a bug in SSL detection

= 2.2.19 =
Changed followlocation in curl to an alternative method, as this gives issues when safemode or open_basedir is enabled.
Added dismissable message when redirects cannot be inserted in the .htaccess

= 2.2.18 =
Fixed bug in logging of curl detection

= 2.2.17 =
Security fixes in ssl-test-page.php

= 2.2.16 =
Bugfix with of insecure content fixer.

= 2.2.13 =
Added a check if the mixed content fixer is functioning on the front end
Fixed a bug where multisite per_site_activation variable wasn't stored networkwide
Added clearing of wp_rocket cache thans to Greg for suggesting this
Added filter so you can remove the really simple security comment
Fixed a bug in the output buffer usage, which resolves several issues.
Added code so JetPack will run smoothly on SSL as well, thanks to Konstantin for suggesting this

= 2.2.12 =
* To prevent lockouts, it is no longer possible to activate plugin when wp-config.php is not writable. In case of loadbalancers, activating ssl without adding the necessary fix in the wp-config would cause a redirect loop which would lock you out of the admin.
* Moved redirect above the WordPress rewrite rules in the htaccess file.
* Added an option to disable the fallback javascript redirection to https.

= 2.2.11 =
Brand new content fixer, which fixes all links on in the source of your website.

= 2.2.10 =
* Roll back of mixed content fixer.

= 2.2.9 =
Improved the mixed content fixer. Faster and more effective.

= 2.2.8 =
Edited the wpconfig define check to prevent warnings when none are needed.

= 2.2.7 =
* Extended detection of homeurl and siteurl constants in wp-config.php with regex to allow  for spaces in code.
* Changed text domain to make this plugin language packs ready
* Added 404 detection to SSL detection function, so subdomains can get checked properly on subdomain multisite installs

= 2.2.6 =
Added slash in redirect rule
small bugfixes

= 2.2.3 =
documentation update

= 2.2.2 =
* Added multisite support for the missing https server variable issue
* Improved curl connection script
* Added French translation thanks to Cedric

= 2.2.1 =
* Small bug fixes

= 2.2.0 =
* Added per site activation for multisite, but excluded this option for subfolder installs.
* Added script to easily deactivate the plugin when you are locked out of the WordPress admin.
* Added support for a situation where no server variables are given which can indicate SSL, which can cause WordPress to generate errors and redirect loops.
* Removed warning on WooCommerce force SSL after checkout, as only unforce SSL seems to be causing problems
* Added Russian translation, thanks to xsascha
* Improved redirect rules in the .htaccess
* Added option te disable the plugin from editing the .htaccess in the settings
* Fixed a bug where multisite would not deactivate correctly
* Fixed a bug where insecure content scan would not scan custom post types

= 2.1.18 =
* Made WooCommerce warning dismissable, as it does not seem to cause issues
* Fixed a bug caused by WP native plugin_dir_url() returning relative path, resulting in no SSL messages

= 2.1.17 =
* Fixed a bug where example .htaccess rewrite rules weren't generated correctly
* Added WooCommerce to the plugin conflicts handler, as some settings conflict with this plugin, and are superfluous when you force your site to SSL anyway.
* Excluded transients from mixed content scan results

= 2.1.16 =
* Fixed a bug where script would fail because curl function was not installed.
* Added debug messages
* Improved FAQ, removed typos
* Replaced screenshots

= 2.1.15 =
* Improved user interface with tabs
* Changed function to test SSL test page from file_get_contents to curl, as this improves response time, which might prevent "no SSL messages"
* Extended the mixed content fixer to replace src="http:// links, as these should always be https on an SSL site.
* Added an error message in case of force rewrite titles in Yoast SEO plugin is used, as this prevents the plugin from fixing mixed content

= 2.1.14 =
* Added support for loadbalancer and is_ssl() returning false: in that case a wp-config fix is needed.
* Improved performance
* Added debugging option, so a trace log can be viewed
* Fixed a bug where the rlrsssl_replace_url_args filter was not applied correctly.

= 2.1.13 =
* Fixed an issue where in some configurations the replace url filter did not fire

= 2.1.12 =
* Added the force SSL option, in cases where SSL could not be detected for some reason.
* Added a test to check if the proposed .htaccess rules will work in the current environment.
* Readded HSTS to the htaccess rules, but now as an option. Adding this should be done only when you are sure you do not want to revert back to http.

= 2.1.11 =
* Improved instructions regarding uninstalling when locked out of back-end

= 2.1.10 =
* Removed HSTS headers, because it is difficult to roll back.

= 2.1.9 =
* Added the possibility to prevent htaccess from being edited, in case of redirect loop.
= 2.1.7 =
* Refined SSL detection
* Bugfix on deactivation of plugin

= 2.1.6 =
* Fixed an SSL detection issue which could lead to redirect loop

= 2.1.4 =
* Improved redirect rules for .htaccess

= 2.1.3 =
* Now plugin only changes .htaccess when one of three preprogrammed ssl types was recognized.
* Simplified filter use to add your own urls to replace, see f.a.q.
* Default javascript redirect when .htaccess redirect does not succeed

= 2.1.2 =
* Fixed bug where number of options with mixed content was not displayed correctly

= 2.1.1 =
* limited the number of files, posts and options that can be show at once in the mixed content scan.

= 2.1.0 =
* Added version control to the .htaccess rules, so the .htaccess gets updated as well.
* Added detection of loadbalancer and cdn so .htaccess rules can be adapted accordingly. Fixes some redirect loop issues.
* Added the possibility to disable the auto replace of insecure links
* Added a scan to scan the website for insecure links
* Added detection of in wp-config.php defined siteurl and homeurl, which could prevent from successful url change.
* Dropped the force ssl option (used when not ssl detected)
* Thanks to Peter Tak, [PTA security](http://www.pta-security.nl/) for mentioning the owasp security best practice https://www.owasp.org/index.php/HTTP_Strict_Transport_Security in .htaccess,

= 2.0.7 =
* Added 301 redirect to .htaccess for seo purposes

= 2.0.3 =
* Fixed some typos in readme
* added screenshots
* fixed a bug where on deactivation the https wasn't removed from siturl and homeurl

= 2.0.0 =
* Added SSL detection by opening a page in the plugin directory over https
* Added https redirection in .htaccess, when possible
* Added warnings and messages to improve user experience
* Added automatic change of siteurl and homeurl to https, to make backend ssl proof.
* Added caching flush support for WP fastest cache, Zen Cache and W3TC
* Fixed bug where siteurl was used as url to fix instead of homeurl
* Fixed issue where url was not replaced on front end, when used url in content is different from home url (e.g. http://www.domain.com as homeurl and http://domain.com in content)
* Added filter so you can add cdn urls to the replacement script
* Added googleapis.com/ajax cdn to standard replacement script, as it is often used without https.

= 1.0.3 =
* Improved installation instructions

== Upgrade notice ==
On settings page load, the .htaccess file is no rewritten. If you have made .htaccess customizations to the RSSSL block and have not blocked the plugin from editing it, do so before upgrading.
Always back up before any upgrade. Especially .htaccess, wp-config.php and the plugin folder. This way you can easily roll back.

== Screenshots ==
1. Your Really Simple Security dashboard - For optimal configuration.
2. The Server Health Check - An in-depth look at your server.
3. New Hardening Features - Fortify your website by minimizing weaknesses.
