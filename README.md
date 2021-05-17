# authcorebos Plugin for DokuWiki

## Authenticate using coreBOS users

All documentation for this plugin can be found at [coreBOS-DokuWiki Login](https://github.com/coreBOS/dokuwikilogin)

If you install this plugin manually, make sure it is installed in `lib/plugins/authcorebos/` - if the folder is called different it will not work!

Please refer to [DokuWiki plugins](http://www.dokuwiki.org/plugins) for additional info on how to install plugins in DokuWiki.

This plugin will permit us to configure one or more coreBOS installs where we will validate user access into DokuWiki, following these rules:

- DokuWiki users with an admin role are only validated locally in DokuWiki
- if the given user is found and validated locally, access will be granted
- if the given user is not found locally or cannot be validated, we will try to validate the credentials against the selected coreBOS install. If access is granted a local user will be created with the coreBOS install prefixed to the user name. This new user will be used in subsequent validations
- if the user changes his password in coreBOS the password will be updated in DokuWiki the next time they try to access
- the new user will have the default group(s) indicated in the plugin settings (see below)
- access will be denied otherwise

In **Settings** we can define:

- **New user role(s)** a comma-separated list of existing DokuWiki roles to be applied to new users created by the authentication plugin.
- **Basic Auth Settings** for those coreBOS installs that are Basic Auth protected we can add the user and password here. **NOTE** that ALL the installs must be protected and share the same user credentials. If you need different credentials for each install then leave these fields empty and put the individual credentials in each coreBOS URL field.
- **Number of installs** defines how many coreBOS installs we want to validate with. By default, only 1.
- **Install Information** for each install we need:
  - **Name** to be shown in the login screen for the user to select the correct install to validate in
  - **Prefix** an internal identifier that will be used when creating users locally. This is necessary to avoid user name collision between different coreBOS installs
  - **URL** of the coreBOS install

To **activate** the authentication plugin you must go to the DokuWiki configuration screen, in the **Authentication** section, and select **authcorebos** in the **Authentication backend** option.

You can change the coreBOS reference name in the translation and settings files in `lib/plugins/authcorebos/lang/`  You can also translate the plugin to other languages there.

----
Copyright (C) Joe Bordes <joe@tsolucio.com>

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.

See the License for the specific language governing permissions and limitations under the License.
