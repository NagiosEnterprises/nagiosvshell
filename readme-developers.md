# Developer Readme

V-Shell stands for visual shell, and is meant to be an alternative way to view
Nagios information through a web browser.

## Small changes

Sometimes a small change or two doesn't warrant setting up an entire dev
environment or getting to know the ins-and-outs of the project.

If you have a small change, fork the repository, commit the changes,
and submit a pull request. One of the developers will handle integrating it 
into the project.

But if you'd like to know more about the project and contribute on a larger
scale, read on!

## Coming up with an idea

Developers are encouraged to use GitHub issues to publicly discuss large
changes before diving into code. This gives the entire V-Shell community time
to add their opinions and ensure V-Shell is moving in the right direction.

## How Nagios and V-Shell interact

When the Nagios service checks the state of nodes, it writes the results to
a couple of key files on the server. On RHEL systems the default directory is
`/var/log/nagios`. The two most important files are the `status.dat` and
`objects.cache`.

V-Shell works by parsing these files directly for information. It does not
communicate with the running Nagios process.

## Application anatomy

V-Shell is made up two parts, the api and the frontend.

The api parses the Nagios files and outputs JSON data through a RESTful API. It
is written in PHP and uses the [CodeIgniter](https://ellislab.com/codeigniter)
framework.

The frontend consumes the information from the API and renders the site. It is
written in JavaScript and uses [AngularJS](https://angularjs.org/). The project
follows the [Angular Seed](https://github.com/angular/angular-seed) layout.

## Automation

V-Shell relies on a number of JavaScript packages to keep code organized
and tested.

The frontend uses [Karma](https://karma-runner.github.io) as a test runner and
[Jasmine BDD](https://jasmine.github.io/) for unit tests.

End-to-end tests are supported using
[Protractor](https://github.com/angular/protractor).

Code hinting and beautification are done by [Grunt](http://gruntjs.com/) using
[JS Hint](http://www.jshint.com/) and [JS
Beautifier](https://github.com/beautify-web/js-beautify)

## Setting up a dev environment

1. Clone the repository to any local directory

2. Install [NodeJS](http://nodejs.org/)

3. Install [`grunt-cli`](http://gruntjs.com/getting-started)

4. Install [Phantom JS](http://phantomjs.org/quick-start.html)

5. `cd` into the root repository directory and run `npm install`. This will
read the `package.json` contents and install the dev dependencies locally.

Once done, confirm the environment by running `npm test` and `grunt`.

## Configuring a server for live testing

Live testing is can be done on any computer with a running Nagios instance and
properly configured web server.

The recommended - but by no means required! - environment is on a
RHEL/Debian linux server. This ensures V-Shell will run smoothly on the most
common deployment environment.

If you do decide to run a Linux server, automated server configuration is
available via Ansible and
[chrislaskey/ansible-configure](https://github.com/chrislaskey/ansible-configure).

Once the server is configured, execute `./install.php` from the repository root
to load the latest changes. Then view the changes in the browser at
`http://your-server-ip/vshell2`

## Running automation and passing tests

Using `npm test` in a separate terminal window will automatically re-run unit
tests when files change.

If tests are passing and new changes look good, the final step is running the
automated tools with Grunt.

Note: always commit code locally before running any `grunt` commands! It's
better to have a longer commit history than have a contributor lose code.

Running `grunt` will test frontend JavaScript code against a specific set of
rules. It will output any problems it sees with specific line and column
numbers. It will also modify the whitespace in each file automatically.

Once the tests pass and `grunt` is happy, the next step is to commit the changes
and upload to your fork on GitHub.

V-Shell code is tested by [Travis-CI](https://travis-ci.org/) whenever a commit
is pushed to GitHub. Before changes can be merged into the main repository, all
Travis-CI tests must pass. See `.travis.yaml` for an up to date list of tests run.

These same tests can be enabled on your public fork of the GitHub repository by
creating a Travis-CI account and turning on your project fork.

## Merging in changes

The final step is to create a pull request on GitHub and wait for your changes
to be merged in. Both Mike and Chris work on this project in their spare time,
so please understand if it takes us a some time to review things!
