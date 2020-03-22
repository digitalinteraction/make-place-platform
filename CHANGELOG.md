# Changelog
The versions of Make Place and what features, bugs and tweaks were made for each.

## 3.8.2
- Fix crash when submitting a valid CheckboxQuestion

## 3.8.1
- Fix CheckboxQuestion failing when not answered

## 3.8.0
- Add CheckboxQuestion and RadioQuestion
  - Checkbox lets you check multiple options
  - Radio lets you pick a single value

## 3.7.0
- Sub-pages on map pages now have hash routes, e.g. '/page/#/view/1234'
  - You can deep link to specific responses / surveys
  - When viewing a response it pans to it
- (Internal) Upgraded and simplified the buildchain with webpack 4

## 3.6.4
- Fixed docker build issue

## 3.6.3 (broken build)
- Fixed a bug when creating a map pin fails and the interface locks up

## 3.6.2
- Sass assets are only rebuild when a variable changes (speeding up boot time)

## 3.6.1
- Tweaked the wording on the consent page

## 3.6.0
- Added consent workflow
  - Consent to the terms is stored per member, whether you accepted and when you submitted that
  - Added `/consent` page to change your consent at any time
  - Any page on the site will redirect to `/consent` if a member has not consented to the latest terms
  - Added `ConsentEffectiveDate` in `/admin/settings` for when the terms were last revised, updating which will trigger re-consent from members
- Added templated `Terms` and `Privacy` pages so deployments can quickly add them
- Changed cookies to be opt-in, Google Analytics will **only** be enabled if the member consents

## 3.5.1
- Fixed minor scrollbars bug

## 3.5.0
- HeatMapComponent now renders linestrings as well as single points

## 3.4.0
- Added more filter types
  - Filter by survey
  - Filter by a TextQuestion on a survey
  - Filter by a DropdownQuestion on a survey

## 3.3.1
- Fixed emailing from lines not being set

## 3.3.0
- Added filtering capability
  - Responses on the map use a service to fetch them
  - Filters can be registered to that service, which're applied when the data loads
- Implemented TemporalFilter, to filter all responses in a time-based way

## 3.2.1
- Removed colour re-compilation (for now)

## 3.2.0

### Features
- Heatmaps, render survey's responses with a heatmap
  - Choose the question to position the heat
  - Optionally choose a question to weight the values
  - Customise the size, opacity and weight values through the CMS
- Different vote styles, agree/disagree or up/down voting

## 3.1.4
- Fixed bug with highlighted geometries on the map
  - Moved `api/survey/:id/response/:id` to `api/survey/:id/response/:id/view`
  - Added `api/survey/:id/response/:id` to return the response json

## 3.1.3
- Improved map performance, `api/survey/:id/responses` accepts `?pluck=a,b,c` to only retrieve specific responses

## 3.1.2
- Hidden question's responses won't get rendered

## 3.1.1
- Fixed ipad-sized map styling issues
- Removed some debug code


## 3.1.0

### Features
- More detailed authentication controls for surveys, each option as (member, specific groups, no-one or anyone)
  - Who can see responses
  - Who can respond
  - Who can see comments
  - Who can make comments
  - Who can see votes
  - Who can make votes
- Linestrings are now validated in the api


## 3.0.1
- Fixed minimisation issues


## 3.0.0
Interactions and swish animations

### Features
- Dynamic Commenting and voting apis so you can comment on anythin in the database (providing it implements Commentable / Votable)
- Frontend overhaul, we're now using webpack to compile easy-to-write javascript (es6) into supported javascript that works on nearly all browsers
- Vuejs map page, the new map page uses vuejs to improve the previos js logic and improve the overall experience
- Customisable the theme of each deployment through docker environment variables

### Tweaks
- Moved to sendgrid for sending emails, as clients don't trust random webservers
- Vector-based map pins for criper images
- Improved user flow when registering & better messages of what needs to be done along the way
- Moved to node-sass to compile scss on startup
- Reorderable Questions and MapComponents
- Simplified and improved the admin experience improving titles and adding descriptive fields
- General style improvements (new buttons!) using multifile-sass for a more organised project
- Added new map detail icons to improve clarity

### Fixes
- LoginController checks BackUrl is on site to prevent spoofing
- LoginController escapes values passed to active?email to prevent off-site scripting
- Fixed ie styling bug with MapPage's detail showing scroll bars when it didn't need them


***


## 2.0.0
User management and media-based questions

### Features
- User management, register, login, edit your profile and reset your passowrd
- Media based questions, ask your users to upload media as part of a survey (supports images, audio & video)
- Different rendering tiles (OpenStreetMap vs Google)
- Geo api integration to store all geometries in a centralized database
- Api Documentation, so you know what the endpoints are and how you use them

### Tweaks
- Pass created dates to the Survey api to pre-date responses i.e. if they were created in the past and stored locally for a bit.
- You can now add multiple components of the same time to a map
- Extra relations from SurveyResponse to Geometries and media for fast access (not through the json-in-sql situation)


***


## 1.0.0
The general setup of the project, docker, testing & basic features.

### Features
- Building an image with Dockerfile
- Unit testing setup with phpunit
- CI setup with gitlab, runs code coverage
- MapPage Components to compose logic through the CMS
- Survey logic and api to interact with it
